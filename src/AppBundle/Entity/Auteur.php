<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Taxon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Auteur
 *
 * @ORM\Table(name="auteur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuteurRepository")
 */
class Auteur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(
     *     min = 2,
     *     minMessage="auteur.error.minName"
     * )
     */
    private $name;

    /**
     * @var ArrayCollection;
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Taxon", mappedBy="auteurs", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $taxons;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taxons = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Auteur
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add taxon
     *
     * @param Taxon $taxon
     *
     * @return Auteur
     */
    public function addTaxon(Taxon $taxon)
    {
        $this->taxons[] = $taxon;

        return $this;
    }

    /**
     * Remove taxon
     *
     * @param Taxon $taxon
     */
    public function removeTaxon(Taxon $taxon)
    {
        $this->taxons->removeElement($taxon);
    }

    /**
     * Get taxons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTaxons()
    {
        return $this->taxons;
    }
}
