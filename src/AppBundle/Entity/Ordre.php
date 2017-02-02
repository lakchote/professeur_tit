<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ordre
 *
 * @ORM\Table(name="ordre")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrdreRepository")
 */
class Ordre
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Taxon", mappedBy="ordre", cascade={"persist", "remove"})
     */
    private $taxons;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Famille", mappedBy="ordre", cascade={"persist", "remove"})
     */
    private $familles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taxons = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Ordre
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
     * @param \AppBundle\Entity\Taxon $taxon
     *
     * @return Ordre
     */
    public function addTaxon(\AppBundle\Entity\Taxon $taxon)
    {
        $this->taxons[] = $taxon;
    
        return $this;
    }

    /**
     * Remove taxon
     *
     * @param \AppBundle\Entity\Taxon $taxon
     */
    public function removeTaxon(\AppBundle\Entity\Taxon $taxon)
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

    /**
     * Add famille
     *
     * @param \AppBundle\Entity\Famille $famille
     *
     * @return Ordre
     */
    public function addFamille(\AppBundle\Entity\Famille $famille)
    {
        $this->familles[] = $famille;
    
        return $this;
    }

    /**
     * Remove famille
     *
     * @param \AppBundle\Entity\Famille $famille
     */
    public function removeFamille(\AppBundle\Entity\Famille $famille)
    {
        $this->familles->removeElement($famille);
    }

    /**
     * Get familles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFamilles()
    {
        return $this->familles;
    }
}
