<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30.03.2017
 * Time: 12:19
 */

namespace App\Model\getDataFromSQL\prepareSQL;


use AnalizPdvBundle\Model\workDateForSQL;


/**
 * подготовка текстов сложных запросов для
 * получения данных для заполннения анализа обязательств
 * реестра и ЕРПН в разрезе ИНН по одному филиалу
 *
 * Class prepareSQDataOutINNByOne
 * @package AnalizPdvBundle\Model\getDataFromSQL\prepareSQL
 */
class prepareSQDataOutINNByAllUZ
{
	/**
	 * @var workDateForSQL
	 */
	private $workDataForSQL;

	/**
	 * массив параметров запроса которые зависят от периода формирования
	 * тип массива ключ => значение где
	 *  - ключ значение параметра в запросе
	 *  - значение значение параметра
	 *  @var array
	 */
	private $arrayForBindValueWithPeriod;


	/**
	 * * массив параметров запроса которые НЕ зависят от периода формирования
	 * тип массива ключ => значение где
	 *  - ключ значение параметра в запросе
	 *  - значение значение параметра
	 * @var array
	 */
	private $arrayForBindValueWithoutPeriod;

	/**
	 * prepareSQDataOutINNByOne constructor.
	 * @param workDateForSQL $workDateForSQL
	 */
	public function __construct(workDateForSQL $workDateForSQL)
	{
		$this->workDataForSQL=$workDateForSQL;
		$this->getArrayForBindValueWithoutPeriod();
		$this->getArrayForBindValueWithPeriod();
	}

	/**
	 * Формирование запроса на создание временной таблицы по данным
	 *  ErpnOut
	 * @return string $SQLTmpTableErpnOut сформированный запрос
	 */
	public function getPrepareSQLCreateTmpTableErpnOut(){
		// подключаем начало запросов
		// временная таблица по данным ЕРПН
		$SQLTmpTableErpnOut=$this->getSQLCreateTmpTableErpnOut_begin();
		/*
		 *	если актуально делать запрос данных из предвдущего периода то подключаем
		 *  к запросам часть которая выбирает эти данные
		**/
		if(!is_null($this->workDataForSQL->getMonthMinisOne())and (!is_null($this->workDataForSQL->getYearMinisOneMonth()))){
			$SQLTmpTableErpnOut.=$this->getSQL_withMonthMinusOne();
		}
		/* если актуально делать запрос данных из других прошедших периодов
			кроме предудущего периода то подключаем
		    к запросам часть которая выбирает эти данные
		**/
		if(!is_null($this->workDataForSQL->getMonthMinisTwo())and (!is_null($this->workDataForSQL->getYearMinisTwoMonth()))){
			$SQLTmpTableErpnOut.=$this->getSQL_withMonthMinusTwo();
		}
		// подключаем конечные части запросов
		$SQLTmpTableErpnOut.=$this->getSQLCreateTmpTableErpnOut_end();
		return $SQLTmpTableErpnOut;
	}

	/**
	 * возвращаем параметры для выполнения запроса
	 * @return array
	 */
	public function getPrepareBindValueCreateTmpTableErpnOut(){
		return $this->arrayForBindValueWithPeriod;
	}

	/**
	 * Формирование запроса на создание временной таблицы по данным
	 *  ReestrOut
	 * @return string  сформированный запрос
	 */
	public function getPrepareSQLCreateTmpTableReestrOut(){
		return $this->getSQLCreateTmpTableReestrOut();
	}

	/**
	 * возвращаем параметры для выполнения запроса
	 * @return array
	 */
	public function getPrepareBindValueCreateTmpTableReestrOut(){
		return $this->arrayForBindValueWithoutPeriod;
	}

	/**
	 * Формирование запроса на создание временной таблицы по данным
	 *  внутренненого соединения  ErpnOut и  ReestrOut
	 * @return string  сформированный запрос
	 */
	public function getPrepareSQLCreateTmpTableOut_InnerJoin(){
		return $this->getSQLCreateTmpTableOut_InnerJoin();
	}

