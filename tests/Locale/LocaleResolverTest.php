<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Locale;

use Leapt\CoreBundle\Locale\LocaleResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class LocaleResolverTest extends TestCase
{
    public function testResolveWithLocaleFromRequest(): void
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->once())->method('getLocale');

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $resolver = new LocaleResolver('foo', true, $requestStack);
        $resolver->resolve();
    }

    public function testResolveWithDefaultLocale(): void
    {
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack
            ->expects($this->never())
            ->method('getCurrentRequest');

        $resolver = new LocaleResolver('foo', false, $requestStack);
        $resolver->resolve();
    }
}
