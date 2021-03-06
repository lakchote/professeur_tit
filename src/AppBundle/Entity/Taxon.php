<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Auteur;
use AppBundle\Entity\Famille;
use AppBundle\Entity\Habitat;
use AppBundle\Entity\Observation;
use AppBundle\Entity\Ordre;
use AppBundle\Entity\Rang;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Taxon
 *
 * @ORM\Table(name="taxon")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaxonRepository")
 */
class Taxon
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
     * @var int
     *
     * @ORM\Column(name="cdNom", type="integer", unique=true)
     */
    private $cdNom;

    /**
     * @var int
     *
     * @ORM\Column(name="taxSup", type="integer", nullable=true)
     */
    private $taxSup;

    /**
     * @var int
     *
     * @ORM\Column(name="cdSup", type="integer", nullable=true)
     */
    private $cdSup;

    /**
     * @var int
     *
     * @ORM\Column(name="cdRef", type="integer")
     */
    private $cdRef;

    /**
     * @var string
     *
     * @ORM\Column(name="nomLatin", type="string", length=255)
     */
    private $nomLatin;

    /**
     * @var string
     *
     * @ORM\Column(name="nomVernaculaire", type="string", length=255)
     */
    private $nomVernaculaire;

    /**
     * @var string
     *
     * @ORM\Column(name="nomVernaculaireEN", type="string", length=255)
     */
    private $nomVernaculaireEN;

    /**
     * @var int
     *
     * @ORM\Column(name="annee", type="integer", nullable=true)
     */
    private $annee;

    /**
     * @var bool
     *
     * @ORM\Column(name="urlINPN", type="boolean")
     */
    private $urlINPN;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ordre", inversedBy="taxons", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     */
    private $ordre;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Famille", inversedBy="taxons", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     */
    private $famille;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Rang", inversedBy="taxons", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $rang;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Habitat", inversedBy="taxons", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     */
    private $habitat;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Auteur", inversedBy="taxons", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     */
    private $auteurs;

    /**
     * @var ArrayCollection;
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Observation", mappedBy="taxon", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $observations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
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
     * Set cdNom
     *
     * @param integer $cdNom
     *
     * @return Taxon
     */
    public function setCdNom($cdNom)
    {
        $this->cdNom = $cdNom;
    
        return $this;
    }

    /**
     * Get cdNom
     *
     * @return integer
     */
    public function getCdNom()
    {
        return $this->cdNom;
    }

    /**
     * Set taxSup
     *
     * @param integer $taxSup
     *
     * @return Taxon
     */
    public function setTaxSup($taxSup)
    {
        $this->taxSup = $taxSup;
    
        return $this;
    }

    /**
     * Get taxSup
     *
     * @return integer
     */
    public function getTaxSup()
    {
        return $this->taxSup;
    }

    /**
     * Set cdSup
     *
     * @param integer $cdSup
     *
     * @return Taxon
     */
    public function setCdSup($cdSup)
    {
        $this->cdSup = $cdSup;
    
        return $this;
    }

    /**
     * Get cdSup
     *
     * @return integer
     */
    public function getCdSup()
    {
        return $this->cdSup;
    }

    /**
     * Set cdRef
     *
     * @param integer $cdRef
     *
     * @return Taxon
     */
    public function setCdRef($cdRef)
    {
        $this->cdRef = $cdRef;
    
        return $this;
    }

    /**
     * Get cdRef
     *
     * @return integer
     */
    public function getCdRef()
    {
        return $this->cdRef;
    }

    /**
     * Set nomLatin
     *
     * @param string $nomLatin
     *
     * @return Taxon
     */
    public function setNomLatin($nomLatin)
    {
        $this->nomLatin = $nomLatin;
    
        return $this;
    }

    /**
     * Get nomLatin
     *
     * @return string
     */
    public function getNomLatin()
    {
        return $this->nomLatin;
    }

    /**
     * Set nomVernaculaire
     *
     * @param string $nomVernaculaire
     *
     * @return Taxon
     */
    public function setNomVernaculaire($nomVernaculaire)
    {
        $this->nomVernaculaire = $nomVernaculaire;
    
        return $this;
    }

    /**
     * Get nomVernaculaire
     *
     * @return string
     */
    public function getNomVernaculaire()
    {
        return $this->nomVernaculaire;
    }

    /**
     * Set nomVernaculaireEN
     *
     * @param string $nomVernaculaireEN
     *
     * @return Taxon
     */
    public function setNomVernaculaireEN($nomVernaculaireEN)
    {
        $this->nomVernaculaireEN = $nomVernaculaireEN;
    
        return $this;
    }

    /**
     * Get nomVernaculaireEN
     *
     * @return string
     */
    public function getNomVernaculaireEN()
    {
        return $this->nomVernaculaireEN;
    }

    /**
     * Set annee
     *
     * @param integer $annee
     *
     * @return Taxon
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;
    
        return $this;
    }

    /**
     * Get annee
     *
     * @return integer
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set urlINPN
     *
     * @param boolean $urlINPN
     *
     * @return Taxon
     */
    public function setUrlINPN($urlINPN)
    {
        $this->urlINPN = $urlINPN;
    
        return $this;
    }

    /**
     * Get urlINPN
     *
     * @return boolean
     */
    public function getUrlINPN()
    {
        return $this->urlINPN;
    }

    /**
     * Set ordre
     *
     * @param Ordre $ordre
     *
     * @return Taxon
     */
    public function setOrdre(Ordre $ordre = null)
    {
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

    /**
     * Set famille
     *
     * @param Famille $famille
     *
     * @return Taxon
     */
    public function setFamille(Famille $famille = null)
    {
        $this->famille = $famille;
    
        return $this;
    }

    /**
     * Get famille
     *
     * @return Famille
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * Set rang
     *
     * @param Rang $rang
     *
     * @return Taxon
     */
    public function setRang(Rang $rang)
    {
        $this->rang = $rang;
    
        return $this;
    }

    /**
     * Get rang
     *
     * @return Rang
     */
    public function getRang()
    {
        return $this->rang;
    }

    /**
     * Set habitat
     *
     * @param Habitat $habitat
     *
     * @return Taxon
     */
    public function setHabitat(Habitat $habitat = null)
    {
        $this->habitat = $habitat;
    
        return $this;
    }

    /**
     * Get habitat
     *
     * @return Habitat
     */
    public function getHabitat()
    {
        return $this->habitat;
    }

    /**
     * Add auteur
     *
     * @param Auteur $auteur
     *
     * @return Taxon
     */
    public function addAuteur(Auteur $auteur)
    {
        $this->auteurs[] = $auteur;
    
        return $this;
    }

    /**
     * Remove auteur
     *
     * @param Auteur $auteur
     */
    public function removeAuteur(Auteur $auteur)
    {
        $this->auteurs->removeElement($auteur);
    }

    /**
     * Get auteurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuteurs()
    {
        return $this->auteurs;
    }

    /**
     * Add observation
     *
     * @param Observation $observation
     *
     * @return Taxon
     */
    public function addObservation(Observation $observation)
    {
        $this->observations[] = $observation;
    
        return $this;
    }

    /**
     * Remove observation
     *
     * @param Observation $observation
     */
    public function removeObservation(Observation $observation)
    {
        $this->observations->removeElement($observation);
    }

    /**
     * Get observations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getObservations()
    {
        return $this->observations;
    }
}
