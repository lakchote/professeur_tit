<?php
/**
 * Created by PhpStorm.
 * User: BENY
 * Date: 25/02/2017
 * Time: 23:22
 */

namespace AppBundle\Doctrine;

use AppBundle\Entity\Taxon;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Entity\Observation;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;

class ObservationPhotoUploadListener
{
    private $uploader;
    private $targetPath;

    public function __construct(FileUploader $uploader, $targetPath)
    {
        $this->uploader = $uploader;
        $this->targetPath = $targetPath;

    }

    public function prePersist(LifecycleEventArgs $args)
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
        if (!$entity instanceof Observation) {
            return;
        }
        $fileName = $entity->getImage();

        $entity->setImage(new File($this->targetPath.'/'.$fileName));
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof Observation) {
            return;
        }

        $file = $entity->getImage();

        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->uploader->upload($file);
        $entity->setImage($fileName);
    }
}