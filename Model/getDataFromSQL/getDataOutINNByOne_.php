<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27.03.2017
 * Time: 20:53
 */

namespace App\Model\getDataFromSQL;
use App\Model\Exception\noCorrectDataException;
use App\Model\workDateForSQL;


/**
 * Задача класса предоставить данные для заполннения анализа обязательств
 * реестра и ЕРПН в разрезе ИНН по одному филиалу
 *
 * реализация нового алгоритма с учетом все РКЕ зарегистрированых в отчетном периоде
 * весь SQL описан в файле а не в хранимых процедурах
 *
 * Class getDataOutINNByOne_
 * @package AnalizPdvBundle\Model\getDataFromSQL
 */
class getDataOutINNByOne_ extends getDataFromAnalizAbstract
{
	/**
	 * месяц анализа
	 * @var int
	 */
	private $monthAnaliz;
	protected $monthAnailzCorrect=["1","2","3","4","5","6","7","8","9","10","11","12"];
	/**
	 * год анализа
	 * @var int
	 */
	private $yearAnaliz;
	protected $yearAnalizCorrect=["2015","2016","2017"];
	protected $numBranch;
	/**
	 * хранит запрос на формирование временной таблицы по данынм  ErpnOut
	 * @var string temp_erpn_out_inn_group_numbranch
	 */
	protected $SQLTmpTableErpnOut;
	/**
	 * хранит запрос на формирование временной таблицы по данным  ReestrOut
	 * @var string temp_reestr_out_inn_group_numbranch
	 */
	protected $SQLTmpTableReestrOut;
	/**
	 * хранит запрос на формирование временной таблицы которая хранит
	 * данные внутренненго соединения временних таблиц
	 * - temp_erpn_out_inn_group_numbranch
	 * - temp_reestr_out_inn_group_numbranch
	 * @var string
	 */
	protected $SQLTmpTableOut_InnerJoin;
	/**
	 * запрос на выборку документов из ErpnOut
	 * @var string
	 */
	protected $SQLOut_InnerJoin_DocByErpn;
	/**
	 * запрос на выборку документов из ReestrOut
	 * @var string
	 */
	protected $SQLOut_InnerJoin_DocByReestr;

	/**
	 * Хранит запрос на формирование временной таблицы на выборку
	 * данных которые есть только в ЕРПН
	 * @var Out_LeftJoin
	 */
	protected $SQLTmpTableOut_LeftJoin;

	/**
	 * Выборка документов из временной таблицы Out_LeftJoin из ЕРПН
	 * @var
	 */
	protected $SQLOut_LeftJoin_DocByErpn;
	/**
	 * Хранит запрос на формирование временной таблицы на выборку
	 * данных которые есть только в РПН
	 * @var Out_RightJoin
	 */
	protected $SQLTmpTableOut_RightJoin;
	/**
	 * Выборка документов из временной таблицы Out_RightJoin из РПН
	 * @var
	 */
	protected $SQLTmpTableOut_RightJoin_DocByReestr;
	/**
	 * @var workDateForSQL
	 */
	private $workDate;

	/**
	 * getDataOutINNByOne_ constructor.
	 * @param EntityManager $em
	 * @param $month
	 * @param $year
	 * @param $numBranch
	 * @throws noCorrectDataException
	 */
	public function init($month,$year,$numBranch)
	{
		if (in_array($month, $this->monthAnailzCorrect)){
			$this->monthAnaliz = $month;
		} else{
			throw new noCorrectDataException("Номер месяца вне диапазона. Инициализация объекта не проведена ");
		}

		if (in_array($year, $this->yearAnalizCorrect)){
			$this->yearAnaliz = $year;
		} else{
			throw new noCorrectDataException("Номер года вне диапазона. Инициализация объекта не проведена ");
		}
		if ($this->monthAnaliz<12 and $this->yearAnaliz==2015){
			throw new noCorrectDataException("Дата меньше 12-2015 не допускается. Инициализация объекта не проведена ");
		}

		try{
			$this->workDate=new workDateForSQL($this->monthAnaliz, $this->yearAnaliz);
		} catch (noCorrectDataException $e){
			echo 'Поймано исключение при инициализации класса workDateForSQL: ',  $e->getMessage(), "\n";
		}
		$this->numBranch=$numBranch;
		$this->initSQL();
		$this->createTmpTable();
	}

