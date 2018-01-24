<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 27.03.2017
 * Time: 11:01
 */

namespace App\Model;
use App\Model\Exception\noCorrectDataException;


/**
 * Class workDateForSQLTest
 * @package AnalizPdvBundle\Model
 */
class workDateForSQLTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @return array
	 */
	public function data_costr(){
		return array(
			[12,2014],
			[11,2014],
			[11,2015],
			[12,2011],
			[14,2015],

		);
	}


    /**
     * @param $m
     * @param $y
     * @dataProvider data_costr
     */
	public function test_construct($m,$y)
	{
		try {
			$odj = new workDateForSQL($m, $y);
		} catch (noCorrectDataException $e){
			return;
		}
		$this->fail ('Not raise an exception '.$m." - ".$y);
	}

	/**
	 * @return array
	 */
	public function data_getMonthMinisOne(){
		return array(
			[1,2016,12,2015],
			[11,2016,10,2016],
			[2,2017,1,2017],
			[12,2017,11,2017],
			[1,2017,12,2016],

		);
	}

    /**
     * @param $m
     * @param $y
     * @param $Mminus
     * @param $Yminus
     * @throws noCorrectDataException
     * @dataProvider data_getMonthMinisOne
     */
	public function test_getMonthMinisOne($m,$y,$Mminus,$Yminus)
	{

			$odj = new workDateForSQL($m, $y);
			$MonthMinisOn=$odj->getMonthMinisOne();
			$YearMinisOneMonth=$odj->getYearMinisOneMonth();
			$this->assertEquals($Mminus, $MonthMinisOn);
			$this->assertEquals($Yminus, $YearMinisOneMonth);
	}
	/**
	 * @return array
	 */
	public function data_getMonthMinisTwo(){
		return array(
			[1,2016,null,null],
			[12,2015,null,null],
			[2,2016,12,2015],
			[11,2016,9,2016],
			[2,2017,12,2016],
			[12,2017,10,2017],
			[1,2017,11,2016],

		);
	}

    /**
     * @param $m
     * @param $y
     * @param $Mminus
     * @param $Yminus
     * @throws noCorrectDataException
     * @dataProvider data_getMonthMinisTwo
     */
	public function test_getMonthMinisTwo($m,$y,$Mminus,$Yminus)
	{

		$odj = new workDateForSQL($m, $y);
		$MonthMinisOn=$odj->getMonthMinisTwo();
		$YearMinisOneMonth=$odj->getYearMinisTwoMonth();
		$this->assertEquals($Mminus, $MonthMinisOn);
		$this->assertEquals($Yminus, $YearMinisOneMonth);
	}

	/**
	 * @return array
	 */
	public function data_getMonthPlusOne(){
		return array(
			[1,2016,2,2016],
			[11,2016,12,2016],
			[12,2017,1,2018],
			[12,2015,1,2016],
			[1,2017,2,2017],
		);
	}

    /**
     * @param $m
     * @param $y
     * @param $Mminus
     * @param $Yminus
     * @throws noCorrectDataException
     * @dataProvider data_getMonthPlusOne
     */
	public function test_getMonthPlusOne($m,$y,$Mminus,$Yminus)
	{

		$odj = new workDateForSQL($m, $y);
		$MonthMinisOn=$odj->getMonthPlusOne();
		$YearMinisOneMonth=$odj->getYearPlusOneMonth();
		$this->assertEquals($Mminus, $MonthMinisOn);
		$this->assertEquals($Yminus, $YearMinisOneMonth);
	}

		/**
		/* @return array
		*/
	public function data_getPeriodAnalizRke(){
		return array(
			[1,2016,"2016-01-01","2016-02-15"],
			[12,2017,"2017-12-01","2018-01-15"],
			[12,2015,"2015-12-01","2016-01-15"],
			[1,2017,"2017-01-01","2017-02-15"],
		);
	}


    /**
     * @dataProvider data_getPeriodAnalizRke
     * @param $m
     * @param $y
     * @param $start
     * @param $end
     * @throws noCorrectDataException
     */
	public function test_getPeriodAnalizRke($m,$y,$start,$end)
	{
		$odj = new workDateForSQL($m, $y);
		$S=$odj->getStartPeriodAnalizRke();
		$E=$odj->getЕndPeriodAnalizRke();
		$this->assertEquals($start, $S);
		$this->assertEquals($end, $E);
	}


	/**
	/* @return array
	 */
	public function data_getPeriodAnalizRkeMinusOne(){
		return array(
			[1,2016,"2016-01-16","2016-01-31"],
			[12,2017,"2017-12-16","2017-12-31"],
			[12,2015,null,null],
			[1,2017,"2017-01-16","2017-01-31"],
		);
	}


    /**
     * @dataProvider data_getPeriodAnalizRkeMinusOne
     * @param $m
     * @param $y
     * @param $start
     * @param $end
     * @throws noCorrectDataException
     */
	public function test_getPeriodAnalizRkeMinusOne($m,$y,$start,$end)
	{
		$odj = new workDateForSQL($m, $y);
		if (!is_null($odj->getMonthMinisOne())and (!is_null($odj->getYearMinisOneMonth()))) {
			$S = $odj->getStartPeriodAnalizRkeMinusOne();
			$E = $odj->getЕndPeriodAnalizRkeMinusOne();
			$this->assertEquals($start, $S);
			$this->assertEquals($end, $E);
		}
	}

	/**
	/* @return array
	 */
	public function data_getPeriodAnalizRkeMinusTwo(){
		return array(
			[1,2017,"2015-12-01","2016-11-30"],
			[1,2016,null,null],
			[12,2017,"2015-12-01","2017-10-31"],
			[12,2015,null,null],

		);
	}


    /**
     * @dataProvider data_getPeriodAnalizRkeMinusTwo
     * @param $m
     * @param $y
     * @param $start
     * @param $end
     * @throws noCorrectDataException
     */
	public function test_getPeriodAnalizRkeMinusTwo($m,$y,$start,$end)
	{
		$odj = new workDateForSQL($m, $y);
		if (!is_null($odj->getMonthMinisTwo())and (!is_null($odj->getYearMinisTwoMonth()))) {
			$S = $odj->getStartPeriodAnalizRkeMinusTwo();
			$E = $odj->getЕndPeriodAnalizRkeMinusTwo();
			$this->assertEquals($start, $S);
			$this->assertEquals($end, $E);
		}
	}

    }
