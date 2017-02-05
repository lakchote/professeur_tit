<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Habitat
 *
 * @ORM\Table(name="habitat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HabitatRepository")
 */
class Habitat
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Taxon", mappedBy="habitat", cascade={"persist", "remove"})
     */
    private $taxons;

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
     * @return Habitat
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
     * @return Habitat
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
}