	/**
	 * класс проводит сборку запросов которые завист от даты анализа
	 */
	private function initSQL(){
		// подключаем начало запросов
			// временная таблица по данным ЕРПН
		$this->SQLTmpTableErpnOut=$this->getSQLCreateTmpTableErpnOut_begin();
			// выборка документов из ЕРПН внутренне соединение Out_InnerJoin
		$this->SQLOut_InnerJoin_DocByErpn=$this->getSQLOut_InnerJoin_DocByErpn_begin();
			// выборка документов из ЕРПН  соединение Out_LeftJoin
		$this->SQLOut_LeftJoin_DocByErpn=$this->getSQLOut_LeftJoin_DocByErpn_begin();
		/*
		 *	если актуально делать запрос данных из предвдущего периода то подключаем
		 *  к запросам часть которая выбирает эти данные
		**/
		if(!is_null($this->workDate->getMonthMinisOne())and (!is_null($this->workDate->getYearMinisOneMonth()))){
			$this->SQLTmpTableErpnOut.=$this->getSQL_withMonthMinusOne();
			$this->SQLOut_InnerJoin_DocByErpn.=$this->getSQL_withMonthMinusOne();
			$this->SQLOut_LeftJoin_DocByErpn.=$this->getSQL_withMonthMinusOne();
		}
		/* если актуально делать запрос данных из других прошедших периодов
			кроме предудущего периода то подключаем
		    к запросам часть которая выбирает эти данные
		**/
		if(!is_null($this->workDate->getMonthMinisTwo())and (!is_null($this->workDate->getYearMinisTwoMonth()))){
			$this->SQLTmpTableErpnOut.=$this->getSQL_withMonthMinusTwo();
			$this->SQLOut_InnerJoin_DocByErpn.=$this->getSQL_withMonthMinusTwo();
			$this->SQLOut_LeftJoin_DocByErpn.=$this->getSQL_withMonthMinusTwo();
		}
		// подключаем конечные части запросов
		$this->SQLTmpTableErpnOut.=$this->getSQLCreateTmpTableErpnOut_end();
		$this->SQLOut_InnerJoin_DocByErpn.=$this->getSQLOut_InnerJoin_DocByErpn_end();
		$this->SQLOut_LeftJoin_DocByErpn.=$this->getSQLOut_LeftJoin_DocByErpn_end();
	}
	/**
	 * Запросы на формирование всех временных таблиц
	 */
	private function createTmpTable(){
		$this->createTmpTable_ErpnOut();
		$this->createTmpTable_ReestrOut();
		$this->createTmpTableOut_InnerJoin();
		$this->createTmpTableOut_LeftJoin();
		$this->createTmpTableOut_RightJoin();
	}

	/**
	 * Создание временной таблицы ErpnOut
	 */
	private function createTmpTable_ErpnOut(){
		// собираем в один запрос все запросы на создание временных таблиц
		$SQLCreateTmpTable = "";
		$SQLCreateTmpTable.=$this->SQLTmpTableErpnOut;
		// подключаем созданный сводный запрос
		$smtp=$this->em->getConnection()->prepare($SQLCreateTmpTable);

		// заполняем параметры запроса
		$smtp->bindValue("m",$this->monthAnaliz);
		$smtp->bindValue("y",$this->yearAnaliz);
		$smtp->bindValue("nb",$this->numBranch);
		$smtp->bindValue("dateBeginRKE",$this->workDate->getStartPeriodAnalizRke());
		$smtp->bindValue("dateEndRKE",$this->workDate->getЕndPeriodAnalizRke());
		if(!is_null($this->workDate->getMonthMinisOne())and (!is_null($this->workDate->getYearMinisOneMonth()))){
			$smtp->bindValue("m_minusOne",$this->workDate->getMonthMinisOne());
			$smtp->bindValue("y_minusOne",$this->workDate->getYearMinisOneMonth());
			$smtp->bindValue("dataBegin_minusOne",$this->workDate->getStartPeriodAnalizRkeMinusOne());
			$smtp->bindValue("dataEnd_minusOne",$this->workDate->getЕndPeriodAnalizRkeMinusOne());
		}
		if(!is_null($this->workDate->getMonthMinisTwo())and (!is_null($this->workDate->getYearMinisTwoMonth()))){
			$smtp->bindValue("dataBeginCreate_invoice",$this->workDate->getStartPeriodAnalizRkeMinusTwo());
			$smtp->bindValue("dataEndCreate_invoice",$this->workDate->getЕndPeriodAnalizRkeMinusTwo());
			$smtp->bindValue("dataBegin_minusTwo",$this->workDate->getStartPeriodAnalizRke());
			$smtp->bindValue("dataEnd_minusTwo",$this->workDate->getЕndPeriodAnalizRkeMinusOne());
		}
		// создаем временные таблицы
		$smtp->execute();
	}

	/**
	 * Создание временной таблицы ReestrOut
	 */
	private function createTmpTable_ReestrOut(){
		// собираем в один запрос все запросы на создание временных таблиц
		$SQLCreateTmpTable = "";
		$SQLCreateTmpTable.=$this->getSQLCreateTmpTableReestrOut();
		// подключаем созданный сводный запрос
		$smtp=$this->em->getConnection()->prepare($SQLCreateTmpTable);

		// заполняем параметры запроса
		$smtp->bindValue("m",$this->monthAnaliz);
		$smtp->bindValue("y",$this->yearAnaliz);
		$smtp->bindValue("nb",$this->numBranch);
		// создаем временные таблицы
		$smtp->execute();
	}

