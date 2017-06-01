<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 01/06/2017
 * Time: 16:28
 */

namespace AppBundle\Twig;


class DisplayTaxonInfo extends \Twig_Extension
{
    public function getFilters()
    {
        return [
          new \Twig_SimpleFilter('displayTaxonInfo', [$this, 'displayTaxonInfo'])
        ];
    }

    public function displayTaxonInfo($value)
    {
        return ($value === '') ? 'Inconnu' : $value;
    }
}
