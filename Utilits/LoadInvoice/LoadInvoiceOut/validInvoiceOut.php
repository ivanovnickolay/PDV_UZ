<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 02.08.2016
 * Time: 23:45
 */

namespace AnalizPdvBundle\Utilits\LoadInvoice\LoadInvoiceOut;


use AnalizPdvBundle\Entity\ErpnOut;
use Doctrine\ORM\EntityManager;
use LoadFileBundle\Entity\Erpn_out;

class validInvoiceOut
{
    private $entityManager;
    private $Keys;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager=$entityManager;
       // $this->Keys=array();
    }

    /**
     * Проверяем сущность на правильность заполения полей и уникальность в базе
     * @param Erpn_out $erpn_out
     * @return bool
     */
    public function valid(Erpn_out $erpn_out)
    {
     //todo написать алгоритм проверки
        $res=false;
       // если значение номера не пустое
	    if ((is_null($erpn_out->getNumInvoice())))
        {
            return false;
        }
            //  если запись есть в текущем массиве
           /* if ((in_array($erpn_out->getKeyField(),$this->Keys)))
            {
                return false;
            }*/
            // проверим сущность на уникальность
            if (($this->isUniqInvoice($erpn_out)))
            {
                // сущность уникальна
                        // сохраняем ключеове поле в временном массиве
                        //         $this->Keys[]=$erpn_out->getKeyField();
                            // возвращаем истинно
                            $res=true;
            } else
            {
                $res=false;
            }
    return $res;
    }

    /**
     * Проверяем уникальность поле сущности
     * @param Erpn_out $erpn_out
     * @return bool
     */
    public function isUniqInvoice(ErpnOut $data)
    {
        // если в массаиве нет похожих записей то
        //if ((!in_array($data->getKeyField(),$this->Keys))) {
            //todo написать запрос на получение количества сущностей по условию
            // проверим есть ли походжие в безе данных
            if (($this->entityManager->getRepository("AnalizPdvBundle:ErpnOut")->ValidInvoice($data)))
            {
                    return true;
            } else
                {
                    return false;
                }
        //}

        return true;
    }

	/**
	 * Проверка на пустую строку
	 * если номер и дата накладной пустые то строка пустая
	 * возвращаем истинно
	 * @param Erpn_out $Invoice
	 * @return bool
	 */
	public function is_emptyRow(Erpn_out $Invoice)
    {
	    if (empty($Invoice->getNumInvoice() and empty($Invoice->getDateCreateInvoice())))
	    {
	        return true;
	    } else
	    {
		    return false;
	    }
    }
}