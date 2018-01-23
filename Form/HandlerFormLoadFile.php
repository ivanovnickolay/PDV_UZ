<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 06.06.2016
 * Time: 22:54
 */

namespace App\Form;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManager;
use App\Entity\LoadFile;


/**
 * Class HandlerFormLoadFile
 * @package AnalizPdvBundle\Form
 */
class HandlerFormLoadFile
{

    private $entityManager;
    private $fileDir;
    private $typeDoc;

    /**
     * HandlerFormLoadFile constructor.
     * @param EntityManager $entityManager
     * @param $fileDir путь к директории, в которую надо грузить файлы
     */
    public function __construct(EntityManager $entityManager,$fileDir)
    {
        $this->entityManager=$entityManager;
        $this->fileDir=$fileDir;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @param $typeDoc тип документа,который загружается формой $form
     * @return bool
     */
    public function handler(FormInterface $form, Request $request, $typeDoc="temp")
    {
        $this->typeDoc=$typeDoc;
        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return false;
        }
        //$form->bind($request);

        if (!$form->isValid()) {
        return false;
            }
            $Load=$form->getData();
                $this->create($Load);
            return true;

    }

    private function create($Load)
    {

        $file = $Load['File'];

        // Generate a unique name for the file before saving it
        $fileName = md5(uniqid()) . '.' . $file->getClientOriginalExtension();

        // Move the file to the directory where brochures are stored
        $file->move($this->fileDir,$fileName);

        $LoadFile=new LoadFile();
        $LoadFile->setUploadName($fileName);
        $LoadFile->setTypeFile($Load['File']->getClientOriginalExtension());
        $LoadFile->setOriginalName($Load['File']->getClientOriginalName());
        $LoadFile->setDescriptionFile($Load['descriptionFile']);
        $LoadFile->setTypeDoc($this->typeDoc);
        $LoadFile->setUploadDate(new \DateTime('NOW'));

       // $this->entityManager = $this->getDoctrine()->getManager();
        $this->entityManager->persist($LoadFile);
        $this->entityManager->flush();
    }
}