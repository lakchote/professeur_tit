<?php
/**
 * Created by PhpStorm.
 * User: BRANDON HEAT
 * Date: 01/06/2017
 * Time: 14:48
 */

namespace AppBundle\Twig;


class SafeRawOutput extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('safeRawOutput', [$this, 'safeRawOutPut'], ['is_safe' => ['html', 'json']])
        ];
    }

    public function safeRawOutput($data)
    {
        return $data;
    }
}
