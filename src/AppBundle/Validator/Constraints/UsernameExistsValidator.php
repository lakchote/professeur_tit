<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 05/02/2017
 * Time: 16:53
 */

namespace AppBundle\Validator\Constraints;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UsernameExistsValidator extends ConstraintValidator
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        if(!$this->em->getRepository('AppBundle:User')->findOneBy(['email' => $value])) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
