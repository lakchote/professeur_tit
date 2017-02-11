<?php

namespace AppBundle\Entity;

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
class User implements UserInterface
{
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Observation", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @Assert\Valid()
     */
    private $observations;

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
}