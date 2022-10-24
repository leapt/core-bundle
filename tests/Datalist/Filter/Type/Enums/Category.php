<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Datalist\Filter\Type\Enums;

enum Category: string
{
    case Movies = 'movies';
    case TVShows = 'tv shows';
    case Books = 'books';
}
