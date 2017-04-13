<?php

namespace  AppBundle\Twig;

class DateDiff extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('dateDiffInDays', [$this, 'getDateDiffInDays'])
        ];
    }

    public function getDateDiffInDays(\DateTime $date)
    {
        $today = new \DateTime();
        $interval = $today->diff($date);
        return ($interval->format('%a') > 1) ? $interval->format('%a jours') : $interval->format('%a jour');
    }
}
