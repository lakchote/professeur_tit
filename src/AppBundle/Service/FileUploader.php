<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 16/02/2017
 * Time: 11:45
 */

namespace AppBundle\Service;


use Symfony\Component\HttpFoundation\File\File;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(File $file)
    {
        $filename = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($this->targetDir, $filename);
        return $filename;
    }
}