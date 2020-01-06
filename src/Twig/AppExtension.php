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
        $badge = '<i class="fad fa-user margin-auto"></i>';
        if ($content == "Végétarien") {$badge = '<i class="fad fa-seedling margin-auto"></i>';}
        if ($content == "Pesco-végétarien") {$badge = '<i class="fad fa-fish margin-auto"></i>';}
        if ($content == "Végan") {$badge = '<i class="fad fa-leaf margin-auto"></i>';}
        if ($content == "Flexitarien") {$badge = '<i class="fad fa-steak margin-auto"></i>';}
        if ($content == "L'ovo-végétarien") {$badge = '<i class="fad fa-egg margin-auto"></i>';}
        if ($content == "Lacto-végétarien" OR $content == "L'ovo-lacto-végétarien") {$badge = '<i class="fad fa-cheese-swiss margin-auto"></i>';}
        if ($content == "Pollo-végétarien") {$badge = '<i class="fad fa-drumstick margin-auto"></i>';}
        if ($content == "Crudivorien") {$badge = '<i class="fas fa-carrot margin-auto"></i>';}
        return $badge;
    }

    public function timeDiff($oneTime, $twoTime, $full, $phrase): string
    {
        $oneTime = $oneTime;
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