	/**
	 * возвращаем параметры для выполнения запроса
	 * @return array
	 */
	public function getPrepareBindValueCreateTmpTableOut_InnerJoint(){
		return null;
	}

	/**
	 * @return string
	 */
	public function getPrepareSQLOut_InnerJoin(){
		return $this->getSQLOut_InnerJoin();
	}

	/**
	 * возвращаем параметры для выполнения запроса
	 * @return array
	 */
	public function getPrepareBindValueOut_InnerJoin(){
		return null;
	}

	/**
	 * Формирование запроса на получение документов из ЕРПН которые
	 * сформировали отклонения при внутреннем соединении Out_InnerJoin
	 * @return string сформированный запрос
	 */
	public function getPrepareSQLOut_InnerJoin_DocByErpn(){
		// выборка документов из ЕРПН внутренне соединение Out_InnerJoin
		$SQLOut_InnerJoin_DocByErpn=$this->getSQLOut_InnerJoin_DocByErpn_begin();
		/*
		 *	если актуально делать запрос данных из предвдущего периода то подключаем
		 *  к запросам часть которая выбирает эти данные
		**/
		if(!is_null($this->workDataForSQL->getMonthMinisOne())and (!is_null($this->workDataForSQL->getYearMinisOneMonth()))){
			$SQLOut_InnerJoin_DocByErpn.=$this->getSQL_withMonthMinusOne();
		}
		/* если актуально делать запрос данных из других прошедших периодов
			кроме предудущего периода то подключаем
		    к запросам часть которая выбирает эти данные
		**/
		if(!is_null($this->workDataForSQL->getMonthMinisTwo())and (!is_null($this->workDataForSQL->getYearMinisTwoMonth()))){
			$SQLOut_InnerJoin_DocByErpn.=$this->getSQL_withMonthMinusTwo();
		}
		// подключаем конечные части запросов
		$SQLOut_InnerJoin_DocByErpn.=$this->getSQLOut_InnerJoin_DocByErpn_end();
		return $SQLOut_InnerJoin_DocByErpn;
	}

	/**
	* возвращаем параметры для выполнения запроса
	* @return array
	*/
	public function getPrepareBindValueOut_InnerJoin_DocByErpn(){
		return $this->arrayForBindValueWithPeriod;
	}

	/**
	 * Формирование запроса на получение документов из РПН которые
	 * сформировали отклонения при внутреннем соединении Out_InnerJoin
	 * @return string сформированный запрос
	 */
	public function getPrepareSQLOut_InnerJoin_DocByReestr(){
		return $this->getSQLOut_InnerJoin_DocByReestr();
	}

	/**
	 * возвращаем параметры для выполнения запроса
	 * @return array
	 */
	public function getPrepareBindValueOut_InnerJoin_DocByReestr(){
		return $this->arrayForBindValueWithoutPeriod;
	}

	/**
	 *  Формирование временной таблицы Out_LeftJoin которыю сохраняются данные
	 *  левого соединения таблиц
	 *  - temp_erpn_out_inn_group_numbranch
	 *  - emp_reestr_out_inn_group_numbranch
	 *
	 * а точнее данные которые есть ТОЛЬКО в ЕРПН
	 * @return string
	 */
	public function getPrepareSQLCreateTmpTableOut_LeftJoin(){
		return $this->getSQLCreateTmpTableOut_LeftJoin();
	}

		/**
		* возвращаем параметры для выполнения запроса
		* @return array
		*/
	public function getPrepareBindValueCreateTmpTableOut_LeftJoin(){
		return null;
	}

	/**
	 * @return string
	 */
	public function getPrepareSQLOut_LeftJoin(){
		return $this->getSQLOut_LeftJoin();
	}

	/**
	 * возвращаем параметры для выполнения запроса
	 * @return array
	 */
	public function getPrepareBindValueOut_LeftJoin(){
		return null;
	}

