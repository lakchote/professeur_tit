<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Ordre;
use AppBundle\Entity\Taxon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Length(
     *     min = 2,
     *     minMessage="famille.error.minName"
     * )
     */
    private $name;

    /**
     * @var ArrayCollection;
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Taxon", mappedBy="famille", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $taxons;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ordre", inversedBy="familles", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $ordre;

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
     * @param Taxon $taxon
     *
     * @return Famille
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
     * Set ordre
     *
     * @param Ordre $ordre
     *
     * @return Famille
     */
    public function setOrdre(Ordre $ordre)
    {
        $ordre->addFamille($this);
        $this->ordre = $ordre;
    
        return $this;
    }

    /**
     * Get ordre
     *
     * @return Ordre
     */
    public function getOrdre()
    {
        return $this->ordre;
    }
}
