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
        if($interval->format('%a') > 1) return $interval->format('%a jours');
        return $interval->format('%a jour');
    }
}
