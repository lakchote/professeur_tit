<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Famille;
use AppBundle\Entity\Taxon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Length(
     *     min = 2,
     *     minMessage="ordre.error.minName"
     * )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Taxon", mappedBy="ordre", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $taxons;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Famille", mappedBy="ordre", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $familles;

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
     * @param Taxon $taxon
     *
     * @return Ordre
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

    /**
     * Add famille
     *
     * @param Famille $famille
     *
     * @return Ordre
     */
    public function addFamille(Famille $famille)
    {
        $this->familles[] = $famille;
    
        return $this;
    }

    /**
     * Remove famille
     *
     * @param Famille $famille
     */
    public function removeFamille(Famille $famille)
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
