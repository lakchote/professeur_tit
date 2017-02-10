<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Taxon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Length(
     *     min = 2,
     *     minMessage="habitat.error.minName"
     * )
     */
    private $name;

    /**
     * @var ArrayCollection;
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Taxon", mappedBy="habitat", cascade={"persist", "remove"})
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
     * @param Taxon $taxon
     *
     * @return Habitat
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
