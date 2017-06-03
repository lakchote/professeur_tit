<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 06/02/2017
 * Time: 16:15
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class CheckLength extends Constraint
{
    public $minMessage = "Le mot de passe doit faire 6 caractères au minimum.";
    public $maxMessage = "Le mot de passe doit faire 12 caractères au maximum.";
}
