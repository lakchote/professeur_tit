<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="page")
 */
class Page
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $titreRoute;

    /**
     * @ORM\Column(type="string")
     */
    private $titrePage = 'Professeur Tit';

    /**
     * @ORM\Column(type="string")
     */
    private $description = 'Application participative gratuite visant à étudier les effets du climat, de l’urbanisation et de l’agriculture sur la biodiversité.';

    /**
     * @ORM\Column(type="string")
     */
    private $keywords = 'ornithologie, association, climat, urbanisation, agriculture, biodiversité, découvrir, application, oiseaux';

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitreRoute()
    {
        return $this->titreRoute;
    }

    /**
     * @param mixed $titreRoute
     */
    public function setTitreRoute($titreRoute)
    {
        $this->titreRoute = $titreRoute;
    }

    /**
     * @return mixed
     */
    public function getTitrePage()
    {
        return $this->titrePage;
    }

    /**
     * @param mixed $titrePage
     */
    public function setTitrePage($titrePage)
    {
        $this->titrePage = $titrePage;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param mixed $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

}
