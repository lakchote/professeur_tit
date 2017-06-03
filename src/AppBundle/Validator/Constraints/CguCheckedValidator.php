<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 05/02/2017
 * Time: 12:42
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CguCheckedValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if($value == '') {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }

}