	/**
	 * Создание временной таблицы Out_InnerJoin
	 */
	private function createTmpTableOut_InnerJoin(){
		// собираем в один запрос все запросы на создание временных таблиц
		$SQLCreateTmpTable = "";
		$SQLCreateTmpTable.=$this->getSQLCreateTmpTableOut_InnerJoin();
		// подключаем созданный сводный запрос
		$smtp=$this->em->getConnection()->prepare($SQLCreateTmpTable);
		// создаем временные таблицы
		$smtp->execute();
	}

	/**
	 * Создание временной таблицы Out_LeftJoin
	 */
	private function createTmpTableOut_LeftJoin(){
		// собираем в один запрос все запросы на создание временных таблиц
		$SQLCreateTmpTable = "";
		$SQLCreateTmpTable.=$this->getSQLCreateTmpTableOut_LeftJoin();
		// подключаем созданный сводный запрос
		$smtp=$this->em->getConnection()->prepare($SQLCreateTmpTable);
		// создаем временные таблицы
		$smtp->execute();
	}

	/**
	 * Создание временной таблицы Out_RightJoin
	 */
	private function createTmpTableOut_RightJoin(){
		// собираем в один запрос все запросы на создание временных таблиц
		$SQLCreateTmpTable = "";
		$SQLCreateTmpTable.=$this->getSQLCreateTmpTableOut_RightJoin();
		// подключаем созданный сводный запрос
		$smtp=$this->em->getConnection()->prepare($SQLCreateTmpTable);
		// создаем временные таблицы
		$smtp->execute();
	}

	/**
	 * головная часть запроса который формирует временную таблицу
	 * Алгоритм формирования
	 * - все ПНЕ текущуго периода без учета времени регистрации
	 * - все РКЕ текущего периода с регистрацией от первого дня текущего месяце до 15 дня месяца следующего за текущим
	 * - все РКЕ прошлого периода (если он есть) с регистрацией от 16 дня текущего месяце до последнуго дня текущего месяца
	 * (генерируется getSQLCreateTmpTableErpnOut_withMonthMinusOne)
	 * - все РКЕ прошлых периодов (если они есть) с регистрацией от 1 дня текущего месяце до последнуго дня текущего месяца
	 * (генерируется getSQLCreateTmpTableErpnOut_withMonthMinusTwo)
	 * @return string temp_erpn_out_inn_group_numbranch
	 */
	private function getSQLCreateTmpTableErpnOut_begin()
	{
		return /** @lang MySQL */
			"	CREATE TEMPORARY TABLE IF NOT EXISTS  temp_erpn_out_inn_group_numbranch (
		    month_create int(11)DEFAULT NULL ,
		    year_create year(4) DEFAULT NULL,
		    inn_client varchar(255) DEFAULT NULL ,
		    num_main_branch varchar(255) DEFAULT NULL ,
		    type_invoice_full varchar(255) DEFAULT NULL,
		    pdv decimal(15, 2) DEFAULT NULL ,
		    id int NOT NULL AUTO_INCREMENT,
		    PRIMARY KEY (id),
		    INDEX main USING BTREE (month_create, year_create, inn_client, num_main_branch,type_invoice_full)
		  )
		  (
				SELECT
				  eo.month_create_invoice AS month_create, 
				  eo.year_create_invoice AS year_create, 
				  eo.inn_client AS inn_client, 
				  eo.num_main_branch AS num_main_branch,
				  eo.type_invoice_full AS type_invoice_full,
				  SUM(eo.pdvinvoice) AS pdv
				FROM erpn_out eo
				WHERE  eo.num_main_branch =:nb AND
				  (
				         (eo.month_create_invoice = :m
				         AND eo.year_create_invoice = :y
				         AND eo.type_invoice_full='ПНЕ')
				      OR
				        (eo.month_create_invoice = :m
				        AND eo.year_create_invoice = :y
				        AND eo.type_invoice_full='РКЕ'
				        AND eo.date_reg_invoice BETWEEN CAST(:dateBeginRKE AS DATE)  AND CAST(:dateEndRKE AS DATE))
		";
	}

	/**
	 * часть запроса на формирование запроса которая подключается если
	 * имеет смысл проверять РКЕ предыдущего месяца перед отчетным
	 *
	 *  - если :m = 5 и :y = 2016 то
	 *      - :m_minusOne = 4
	 *      - :y_minusOne = 2016
	 *      - :dataBegin_minusOne = '2016-05-16'
	 *      - :dataEnd_minusOne = '2016-05-31'
	 * @return string
	 */
	private function getSQL_withMonthMinusOne(){
		return /** @lang MySQL */
			" OR
		        (eo.month_create_invoice = :m_minusOne
		        AND eo.year_create_invoice = :y_minusOne
		        AND eo.type_invoice_full='РКЕ'
		        AND eo.date_reg_invoice BETWEEN CAST(:dataBegin_minusOne AS DATE)  AND CAST(:dataEnd_minusOne AS DATE))
		";
	}

	/**
	 * часть запроса на формирование запроса которая подключается если
	 * имеет смысл проверять РКЕ месяцев которые следуют за предыдущим месяцем перед отчетным
	 *
	 *  - если :m = 5 и :y = 2016 то
	 *      - :dataBeginCreate_invoice = "2015-12-01' всегда !!
	 *      - :dataEndCreate_invoice = '2016-03-31'
	 *      - :dataBegin_minusTwo = '2016-05-01'
	 *      - :dataEnd_minusTwo = '2016-05-31'
	 * @return string
	 */
	public function getSQL_withMonthMinusTwo(){
		return/** @lang MySQL */
			" OR 
		        (eo.date_create_invoice BETWEEN CAST(:dataBeginCreate_invoice AS DATE)  AND CAST(:dataEndCreate_invoice AS DATE)
		        AND eo.type_invoice_full='РКЕ'
		        AND eo.date_reg_invoice BETWEEN CAST(:dataBegin_minusTwo AS DATE)  AND CAST(:dataEnd_minusTwo AS DATE))
		";

	}


	/**
	 * конечная часть запроса который формирует временную таблицу
	 * @return string
	 */
	private function getSQLCreateTmpTableErpnOut_end(){
		return" )
        GROUP BY eo.month_create_invoice,
           eo.year_create_invoice,
           eo.inn_client,
           eo.num_main_branch,
           eo.type_invoice_full);
		";
	}

