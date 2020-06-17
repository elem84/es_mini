<?php declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HighlightExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('highlight', array($this, 'highlightWords'), [
                'is_safe' => [ 'html' ]
            ]),
        );
    }

    public function highlightWords($text, $keyword)
    {
        if (null === $keyword)
        {
            return $text;
        }

        $words = explode(' ', $keyword);
        $keywords = [];

        foreach ($words as $k => $v)
        {
            if ('' === $v)
            {
                continue;
            }

            $keywords[] = preg_quote($v);
        }

        if (empty($keywords))
        {
            return $text;
        }

        return preg_replace(
            '#\b'. implode('|\b', $keywords) .'#i',
            '<mark>\\0</mark>',
            $text
        );
    }
}