<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.02.2018
 * Time: 15:35
 */

namespace App\Utilits\loadDataExcel\configLoader;
use App\Utilits\loadDataExcel;


/**
 * Класс предназначен для централизации создания конфигурации загрузки
 * Class configLoaderFactory
 * @package App\Utilits\loadDataExcel\configLoader
 */
class configLoaderFactory
{

    /**
     * на основаниии анализа названия файла которое проводит parseNameFile::parseName($fileName)
     * формируется название класса котороый хранит конфигурацию загрузки данных из файла
     * на основании названия - создается соответствующий класс, который передается из функции
     * если возникает ошибка - вызывается исключение errorLoadDataException
     * test to loadRowsTest::class
     * @param $fileName
     * @return configLoader_interface
     * @throws loadDataExcel\Exception\errorLoadDataException
     * @see  loadDataExcel\loadData\loadRowsTest
     */
    public static function getConfigLoad($fileName){
        //$templateNameConfigLoadClass = "configLoad";
        $regConfigLoader = array(
            "RestrIn"=>configLoadReestrIn::class,
            "RestrOut"=>configLoadReestrOut::class
        );
                // парсим название файла для получения типа сущности
            //$typeEntity =loadDataExcel\parseNameFile::parseName($fileName);
            $typeEntity =self::parseName($fileName);
            if ($typeEntity<>'') {
               // $className ='App\\Utilits\\loadDataExcel\\configLoader\\'.$templateNameConfigLoadClass . $typeEntity;
                if (key_exists($typeEntity,$regConfigLoader)){
                    $className=$regConfigLoader[$typeEntity];
                    if (class_exists($className)){
                        return new $className();
                    }else{
                        throw new loadDataExcel\Exception\errorLoadDataException("Не найден файл конфигурации для чтения информации из файла !");
                    }

                }

            } else{
                    throw new loadDataExcel\Exception\errorLoadDataException();
            }
    }

    private static function parseName(string $fileName):string {
        $pathinfo = pathinfo($fileName);
        $baseNameFile=$pathinfo['filename'];
        if ( 1 == substr_count($baseNameFile,'TAB1')){
            return "RestrIn";
        }
        if ( 1 == substr_count($baseNameFile,'TAB2')){
            return "RestrOut";
        }
        return "";

    }

}