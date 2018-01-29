<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * ReestrbranchIn
 */
class ReestrbranchIn
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $month = '0';

    /**
     * @var integer
     */
    private $year = '0';

    /**
     * @var string
     */
    private $numBranch;

    /**
     * @var \DateTime
     */
    private $dateGetInvoice;

    /**
     * @var \DateTime
     */
    private $dateCreateInvoice;

    /**
     * @var string
     */
    private $numInvoice;

    /**
     * @var string
     */
    private $typeInvoiceFull;

    /**
     * @var string
     */
    private $nameClient;

    /**
     * @var string
     */
    private $innClient;

    /**
     * @var float
     */
    private $zagSumm;

    /**
     * @var float
     */
    private $baza20;

    /**
     * @var float
     */
    private $pdv20;

    /**
     * @var float
     */
    private $baza7;

    /**
     * @var float
     */
    private $pdv7;

    /**
     * @var float
     */
    private $baza0;

    /**
     * @var float
     */
    private $pdv0;

    /**
     * @var float
     */
    private $bazaZvil;

    /**
     * @var float
     */
    private $pdvZvil;

    /**
     * @var float
     */
    private $bazaNeGos;

    /**
     * @var float
     */
    private $pdvNeGos;

    /**
     * @var float
     */
    private $bazaZaMezhi;

    /**
     * @var float
     */
    private $pdvZaMezhi;

    /**
     * @var \DateTime
     */
    private $rkeDateCreateInvoice;

    /**
     * @var string
     */
    private $rkeNumInvoice;

    /**
     * @var string
     */
    private $rkePidstava;

    /**
     * @var string
     */
    private $keyField;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set month
     *
     * @param integer $month
     *
     * @return ReestrbranchIn
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return ReestrbranchIn
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set numBranch
     *
     * @param string $numBranch
     *
     * @return ReestrbranchIn
     */
    public function setNumBranch($numBranch)
    {
        $this->numBranch = $numBranch;

        return $this;
    }

    /**
     * Get numBranch
     *
     * @return string
     */
    public function getNumBranch()
    {
        return $this->numBranch;
    }

    /**
     * Set dateGetInvoice
     *
     * @param \DateTime $dateGetInvoice
     *
     * @return ReestrbranchIn
     */
    public function setDateGetInvoice($dateGetInvoice)
    {
        if (new \DateTime("0000-00-00")==$dateGetInvoice)
	        {
		        $this->dateGetInvoice = null;
	        } else {
		        $this->dateGetInvoice = $dateGetInvoice;
        }
        return $this;
    }

    /**
     * Get dateGetInvoice
     *
     * @return \DateTime
     */
    public function getDateGetInvoice()
    {
        return $this->dateGetInvoice;
    }

    /**
     * Set dateCreateInvoice
     *
     * @param \DateTime $dateCreateInvoice
     *
     * @return ReestrbranchIn
     */
    public function setDateCreateInvoice($dateCreateInvoice)
    {
       if (new \DateTime("0000-00-00")==$dateCreateInvoice)
        {
            $this->dateCreateInvoice = null;
        } else {
            $this->dateCreateInvoice = $dateCreateInvoice;
        }
        return $this;
    }

    /**
     * Get dateCreateInvoice
     *
     * @return \DateTime
     */
    public function getDateCreateInvoice()
    {
        return $this->dateCreateInvoice;
    }

    /**
     * Set numInvoice
     *
     * @param string $numInvoice
     *
     * @return ReestrbranchIn
     */
    public function setNumInvoice($numInvoice)
    {
        $this->numInvoice = $numInvoice;

        return $this;
    }

    /**
     * Get numInvoice
     *
     * @return string
     */
    public function getNumInvoice()
    {
        return $this->numInvoice;
    }

    /**
     * Set typeInvoiceFull
     *
     * @param string $typeInvoiceFull
     *
     * @return ReestrbranchIn
     */
    public function setTypeInvoiceFull($typeInvoiceFull)
    {
        $this->typeInvoiceFull = $typeInvoiceFull;

        return $this;
    }

    /**
     * Get typeInvoiceFull
     *
     * @return string
     */
    public function getTypeInvoiceFull()
    {
        return $this->typeInvoiceFull;
    }

    /**
     * Set nameClient
     *
     * @param string $nameClient
     *
     * @return ReestrbranchIn
     */
    public function setNameClient($nameClient)
    {
        $this->nameClient = $nameClient;

        return $this;
    }

    /**
     * Get nameClient
     *
     * @return string
     */
    public function getNameClient()
    {
        return $this->nameClient;
    }

    /**
     * Set innClient
     *
     * @param string $innClient
     *
     * @return ReestrbranchIn
     */
    public function setInnClient($innClient)
    {
        $this->innClient = $innClient;

        return $this;
    }

    /**
     * Get innClient
     *
     * @return string
     */
    public function getInnClient()
    {
        return $this->innClient;
    }

    /**
     * Set zagSumm
     *
     * @param float $zagSumm
     *
     * @return ReestrbranchIn
     */
    public function setZagSumm($zagSumm)
    {
        if (empty($zagSumm)){
            $this->zagSumm=0;
        } else {
            $this->zagSumm = $zagSumm;
        }

        return $this;
    }

    /**
     * Get zagSumm
     *
     * @return float
     */
    public function getZagSumm()
    {
        return $this->zagSumm;
    }

    /**
     * Set baza20
     *
     * @param float $baza20
     *
     * @return ReestrbranchIn
     */
    public function setBaza20($baza20)
    {
        if(empty($baza20)) {
            $this->baza20 = 0;
        } else{
            $this->baza20 = $baza20;
        }
        return $this;
    }

    /**
     * Get baza20
     *
     * @return float
     */
    public function getBaza20()
    {
        return $this->baza20;
    }

    /**
     * Set pdv20
     *
     * @param float $pdv20
     *
     * @return ReestrbranchIn
     */
    public function setPdv20($pdv20)
    {
        if (empty($pdv20)){
            $this->pdv20 =0;
        } else {
            $this->pdv20 = $pdv20;
        }

        return $this;
    }

    /**
     * Get pdv20
     *
     * @return float
     */
    public function getPdv20()
    {
        return $this->pdv20;
    }

    /**
     * Set baza7
     *
     * @param float $baza7
     *
     * @return ReestrbranchIn
     */
    public function setBaza7($baza7)
    {
        if(empty($baza7)) {
            $this->baza7 = 0;
        } else{
            $this->baza7 = $baza7;
        }

        return $this;
    }

    /**
     * Get baza7
     *
     * @return float
     */
    public function getBaza7()
    {
        return $this->baza7;
    }

    /**
     * Set pdv7
     *
     * @param float $pdv7
     *
     * @return ReestrbranchIn
     */
    public function setPdv7($pdv7)
    {
        if (empty($pdv7)){
            $this->pdv7 =0;
        } else {
            $this->pdv7 = $pdv7;
        }

        return $this;
    }

    /**
     * Get pdv7
     *
     * @return float
     */
    public function getPdv7()
    {
        return $this->pdv7;
    }

    /**
     * Set baza0
     *
     * @param float $baza0
     *
     * @return ReestrbranchIn
     */
    public function setBaza0($baza0)
    {
        if(empty($baza0)) {
            $this->baza0 = 0;
        } else{
            $this->baza0 = $baza0;
        }

        return $this;
    }

    /**
     * Get baza0
     *
     * @return float
     */
    public function getBaza0()
    {
        return $this->baza0;
    }

    /**
     * Set pdv0
     *
     * @param float $pdv0
     *
     * @return ReestrbranchIn
     */
    public function setPdv0($pdv0)
    {
        if (empty($pdv0)){
            $this->pdv0 =0;
        } else {
            $this->pdv0 = $pdv0;
        }

        return $this;
    }

    /**
     * Get pdv0
     *
     * @return float
     */
    public function getPdv0()
    {
        return $this->pdv0;
    }

    /**
     * Set bazaZvil
     *
     * @param float $bazaZvil
     *
     * @return ReestrbranchIn
     */
    public function setBazaZvil($bazaZvil)
    {
        if(empty($bazaZvil)) {
            $this->bazaZvil = 0;
        } else{
            $this->bazaZvil = $bazaZvil;
        }

        return $this;
    }

    /**
     * Get bazaZvil
     *
     * @return float
     */
    public function getBazaZvil()
    {
        return $this->bazaZvil;
    }

    /**
     * Set pdvZvil
     *
     * @param float $pdvZvil
     *
     * @return ReestrbranchIn
     */
    public function setPdvZvil($pdvZvil)
    {
        if (empty($pdvZvil)){
            $this->pdvZvil =0;
        } else {
            $this->pdvZvil = $pdvZvil;
        }

        return $this;
    }

    /**
     * Get pdvZvil
     *
     * @return float
     */
    public function getPdvZvil()
    {
        return $this->pdvZvil;
    }

    /**
     * Set bazaNeGos
     *
     * @param float $bazaNeGos
     *
     * @return ReestrbranchIn
     */
    public function setBazaNeGos($bazaNeGos)
    {
        if(empty($bazaNeGos)) {
            $this->bazaNeGos = 0;
        } else{
            $this->bazaNeGos = $bazaNeGos;
        }

        return $this;
    }

    /**
     * Get bazaNeGos
     *
     * @return float
     */
    public function getBazaNeGos()
    {
        return $this->bazaNeGos;
    }

    /**
     * Set pdvNeGos
     *
     * @param float $pdvNeGos
     *
     * @return ReestrbranchIn
     */
    public function setPdvNeGos($pdvNeGos)
    {
        if (empty($pdvNeGos)){
            $this->pdvNeGos =0;
        } else {
            $this->pdvNeGos = $pdvNeGos;
        }

        return $this;
    }

    /**
     * Get pdvNeGos
     *
     * @return float
     */
    public function getPdvNeGos()
    {
        return $this->pdvNeGos;
    }

    /**
     * Set bazaZaMezhi
     *
     * @param float $bazaZaMezhi
     *
     * @return ReestrbranchIn
     */
    public function setBazaZaMezhi($bazaZaMezhi)
    {
        if(empty($bazaZaMezhi)) {
        $this->bazaZaMezhi = 0;
        } else{
        $this->bazaZaMezhi = $bazaZaMezhi;
        }


        return $this;
    }

    /**
     * Get bazaZaMezhi
     *
     * @return float
     */
    public function getBazaZaMezhi()
    {
        return $this->bazaZaMezhi;
    }

    /**
     * Set pdvZaMezhi
     *
     * @param float $pdvZaMezhi
     *
     * @return ReestrbranchIn
     */
    public function setPdvZaMezhi($pdvZaMezhi)
    {
        if (empty($pdvZaMezhi)){
            $this->pdvZaMezhi =0;
        } else {
            $this->pdvZaMezhi = $pdvZaMezhi;
        }

        return $this;
    }

    /**
     * Get pdvZaMezhi
     *
     * @return float
     */
    public function getPdvZaMezhi()
    {
        return $this->pdvZaMezhi;
    }

    /**
     * Set rkeDateCreateInvoice
     * значение \DateTime("0000-00-00") присваивается при разборе строк в реестре если дата установлена пустой
     * если получено значение даты равное \DateTime("0000-00-00")  rkeDateCreateInvoice = null
     * иначе - присваиваем полученную дату
     * @param \DateTime $rkeDateCreateInvoice
     *
     * @return ReestrbranchIn
     */
    public function setRkeDateCreateInvoice($rkeDateCreateInvoice)
    {
       if (new \DateTime("0000-00-00")==$rkeDateCreateInvoice)
       {
           $this->rkeDateCreateInvoice = null;
       } else {
           $this->rkeDateCreateInvoice = $rkeDateCreateInvoice;
       }

        return $this;
    }

    /**
     * Get rkeDateCreateInvoice
     * если было передано нулевое значение то вернуть надо нулевую дату
     * иначе надо вернуть полученное значение
     * @return \DateTime|null
     */
    public function getRkeDateCreateInvoice()
    {
        //return $this->rkeDateCreateInvoice;
        if(null == $this->rkeDateCreateInvoice){
            return new \DateTime("0000-00-00");
        }else{
            return $this->rkeDateCreateInvoice;
        }
    }

    /**
     * Set rkeNumInvoice
     *
     * @param string $rkeNumInvoice
     *
     * @return ReestrbranchIn
     */
    public function setRkeNumInvoice($rkeNumInvoice)
    {
        $this->rkeNumInvoice = $rkeNumInvoice;

        return $this;
    }

    /**
     * Get rkeNumInvoice
     *
     * @return string
     */
    public function getRkeNumInvoice()
    {
        return $this->rkeNumInvoice;
    }

    /**
     * Set rkePidstava
     *
     * @param string $rkePidstava
     *
     * @return ReestrbranchIn
     */
    public function setRkePidstava($rkePidstava)
    {
        $this->rkePidstava = $rkePidstava;

        return $this;
    }

    /**
     * Get rkePidstava
     *
     * @return string
     */
    public function getRkePidstava()
    {
        return $this->rkePidstava;
    }

    /**
     * Set keyField
     *
     * @param string $keyField
     *
     * @return ReestrbranchIn
     */
    public function setKeyField()
    {
        if ($this->dateCreateInvoice==null) {
            $this->keyField = $this->numInvoice . '/' . $this->typeInvoiceFull . '/null/' . $this->innClient;
        } else{
            $this->keyField = $this->numInvoice . '/' . $this->typeInvoiceFull . '/' . date_format ($this->dateCreateInvoice , "d-m-Y") . '/' . $this->innClient;
        }

        return $this;
    }

    /**
     * Get keyField
     *
     * @return string
     */
    public function getKeyField()
    {
        return $this->keyField;
    }
}