	/**
	 * Создает временную таблицу данных из РПН филиала а в периоде
	 * @return string temp_reestr_out_inn_group_numbranch
	 */
	private function getSQLCreateTmpTableReestrOut(){
		return /** @lang MySQL */
			"
		CREATE TEMPORARY TABLE IF NOT EXISTS temp_reestr_out_inn_group_numbranch (
		    month_create int(11)DEFAULT NULL ,
		    year_create year(4) DEFAULT NULL,
		    inn_client varchar(255) DEFAULT NULL ,
		    type_invoice_full varchar(255) DEFAULT NULL ,
		    num_main_branch varchar(255) DEFAULT NULL ,
		    pdv decimal(15, 2) DEFAULT NULL ,
		    id int NOT NULL AUTO_INCREMENT,
		    PRIMARY KEY (id),
		    INDEX main USING BTREE (month_create, year_create, inn_client, num_main_branch,type_invoice_full)
		  )
		  (SELECT
		    MONTH(`reestrbranch_out`.`date_create_invoice`) AS `month_create`,
		    YEAR(`reestrbranch_out`.`date_create_invoice`) AS `year_create`,
		    `reestrbranch_out`.`inn_client` AS `inn_client`,
		    `reestrbranch_out`. type_invoice_full AS type_invoice_full,
		    `reestrbranch_out`.`num_branch` AS `num_main_branch`,
		    SUM((`reestrbranch_out`.`pdv_20` + `reestrbranch_out`.`pdv_7`)) AS `pdv`
		  FROM `reestrbranch_out`
		  WHERE month = :m
		  AND year = :y
		    AND `reestrbranch_out`.num_branch = :nb
		  GROUP BY MONTH(`reestrbranch_out`.`date_create_invoice`),
		           YEAR(`reestrbranch_out`.`date_create_invoice`),
		           `reestrbranch_out`.`inn_client`,
		            `reestrbranch_out`. type_invoice_full,
		           `reestrbranch_out`.`num_branch`);
		";
	}

	/**
	 * временная таблица Out_InnerJoin в которую сохраняются результаты
	 * внутреннего соединение таблиц по
	 *  - temp_erpn_out_inn_group_numbranch
	 *  - emp_reestr_out_inn_group_numbranch
	 *
	 * при не нулевом сальдо ПДВ
	 * @return string Out_InnerJoin
	 */
	private function getSQLCreateTmpTableOut_InnerJoin()
	{
		return
			/** @lang MySQL */
			"
		CREATE TEMPORARY TABLE IF NOT EXISTS Out_InnerJoin(
		    month_create int(11)DEFAULT NULL ,
		    year_create year(4) DEFAULT NULL,
		    type_invoice_full varchar(255) DEFAULT NULL ,
		    Erpn_Inn varchar(255) DEFAULT NULL ,
		    Reestr_inn varchar(255) DEFAULT NULL ,
		    Erpn_pdv_PRAVO decimal(15, 2) DEFAULT NULL ,
		    Reestr_pdv_FACT decimal(15, 2) DEFAULT NULL ,
		    saldo_pdv decimal(15, 2) DEFAULT NULL ,
		    id int NOT NULL AUTO_INCREMENT,
		    PRIMARY KEY (id),
		    INDEX main USING BTREE (month_create, year_create, type_invoice_full, Erpn_Inn)
		  ) AS
		  (SELECT
		    temp_erpn_out_inn_group_numbranch.month_create,
		    temp_erpn_out_inn_group_numbranch.year_create,
		    temp_erpn_out_inn_group_numbranch.type_invoice_full,
		    temp_erpn_out_inn_group_numbranch.inn_client AS Erpn_Inn,
		    temp_reestr_out_inn_group_numbranch.inn_client AS Reestr_inn,
		    temp_erpn_out_inn_group_numbranch.pdv AS Erpn_pdv_PRAVO,
		    temp_reestr_out_inn_group_numbranch.pdv AS Reestr_pdv_FACT,
		    temp_erpn_out_inn_group_numbranch.pdv - temp_reestr_out_inn_group_numbranch.pdv AS saldo_pdv
		  FROM temp_erpn_out_inn_group_numbranch 
		    INNER JOIN temp_reestr_out_inn_group_numbranch
		      ON temp_erpn_out_inn_group_numbranch.month_create = temp_reestr_out_inn_group_numbranch.month_create
		      AND temp_erpn_out_inn_group_numbranch.year_create = temp_reestr_out_inn_group_numbranch.year_create
		      AND temp_erpn_out_inn_group_numbranch.num_main_branch = temp_reestr_out_inn_group_numbranch.num_main_branch
		      AND temp_erpn_out_inn_group_numbranch.inn_client = temp_reestr_out_inn_group_numbranch.inn_client
		      AND temp_erpn_out_inn_group_numbranch.type_invoice_full = temp_reestr_out_inn_group_numbranch.type_invoice_full
		  WHERE  (temp_erpn_out_inn_group_numbranch.pdv - temp_reestr_out_inn_group_numbranch.pdv)<>0);
		";

	}

