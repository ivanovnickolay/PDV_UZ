<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12.03.2017
 * Time: 18:25
 */

namespace App\Utilits\LoadInvoice\createEntity;


/**
 * Интерфейс который описывает создание сущностей при загрузке
 * данных из файлов CSV
 *
 * Interface createEntity
 * @package AnalizPdvBundle\Utilits\LoadInvoice\LoadInvoiceOut
 */
interface createEntityInterface
{
	/**
	 * создает необходимую сущность
	 * заполняет ее данными их массива $data
	 * возвращает заполненную сущность
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function getEntity(array $data);

	/**
	 * Очищает значение заполненной сущности
	 * @return mixed
	 */
	public function unsetEntity();

}