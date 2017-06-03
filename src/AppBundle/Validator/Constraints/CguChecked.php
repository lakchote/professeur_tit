<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 05/02/2017
 * Time: 12:40
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class CguChecked extends Constraint
{
    public $message = 'Vous devez accepter les CGU.';
}
