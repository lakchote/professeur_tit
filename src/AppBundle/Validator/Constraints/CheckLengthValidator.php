<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 06/02/2017
 * Time: 16:16
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckLengthValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if(strlen($value) < 6 || strlen($value) > 12) {
            (strlen($value) < 6) ?
                $this->context->buildViolation($constraint->minMessage)->addViolation() : $this->context->buildViolation($constraint->maxMessage)->addViolation();
        }
    }
}
