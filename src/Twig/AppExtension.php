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
            new TwigFilter('badgePreferenceIcon', [$this, 'badgePreferenceIcon']),
            new TwigFilter('badgeIcon', [$this, 'badgeIcon']),
        ];
    }

    public function reducContent($content, $max): string
    {
        $content = strip_tags($content);
        return substr($content, 0, $max);
    }

    public function badgeIcon($content): string
    {
        if ($content == "ROLE_ADMIN") {
            return '<i class="cds ml-1 fad fa-badge-check"></i>';
        }
        if ($content == "") {
            return '';
        }
    }

    public function badgePreferenceIcon($content): string
    {
        $badge = array(
            'Végétarien' => 'fa-seedling',
            'Pesco-végétarien' => 'fa-fish',
            'Végan' => 'fa-leaf',
            'Flexitarien' => 'fa-steak',
            "L'ovo-végétarien" => 'fa-egg',
            "L'ovo-lacto-végétarien" => 'fa-fa-cheese-swiss',
            'Lacto-végétarien' => 'fa-cheese-swiss',
            'Pollo-végétarien' => 'fa-drumstick',
            'Crudivorien' => 'fa-carrot',
        );
        if(!empty($badge[$content])){
            return '<i class="fad '.$badge[$content].' margin-auto"></i>';
        }else{
            return '<i class="fad fa-user margin-auto"></i>';
        }
    }

    public function timeDiff($oneTime, $twoTime, $full, $phrase): string
    {
        if ($twoTime == "now") {$twoTime = new \DateTime();}
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