	/**
	 *
	 * @return string
	 */
	public function getPrepareSQLOut_LeftJoin_DocByErpn(){
		// подключаем начало запросов
		// выборка документов из ЕРПН  соединение Out_LeftJoin
		$SQLOut_LeftJoin_DocByErpn=$this->getSQLOut_LeftJoin_DocByErpn_begin();
		/*
		 *	если актуально делать запрос данных из предвдущего периода то подключаем
		 *  к запросам часть которая выбирает эти данные
		**/
		if(!is_null($this->workDataForSQL->getMonthMinisOne())and (!is_null($this->workDataForSQL->getYearMinisOneMonth()))){
			$SQLOut_LeftJoin_DocByErpn.=$this->getSQL_withMonthMinusOne();
		}
		/* если актуально делать запрос данных из других прошедших периодов
			кроме предудущего периода то подключаем
		    к запросам часть которая выбирает эти данные
		**/
		if(!is_null($this->workDataForSQL->getMonthMinisTwo())and (!is_null($this->workDataForSQL->getYearMinisTwoMonth()))){
			$SQLOut_LeftJoin_DocByErpn.=$this->getSQL_withMonthMinusTwo();
		}
		// подключаем конечные части запросов
		$SQLOut_LeftJoin_DocByErpn.=$this->getSQLOut_LeftJoin_DocByErpn_end();
		return $SQLOut_LeftJoin_DocByErpn;
	}

	/**
	 * возвращаем параметры для выполнения запроса
	 * @return array
	 */
	public function getPrepareBindValueOut_LeftJoin_DocByErpn(){
		return $this->arrayForBindValueWithPeriod;
	}

	/**
	 * Формирование временной таблицы Out_RightJoin которыю сохраняются данные
	 *  левого соединения таблиц
	 *  - temp_reestr_out_inn_group_numbranch
	 *  - temp_erpn_out_inn_group_numbranch
	 *
	 * а точнее данные которые есть ТОЛЬКО в РПН
	 * @return string
	 */
	public function getPrepareSQLCreateTmpTableOut_RightJoin(){
		return $this->getSQLCreateTmpTableOut_RightJoin();
	}

	/**
	 * возвращаем параметры для выполнения запроса
	 * @return array
	 */
	public function getPrepareBindValueCreateTmpTableOut_RightJoin(){
		return null;
	}

	/**
	 * @return string
	 */
	public function getPrepareSQLOut_RightJoin(){
		return $this->getSQLOut_RightJoin();
	}

	/**
	 * возвращаем параметры для выполнения запроса
	 * @return array
	 */
	public function getPrepareBindValueOut_RightJoin(){
		return null;
	}

	/**
	 * Формирование запроса на получение документов из РПН которые сформировали отклонения при
	 * внутреннем соединении  Out_RightJoin
	 * @return string
	 */
	public function getPrepareSQLOut_RightJoin_DocByReestr(){
		return $this->getSQLOut_RightJoin_DocByReestr();
	}

