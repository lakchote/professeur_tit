<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="Cet email est déjà utilisé !")
 */
class User implements UserInterface, \Serializable
{

    private $img_path = '/uploads/user/';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez indiquer votre nom.")
     * @Assert\Length(max="50", maxMessage="Le nom ne peut excéder 50 caractères.")
     */
    private $nom;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez indiquer votre prénom.")
     * @Assert\Length(max="50", maxMessage="Le prénom ne peut excéder 50 caractères.")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(groups={"Registration"}, message="Vous devez indiquer votre mot de passe.")
     * @Assert\Length(groups={"Registration"}, min=6, max=12, minMessage="Le mot de passe doit faire 6 caractères au minimum.", maxMessage="Le mot de passe doit faire 12 caractères au maximum.")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string")
     * @Assert\Email(message="L'adresse mail n'est pas valide.", checkMX=true)
     * @Assert\NotBlank(message="Vous devez spécifier votre adresse mail.")
     */
    private $email;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $reset_password;

    /**
     * @ORM\Column(type="date")
     */
    private $date_inscription;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description = 'Pas de description renseignée pour l\'instant.';


    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(mimeTypes={"image/jpeg", "image/png"})
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_email_visible = false;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Observation", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $observations;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $raisonBan;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateBan;

    public function __construct()
    {
        $this->observations = new ArrayCollection();
    }

    public function getRoles()
    {
        $roles = $this->roles;
        if(!in_array('ROLE_FROZEN', $roles)) {
            $roles[] = 'ROLE_OBSERVATEUR';
        }
        return $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {

    }

    public function getUsername()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles[] = $roles;
    }

    /**
     * @return mixed
     */
    public function getResetPassword()
    {
        return $this->reset_password;
    }

    /**
     * @param mixed $reset_password
     */
    public function setResetPassword($reset_password)
    {
        $this->reset_password = $reset_password;
    }

     public function getObservations()
     {
         return $this->observations;
     }

     public function addObservations(Observation $observation)
     {
         $this->observations->add($observation);
         $observation->setUser($this);
     }

     public function removeObservations(Observation $observation)
     {
         $this->observations->removeElement($observation);
     }

    /**
     * @return mixed
     */
    public function getDateInscription()
    {
        return $this->date_inscription;
    }

    /**
     * @param mixed $date_inscription
     */
    public function setDateInscription($date_inscription)
    {
        $this->date_inscription = $date_inscription;
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
    public function getIsEmailVisible()
    {
        return $this->is_email_visible;
    }

    /**
     * @param mixed $is_email_visible
     */
    public function setIsEmailVisible($is_email_visible)
    {
        $this->is_email_visible = $is_email_visible;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        if($image !== null) $this->image = $image;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->nom,
            $this->prenom,
            $this->password,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->nom,
            $this->prenom,
            $this->password,
            ) = unserialize($serialized);
    }

    public function getImgPath()
    {
        return $this->img_path;
    }

    /**
     * @return mixed
     */
    public function getRaisonBan()
    {
        return $this->raisonBan;
    }

    /**
     * @param mixed $raisonBan
     */
    public function setRaisonBan($raisonBan)
    {
        $this->raisonBan = $raisonBan;
    }

    /**
     * @return mixed
     */
    public function getDateBan()
    {
        return $this->dateBan;
    }

    /**
     * @param mixed $dateBan
     */
    public function setDateBan($dateBan)
    {
        $this->dateBan = $dateBan;
    }

    public function resetRoles()
    {
        $this->roles = [];
    }
}
