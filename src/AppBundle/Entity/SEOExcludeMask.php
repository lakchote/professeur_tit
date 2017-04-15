<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="seoexclude_mask")
 */
class SEOExcludeMask
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $masque;

    /**
     * @return mixed
     */
    public function getMasque()
    {
        return $this->masque;
    }

    /**
     * @param mixed $masque
     */
    public function setMasque($masque)
    {
        $this->masque = $masque;
    }
}
