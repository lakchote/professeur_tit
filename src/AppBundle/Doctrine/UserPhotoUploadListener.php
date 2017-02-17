<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 16/02/2017
 * Time: 12:01
 */

namespace AppBundle\Doctrine;


use AppBundle\Entity\User;
use AppBundle\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;

class UserPhotoUploadListener
{
    private $uploader;
    private $targetPath;

    public function __construct(FileUploader $uploader, $targetPath)
    {
        $this->uploader = $uploader;
        $this->targetPath = $targetPath;
    }

    public  function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $filename = $entity->getImage();
        if($filename != '') $entity->setImage(new File($this->targetPath . '/' . $filename));
    }

    public function uploadFile($entity)
    {
        if(!$entity instanceof User)
        {
            return;
        }
        $file = $entity->getImage();

        if(!$file instanceof File)
        {
            return;
        }

        $filename = $this->uploader->upload($file);
        $entity->setImage($filename);
    }
}