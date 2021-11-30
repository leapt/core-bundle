<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class GravatarExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('gravatar', [$this, 'gravatar'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email      The email address
     * @param int    $size       Size in pixels, defaults to 80px [ 1 - 512 ]
     * @param array  $attributes Optional, additional key/value attributes to include in the IMG tag
     * @param string $imageSet   Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $rating     Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool   $img        True to return a complete IMG tag, False for just the URL
     *
     * @return string containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    public function gravatar(string $email, int $size = 35, array $attributes = ['class' => 'gravatar'], string $imageSet = 'mm', string $rating = 'g', bool $img = true): string
    {
        $url = sprintf(
            'https://www.gravatar.com/avatar/%s?s=%d&d=%s&r=%s',
            md5(strtolower(trim($email))),
            $size,
            $imageSet,
            $rating,
        );

        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($attributes as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= '>';
        }

        return $url;
    }
}