	/**
	 * возвращаем параметры для выполнения запроса
	 * @return array
	 */
	public function getPrepareBindValueOut_RightJoin_DocByReestr(){
		return $this->arrayForBindValueWithoutPeriod;
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
		    type_invoice_full varchar(255) DEFAULT NULL,
		    pdv decimal(15, 2) DEFAULT NULL ,
		    id int NOT NULL AUTO_INCREMENT,
		    PRIMARY KEY (id),
		    INDEX main USING BTREE (month_create, year_create, inn_client, type_invoice_full)
		  )
		  (
				SELECT
				  eo.month_create_invoice AS month_create, 
				  eo.year_create_invoice AS year_create, 
				  eo.inn_client AS inn_client, 
				  eo.type_invoice_full AS type_invoice_full,
				  SUM(eo.pdvinvoice) AS pdv
				FROM erpn_out eo
				WHERE  
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
	private function getSQL_withMonthMinusTwo(){
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
		    pdv decimal(15, 2) DEFAULT NULL ,
		    id int NOT NULL AUTO_INCREMENT,
		    PRIMARY KEY (id),
		    INDEX main USING BTREE (month_create, year_create, inn_client,type_invoice_full)
		  )
		 (SELECT
		    MONTH(`reestrbranch_out`.`date_create_invoice`) AS `month_create`,
		    YEAR(`reestrbranch_out`.`date_create_invoice`) AS `year_create`,
		    `reestrbranch_out`.`inn_client` AS `inn_client`,
		    `reestrbranch_out`. type_invoice_full AS type_invoice_full,
		    SUM((`reestrbranch_out`.`pdv_20` + `reestrbranch_out`.`pdv_7`)) AS `pdv`
		 FROM `reestrbranch_out`
		 WHERE month = :m  AND year = :y
		 GROUP BY MONTH(`reestrbranch_out`.`date_create_invoice`),
		          YEAR(`reestrbranch_out`.`date_create_invoice`),
		          `reestrbranch_out`.`inn_client`,
		          `reestrbranch_out`. type_invoice_full);
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
		      ON 
		    temp_erpn_out_inn_group_numbranch.month_create = temp_reestr_out_inn_group_numbranch.month_create
		    AND temp_erpn_out_inn_group_numbranch.year_create = temp_reestr_out_inn_group_numbranch.year_create
		    AND temp_erpn_out_inn_group_numbranch.inn_client = temp_reestr_out_inn_group_numbranch.inn_client
		    AND temp_erpn_out_inn_group_numbranch.type_invoice_full = temp_reestr_out_inn_group_numbranch.type_invoice_full
		    WHERE  (temp_erpn_out_inn_group_numbranch.pdv - temp_reestr_out_inn_group_numbranch.pdv)<>0);
		";

	}

	/**
	 * @return string
	 */
	private function getSQLOut_InnerJoin(){
		return
			"
			select
			    month_create,
			    year_create,
			    type_invoice_full,
			    Erpn_Inn,
			    Reestr_inn,
			    Erpn_pdv_PRAVO,
			    Reestr_pdv_FACT,
			    saldo_pdv 
			FROM Out_InnerJoin;
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
			  DATE_FORMAT(eo.date_create_invoice, '%d.%m.%Y') as date_create_invoice  , 
			  DATE_FORMAT(eo.date_reg_invoice, '%d.%m.%Y') as date_reg_invoice ,
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
		  WHERE  
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
             DATE_FORMAT(ro.date_create_invoice, '%d.%m.%Y') as date_create_invoice, 
             ro.num_invoice, 
             ro.type_invoice_full,
             ro.name_client,
             ro.inn_client,
             (ro.pdv_20 + ro.pdv_7) as pdvinvoice
		 FROM reestrbranch_out ro
		 INNER JOIN Out_InnerJoin oij ON
		    ro.type_invoice_full = oij.type_invoice_full 
		    AND ro.month_create_invoice = oij.month_create 
		    AND ro.year_create_invoice = oij.year_create
		    AND ro.inn_client = oij.Erpn_Inn
		 WHERE  ro.month = :m
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
		    AND temp_erpn_out_inn_group_numbranch.inn_client = temp_reestr_out_inn_group_numbranch.inn_client
		    AND temp_erpn_out_inn_group_numbranch.type_invoice_full = temp_reestr_out_inn_group_numbranch.type_invoice_full
		 WHERE temp_reestr_out_inn_group_numbranch.month_create IS NULL 
		  AND temp_reestr_out_inn_group_numbranch.year_create IS NULL
		  AND temp_reestr_out_inn_group_numbranch.inn_client IS NULL
		  AND temp_reestr_out_inn_group_numbranch.type_invoice_full IS NULL
		 );
		";

	}

	/**
	 * @return string
	 */
	private function getSQLOut_LeftJoin(){
		return
			"select
	            month_create ,
			    year_create,
			    type_invoice_full,
			    Erpn_Inn,
			    Erpn_pdv_PRAVO
			FROM Out_LeftJoin;
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
		  date_format(eo.date_create_invoice,'%d.%m.%Y') as date_create_invoice,
		  date_format(eo.date_reg_invoice,'%d.%m.%Y') as date_reg_invoice, 
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
		WHERE  	  (
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
			      AND temp_reestr_out_inn_group_numbranch.inn_client = temp_erpn_out_inn_group_numbranch.inn_client
			      AND temp_erpn_out_inn_group_numbranch.type_invoice_full = temp_reestr_out_inn_group_numbranch.type_invoice_full
			  WHERE temp_erpn_out_inn_group_numbranch.month_create IS NULL
			  AND temp_erpn_out_inn_group_numbranch.year_create IS NULL
			  AND temp_erpn_out_inn_group_numbranch.inn_client IS NULL
			  AND temp_erpn_out_inn_group_numbranch.type_invoice_full IS NULL
	 	  );
		";
	}

	/**
	 * @return string
	 */
	private function getSQLOut_RightJoin(){
		return
			"select
				month_create,
			    year_create,
			    type_invoice_full,
			    Reestr_Inn,
			    Reestr_pdv_Fact
			FROM Out_RightJoin;
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
			  WHERE ro.month = :m
			        AND ro.year = :y;
		";

	}

	/**
	 *  формирирование массива arrayForBindValueWithPeriod с данными параметров запроса
	 *  которые ЗАВИСЯТ от периода
	 *
	 */
	private function getArrayForBindValueWithPeriod(){
		$this->arrayForBindValueWithPeriod["m"]=$this->workDataForSQL->getMonthAnaliz();
		$this->arrayForBindValueWithPeriod["y"]=$this->workDataForSQL->getYearAnaliz();

		$this->arrayForBindValueWithPeriod["dateBeginRKE"]=$this->workDataForSQL->getStartPeriodAnalizRke();
		$this->arrayForBindValueWithPeriod["dateEndRKE"]=$this->workDataForSQL->getЕndPeriodAnalizRke();

		if(!is_null($this->workDataForSQL->getMonthMinisOne())and (!is_null($this->workDataForSQL->getYearMinisOneMonth()))){
			$this->arrayForBindValueWithPeriod["m_minusOne"]=$this->workDataForSQL->getMonthMinisOne();
			$this->arrayForBindValueWithPeriod["y_minusOne"]=$this->workDataForSQL->getYearMinisOneMonth();
			$this->arrayForBindValueWithPeriod["dataBegin_minusOne"]=$this->workDataForSQL->getStartPeriodAnalizRkeMinusOne();
			$this->arrayForBindValueWithPeriod["dataEnd_minusOne"]=$this->workDataForSQL->getЕndPeriodAnalizRkeMinusOne();
		}

		if(!is_null($this->workDataForSQL->getMonthMinisTwo())and (!is_null($this->workDataForSQL->getYearMinisTwoMonth()))){
			$this->arrayForBindValueWithPeriod["dataBeginCreate_invoice"]=$this->workDataForSQL->getStartPeriodAnalizRkeMinusTwo();
			$this->arrayForBindValueWithPeriod["dataEndCreate_invoice"]=$this->workDataForSQL->getЕndPeriodAnalizRkeMinusTwo();
			$this->arrayForBindValueWithPeriod["dataBegin_minusTwo"]=$this->workDataForSQL->getStartPeriodAnalizRke();
			$this->arrayForBindValueWithPeriod["dataEnd_minusTwo"]=$this->workDataForSQL->getЕndPeriodAnalizRkeMinusOne();
		}
	}

	/**
	 * формирирование массива arrayForBindValueWithoutPeriod с данными параметров запроса
	 *  которые НЕ зависят от периода
	 */
	private function getArrayForBindValueWithoutPeriod(){
		$this->arrayForBindValueWithoutPeriod["m"]=$this->workDataForSQL->getMonthAnaliz();
		$this->arrayForBindValueWithoutPeriod["y"]=$this->workDataForSQL->getYearAnaliz();


	}
}