	/**
	 * Начало запроса ддя формирования документов из ЕРПН
	 * которые сформировали отклонения при внутреннем соединении Out_InnerJoin
	 * @return string
	 */
	private function getSQLOut_InnerJoin_DocByErpn_begin(){
	return "
		SELECT 
			  eo.num_invoice, 
			  eo.date_create_invoice, 
			  eo.date_reg_invoice, 
			  eo.type_invoice_full, 
			  eo.inn_client,
			  eo.name_client,
			  eo.pdvinvoice,
			  eo.name_vendor 
		  FROM erpn_out eo
		  INNER JOIN Out_InnerJoin oij ON
		    eo.type_invoice_full = oij.type_invoice_full 
		    AND eo.month_create_invoice = oij.month_create 
		    AND eo.year_create_invoice = oij.year_create
		    AND eo.inn_client = oij.Erpn_Inn
		  WHERE  eo.num_main_branch =:nb AND
				  (
				         (eo.month_create_invoice = :m
				         AND eo.year_create_invoice = :y
				         AND eo.type_invoice_full='ПНЕ')
				      OR
				        (eo.month_create_invoice = :m
				        AND eo.year_create_invoice = :y
				        AND eo.type_invoice_full='РКЕ'
				        AND eo.date_reg_invoice BETWEEN CAST(:dateBeginRKE AS DATE)  AND CAST(:dateEndRKE AS DATE))
	";

	}

	/**
	 * Конец запроса ддя формирования документов из ЕРПН
	 * которые сформировали отклонения при внутреннем соединении Out_InnerJoin
	 * @return string
	 */
	private function getSQLOut_InnerJoin_DocByErpn_end(){
		return
		"
		);
		";
	}

	/**
	 * возвращает документы из РПН которые сформировали отклонения при
	 * внутреннем соединении  Out_InnerJoin
	 * @return string
	 */
	private function getSQLOut_InnerJoin_DocByReestr(){
		return
			/** @lang MySQL */
			"
		SELECT 
             ro.month, 
             ro.year, 
             ro.num_branch, 
             DATE_FORMAT(ro.date_create_invoice, '%d.%m.%Y') , 
             ro.num_invoice, 
             ro.type_invoice_full,
             ro.name_client,
             ro.inn_client,
             (ro.pdv_20 + ro.pdv_7) as PDV
		 FROM reestrbranch_out ro
		 INNER JOIN Out_InnerJoin oij ON
		    ro.type_invoice_full = oij.type_invoice_full 
		    AND ro.month_create_invoice = oij.month_create 
		    AND ro.year_create_invoice = oij.year_create
		    AND ro.inn_client = oij.Erpn_Inn
		 WHERE ro.num_branch = :nb
		        AND ro.month = :m
		        AND ro.year = :y;
		";
	}
	/**
	 * временная таблица Out_LeftJoin которыю сохраняются данные
	 *  левого соединения таблиц
	 *  - temp_erpn_out_inn_group_numbranch
	 *  - emp_reestr_out_inn_group_numbranch
	 *
	 * а точнее данные которые есть ТОЛЬКО в ЕРПН
	 * @return string
	 */
	private function getSQLCreateTmpTableOut_LeftJoin(){
		return
			/** @lang MySQL */
			"
		CREATE TEMPORARY TABLE IF NOT EXISTS Out_LeftJoin(
		    month_create int(11)DEFAULT NULL ,
		    year_create year(4) DEFAULT NULL,
		    type_invoice_full varchar(255) DEFAULT NULL ,
		    Erpn_Inn varchar(255) DEFAULT NULL ,
		    Erpn_pdv_PRAVO decimal(15, 2) DEFAULT NULL ,
		    id int NOT NULL AUTO_INCREMENT,
		    PRIMARY KEY (id),
		    INDEX main USING BTREE (month_create, year_create,  Erpn_Inn)
		  ) as
		  (SELECT
		  temp_erpn_out_inn_group_numbranch.month_create,
		  temp_erpn_out_inn_group_numbranch.year_create,
		  temp_erpn_out_inn_group_numbranch.type_invoice_full,
		  temp_erpn_out_inn_group_numbranch.inn_client AS Erpn_Inn,
		  temp_erpn_out_inn_group_numbranch.pdv AS Erpn_pdv_PRAVO
		    FROM temp_erpn_out_inn_group_numbranch
		  LEFT JOIN temp_reestr_out_inn_group_numbranch
		    ON temp_erpn_out_inn_group_numbranch.month_create = temp_reestr_out_inn_group_numbranch.month_create
		    AND temp_erpn_out_inn_group_numbranch.year_create = temp_reestr_out_inn_group_numbranch.year_create
		    AND temp_erpn_out_inn_group_numbranch.num_main_branch = temp_reestr_out_inn_group_numbranch.num_main_branch
		    AND temp_erpn_out_inn_group_numbranch.inn_client = temp_reestr_out_inn_group_numbranch.inn_client
		    AND temp_erpn_out_inn_group_numbranch.type_invoice_full = temp_reestr_out_inn_group_numbranch.type_invoice_full
		 WHERE temp_reestr_out_inn_group_numbranch.month_create IS NULL 
		  AND temp_reestr_out_inn_group_numbranch.year_create IS NULL
		  AND temp_reestr_out_inn_group_numbranch.inn_client IS NULL
		  AND temp_reestr_out_inn_group_numbranch.type_invoice_full IS NULL
		  AND temp_reestr_out_inn_group_numbranch.num_main_branch IS NULL
		 );
		";

	}

