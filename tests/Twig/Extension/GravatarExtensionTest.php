<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\Extension;

use Leapt\CoreBundle\Twig\Extension\GravatarExtension;
use PHPUnit\Framework\TestCase;

final class GravatarExtensionTest extends TestCase
{
    private GravatarExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new GravatarExtension();
    }

    public function testGetFilters(): void
    {
        $filters = $this->extension->getFilters();
        self::assertSame('gravatar', $filters[0]->getName());
    }

    public function testGravatar(): void
    {
        self::assertSame('<img src="https://www.gravatar.com/avatar/93942e96f5acd83e2e047ad8fe03114d?s=35&d=mm&r=g" class="gravatar">', $this->extension->gravatar('test@email.com'));
        self::assertSame('<img src="https://www.gravatar.com/avatar/93942e96f5acd83e2e047ad8fe03114d?s=40&d=monsterid&r=pg" class="custom" data-attr="ok">', $this->extension->gravatar(
            'test@email.com',
            40,
            ['class' => 'custom', 'data-attr' => 'ok'],
            'monsterid',
            'pg',
        ));
        self::assertSame('https://www.gravatar.com/avatar/93942e96f5acd83e2e047ad8fe03114d?s=35&d=mm&r=g', $this->extension->gravatar(
            'test@email.com',
            35,
            [],
            'mm',
            'g',
            false,
        ));
    }
}
