<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('reducContent', [$this, 'reducContent']),
            new TwigFilter('timeDiff', [$this, 'timeDiff']),
        ];
    }

    public function reducContent($content, $max): string
    {
        $content = strip_tags($content);
        return substr($content, 0, $max);
    }

    public function timeDiff($oneTime, $twoTime, $full, $phrase): string
    {
        $oneTime = new \DateTime($oneTime);
        // $twoTime = new \DateTime();
        if ($twoTime == "now") {
            $twoTime = new \DateTime();
        }
        $diff = $twoTime->diff($oneTime);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'an',
            'm' => 'mois',
            'w' => 'semaine',
            'd' => 'jour',
            'h' => 'heure',
            'i' => 'minute',
            's' => 'seconde',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? $phrase . implode(', ', $string) : 'Maintenant';
    }
}
