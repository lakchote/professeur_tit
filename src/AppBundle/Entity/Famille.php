<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Famille
 *
 * @ORM\Table(name="famille")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FamilleRepository")
 */
class Famille
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Taxon", mappedBy="famille", cascade={"persist", "remove"})
     */
    private $taxons;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ordre", inversedBy="familles", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $ordre;

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
     * @return Famille
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
     * @return Famille
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
     * Set ordre
     *
     * @param \AppBundle\Entity\Ordre $ordre
     *
     * @return Famille
     */
    public function setOrdre(\AppBundle\Entity\Ordre $ordre)
    {
        $ordre->addFamille($this);
        $this->ordre = $ordre;
    
        return $this;
    }

    /**
     * Get ordre
     *
     * @return \AppBundle\Entity\Ordre
     */
    public function getOrdre()
    {
        return $this->ordre;
    }
}
