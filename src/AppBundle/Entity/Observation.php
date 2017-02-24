<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Taxon;
use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Observation
 *
 * @ORM\Table(name="observation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObservationRepository")
 */
class Observation
{

    const OBS_STARTED = "started";
    const OBS_MODIFIED = "modified";
    const OBS_REFUSED = "refused";
    const OBS_VALIDATED = "validated";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\Range(
     *     min ="-2 years",
     *     max ="+1 hour",
     *     minMessage="observation.error.mindate",
     *     maxMessage="observation.error.maxdate"
     * )
     *
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="decimal", precision=10, scale=8)
     * @Assert\NotBlank(message="observation.error.geoBlank")
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="decimal", precision=11, scale=8)
     * @Assert\NotBlank(message="observation.error.geoBlank")
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     * @Assert\NotBlank(message="observation.error.statusNull")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_path", type="string", length=255, nullable=true)
     */
    private $photoPath;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="observations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Taxon", inversedBy="observations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="observation.error.taxonNull")
     */
    private $taxon;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->status = self::OBS_STARTED;
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Observation
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Observation
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Observation
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Observation
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set photoPath
     *
     * @param string $photoPath
     *
     * @return Observation
     */
    public function setPhotoPath($photoPath)
    {
        $this->photoPath = $photoPath;
    
        return $this;
    }

    /**
     * Get photoPath
     *
     * @return string
     */
    public function getPhotoPath()
    {
        return $this->photoPath;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Observation
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set taxon
     *
     * @param Taxon $taxon
     *
     * @return Observation
     */
    public function setTaxon(Taxon $taxon)
    {
        $this->taxon = $taxon;
    
        return $this;
    }

    /**
     * Get taxon
     *
     * @return Taxon
     */
    public function getTaxon()
    {
        return $this->taxon;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Observation
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
