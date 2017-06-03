<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 05/02/2017
 * Time: 16:52
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class UsernameExists extends Constraint
{
    public $message = 'Aucun nom d\'utilisateur avec cette e-mail.';
}