	/**
	 * Начало запроса ддя формирования документов из ЕРПН
	 * которые сформировали отклонения при  соединении Out_LeftJoin
	 * @return string
	 */
	private function getSQLOut_LeftJoin_DocByErpn_begin(){
		return
		"
		SELECT 
		  eo.num_invoice, 
		  date_format(eo.date_create_invoice,'%d.%m.%Y'),
		  date_format(eo.date_reg_invoice,'%d.%m.%Y'), 
		  eo.type_invoice_full, 
		  eo.inn_client, 
		  eo.name_client, 
		  eo.pdvinvoice,
		  eo.name_vendor FROM erpn_out eo
		  INNER JOIN Out_LeftJoin olj ON
		  olj.month_create = eo.month_create_invoice
		  AND olj.year_create = eo.year_create_invoice
		  AND olj.Erpn_Inn = eo.inn_client
		  AND eo.type_invoice_full = olj.type_invoice_full
		WHERE  eo.num_main_branch =:nb AND
				  (
				         (eo.month_create_invoice = :m
				         AND eo.year_create_invoice = :y
				         AND eo.type_invoice_full='ПНЕ')
				      OR
				        (eo.month_create_invoice = :m
				        AND eo.year_create_invoice = :y
				        AND eo.type_invoice_full='РКЕ'
				        AND eo.date_reg_invoice BETWEEN CAST(:dateBeginRKE AS DATE)  AND CAST(:dateEndRKE AS DATE))
		";
	}

	/**
	 * Конец запроса ддя формирования документов из ЕРПН
	 * которые сформировали отклонения при  соединении Out_LeftJoin
	 * @return string
	 */
	private function getSQLOut_LeftJoin_DocByErpn_end(){
		return
			"
		);
		";
	}

	/**
	 * временная таблица Out_RightJoin которыю сохраняются данные
	 *  левого соединения таблиц
	 *  - emp_reestr_out_inn_group_numbranch
	 *  - temp_erpn_out_inn_group_numbranch
	 *
	 * а точнее данные которые есть ТОЛЬКО в РПН
	 * @return string
	 */
	private function getSQLCreateTmpTableOut_RightJoin(){
		return
			/** @lang MySQL */
			"
		CREATE TEMPORARY TABLE IF NOT EXISTS Out_RightJoin(
		    month_create int(11)DEFAULT NULL ,
		    year_create year(4) DEFAULT NULL,
		    type_invoice_full varchar(255) DEFAULT NULL ,
		    Reestr_Inn varchar(255) DEFAULT NULL ,
		    Reestr_pdv_Fact decimal(15, 2) DEFAULT NULL ,
		    id int NOT NULL AUTO_INCREMENT,
		    PRIMARY KEY (id),
		    INDEX main USING BTREE (month_create, year_create,Reestr_Inn)
		  ) as
		  ( SELECT
		    temp_reestr_out_inn_group_numbranch.month_create AS month_create,
		    temp_reestr_out_inn_group_numbranch.year_create AS year_create,
		    temp_reestr_out_inn_group_numbranch.type_invoice_full AS type_invoice_full,
		    temp_reestr_out_inn_group_numbranch.inn_client AS Reestr_Inn,
		    COALESCE(temp_reestr_out_inn_group_numbranch.pdv, 0) AS Reestr_pdv_Fact
		   FROM temp_reestr_out_inn_group_numbranch
		    LEFT JOIN temp_erpn_out_inn_group_numbranch
		      ON temp_reestr_out_inn_group_numbranch.month_create = temp_erpn_out_inn_group_numbranch.month_create
		      AND temp_reestr_out_inn_group_numbranch.year_create = temp_erpn_out_inn_group_numbranch.year_create
		      AND temp_reestr_out_inn_group_numbranch.num_main_branch = temp_erpn_out_inn_group_numbranch.num_main_branch
		      AND temp_reestr_out_inn_group_numbranch.inn_client = temp_erpn_out_inn_group_numbranch.inn_client
		      AND temp_erpn_out_inn_group_numbranch.type_invoice_full = temp_reestr_out_inn_group_numbranch.type_invoice_full
		  WHERE temp_erpn_out_inn_group_numbranch.month_create IS NULL
		  AND temp_erpn_out_inn_group_numbranch.year_create IS NULL
		  AND temp_erpn_out_inn_group_numbranch.inn_client IS NULL
		  AND temp_erpn_out_inn_group_numbranch.type_invoice_full IS NULL
		  AND temp_erpn_out_inn_group_numbranch.num_main_branch IS NULL);
		";
	}

