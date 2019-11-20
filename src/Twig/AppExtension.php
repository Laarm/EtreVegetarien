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
        ];
    }

    public function reducContent($content, $max): string {
        $content = strip_tags($content);
        return substr($content, 0, $max);
    }
}