	/**
	 * возвращает документы из РПН которые сформировали отклонения при
	 * внутреннем соединении  Out_RightJoin
	 * @return string
	 */
	private function getSQLOut_RightJoin_DocByReestr(){
		return
			/** @lang MySQL */
			"
			 SELECT 
				 ro.month, 
	             ro.year, 
	             ro.num_branch, 
	             DATE_FORMAT(ro.date_create_invoice, '%d.%m.%Y') , 
	             ro.num_invoice, 
	             ro.type_invoice_full,
	             ro.name_client,
	             ro.inn_client,
	             (ro.pdv_20 + ro.pdv_7) as PDV
			 FROM reestrbranch_out ro
			  INNER JOIN Out_RightJoin oij ON
			    ro.type_invoice_full = oij.type_invoice_full 
			    AND ro.month_create_invoice = oij.month_create 
			    AND ro.year_create_invoice = oij.year_create
			    AND ro.inn_client=oij.Reestr_Inn
			  WHERE ro.num_branch = :nb
			        AND ro.month = :m
			        AND ro.year = :y;
		";

	}

	/**
	 * Данные анализа обязательств если документы с ЕРПН равны документам с Реестра филиала
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranch - отсюда вызывается функция
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::AnalizInnOutInnerJoinOneBranch - хранимая процедура для генерации данных
	 */
	public function getReestrEqualErpn(int $month, int $year, string $numBranch)
	{
		$SQL= /** @lang MySQL */
			"select
			    month_create,
			    year_create,
			    type_invoice_full,
			    Erpn_Inn,
			    Reestr_inn,
			    Erpn_pdv_PRAVO,
			    Reestr_pdv_FACT,
			    saldo_pdv 
			FROM Out_InnerJoin;";
		$smtp=$this->em->getConnection()->prepare($SQL);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;

	}


	/**
	 * Получение документов с ЕРПН по расхождению сформированному в getReestrEqualErpn
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::getDocErpnBy_AnalizInnOutInnerJoinBranch - хранимая процедура для генерации данных
	 */

	public function getReestrEqualErpn_DocErpn(int $month, int $year,string $numBranch)
	{
		$smtp=$this->em->getConnection()->prepare($this->SQLOut_InnerJoin_DocByErpn);

		$smtp->bindValue("m",$this->monthAnaliz);
		$smtp->bindValue("y",$this->yearAnaliz);
		$smtp->bindValue("nb",$this->numBranch);
		$smtp->bindValue("dateBeginRKE",$this->workDate->getStartPeriodAnalizRke());
		$smtp->bindValue("dateEndRKE",$this->workDate->getЕndPeriodAnalizRke());
		if(!is_null($this->workDate->getMonthMinisOne())and (!is_null($this->workDate->getYearMinisOneMonth()))){
			$smtp->bindValue("m_minusOne",$this->workDate->getMonthMinisOne());
			$smtp->bindValue("y_minusOne",$this->workDate->getYearMinisOneMonth());
			$smtp->bindValue("dataBegin_minusOne",$this->workDate->getStartPeriodAnalizRkeMinusOne());
			$smtp->bindValue("dataEnd_minusOne",$this->workDate->getЕndPeriodAnalizRkeMinusOne());
		}
		if(!is_null($this->workDate->getMonthMinisTwo())and (!is_null($this->workDate->getYearMinisTwoMonth()))){
			$smtp->bindValue("dataBeginCreate_invoice",$this->workDate->getStartPeriodAnalizRkeMinusTwo());
			$smtp->bindValue("dataEndCreate_invoice",$this->workDate->getЕndPeriodAnalizRkeMinusTwo());
			$smtp->bindValue("dataBegin_minusTwo",$this->workDate->getStartPeriodAnalizRke());
			$smtp->bindValue("dataEnd_minusTwo",$this->workDate->getЕndPeriodAnalizRkeMinusOne());
		}

		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;

	}

	/**
	 * Получение документов с Реестров по расхождению сформированному в getReestrEqualErpn
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::getDocReestrBy_AnalizInnOutInnerJoinBranch - хранимая процедура для генерации данных
	 */
	public function getReestrEqualErpn_DocReestr(int $month, int $year,string $numBranch)
	{
		$smtp=$this->em->getConnection()->prepare($this->getSQLOut_InnerJoin_DocByReestr());

		$smtp->bindValue("m",$this->monthAnaliz);
		$smtp->bindValue("y",$this->yearAnaliz);
		$smtp->bindValue("nb",$this->numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;
	}

	/**
	 * Данные анализа обязательств только документы которые есть в Реестрах филиала но нет в ЕРПН
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranch - отсюда вызывается функция
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::AnalizInnOutRightJoinOneBranch - хранимая процедура для генерации данных
	 */
	public function getReestrNoEqualErpn(int $month, int $year, string $numBranch)
	{
		$SQL=$SQL= /** @lang MySQL */
			"select
				month_create,
			    year_create,
			    type_invoice_full,
			    Reestr_Inn,
			    Reestr_pdv_Fact
			FROM Out_RightJoin;";
		 $smtp=$this->em->getConnection()->prepare($SQL);
    	 $smtp->execute();
		 $arrayResult=$smtp->fetchAll();
		return $arrayResult;

	}

	/**
	 * Получение документов с Реестров по расхождению сформированому getReestrNoEqualErpn
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::getDocReestrBy_AnalizInnOutRightJoinBranch - хранимая процедура для генерации данных
	 */
	public function getReestrNoEqualErpn_DocReestr(int $month, int $year, string $numBranch)
	{
		$smtp=$this->em->getConnection()->prepare($this->getSQLOut_RightJoin_DocByReestr());
		$smtp->bindValue("m",$this->monthAnaliz);
		$smtp->bindValue("y",$this->yearAnaliz);
		$smtp->bindValue("nb",$this->numBranch);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;

	}

	/**
	 * Данные анализа обязательств только документы которые есть в ЕРПН но нет в Реестрах филилала
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranch - отсюда вызывается функция
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::AnalizInnOutLeftJoinOneBranch - хранимая процедура для генерации данных
	 */
	public function getErpnNoEqualReestr(int $month, int $year, string $numBranch)
	{
		$SQL= /** @lang MySQL */
			"select
	            month_create ,
			    year_create,
			    type_invoice_full,
			    Erpn_Inn,
			    Erpn_pdv_PRAVO
			FROM Out_LeftJoin;";
		$smtp=$this->em->getConnection()->prepare($SQL);
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;


	}

	/**
	 * Получение документов с ЕРПН по расхождению сформированому в getErpnNoEqualReestr
	 * @param int $month
	 * @param int $year
	 * @param string $numBranch
	 * @return array
	 * @uses writeAnalizOutByInn::writeAnalizPDVOutInnByOneBranchWithDoc - отсюда вызывается функция
	 * @uses store_procedure::getDocErpnBy_AnalizInnOutLeftJoinBranch - хранимая процедура для генерации данных
	 */
	public function getErpnNoEqualReestr_DocErpn(int $month, int $year, string $numBranch)
	{
		$smtp=$this->em->getConnection()->prepare($this->SQLOut_LeftJoin_DocByErpn);
		// заполняем параметры запроса
		$smtp->bindValue("m",$this->monthAnaliz);
		$smtp->bindValue("y",$this->yearAnaliz);
		$smtp->bindValue("nb",$this->numBranch);
		$smtp->bindValue("dateBeginRKE",$this->workDate->getStartPeriodAnalizRke());
		$smtp->bindValue("dateEndRKE",$this->workDate->getЕndPeriodAnalizRke());
		if(!is_null($this->workDate->getMonthMinisOne())and (!is_null($this->workDate->getYearMinisOneMonth()))){
			$smtp->bindValue("m_minusOne",$this->workDate->getMonthMinisOne());
			$smtp->bindValue("y_minusOne",$this->workDate->getYearMinisOneMonth());
			$smtp->bindValue("dataBegin_minusOne",$this->workDate->getStartPeriodAnalizRkeMinusOne());
			$smtp->bindValue("dataEnd_minusOne",$this->workDate->getЕndPeriodAnalizRkeMinusOne());
		}
		if(!is_null($this->workDate->getMonthMinisTwo())and (!is_null($this->workDate->getYearMinisTwoMonth()))){
			$smtp->bindValue("dataBeginCreate_invoice",$this->workDate->getStartPeriodAnalizRkeMinusTwo());
			$smtp->bindValue("dataEndCreate_invoice",$this->workDate->getЕndPeriodAnalizRkeMinusTwo());
			$smtp->bindValue("dataBegin_minusTwo",$this->workDate->getStartPeriodAnalizRke());
			$smtp->bindValue("dataEnd_minusTwo",$this->workDate->getЕndPeriodAnalizRkeMinusOne());
		}
		$smtp->execute();
		$arrayResult=$smtp->fetchAll();
		return $arrayResult;

	}

}
