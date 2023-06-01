<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Validator;

use Composer\InstalledVersions;
use Leapt\CoreBundle\Validator\Constraints\Slug;
use Symfony\Component\Validator\Constraints\RegexValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class SlugValidatorTest extends ConstraintValidatorTestCase
{
    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new Slug());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid(): void
    {
        $this->validator->validate('', new Slug());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getValidSlugs
     */
    public function testValidSlug(string $slug): void
    {
        $this->validator->validate($slug, new Slug());
        $this->assertNoViolation();
    }

    public static function getValidSlugs(): iterable
    {
        yield ['dora1'];
        yield ['ca-fait-le-cafe'];
    }

    /**
     * @dataProvider getInvalidSlugs
     */
    public function testInvalidSlugs(string $slug): void
    {
        $validatorVersion = InstalledVersions::getVersion('symfony/validator');

        $constraint = new Slug();

        $this->validator->validate($slug, $constraint);

        $assertion = $this->buildViolation('A slug can only contain lowercase letters, numbers and hyphens.')
            ->setCode('de1e3db3-5ed4-4941-aae4-59f3667cc3a3')
            ->setParameter('{{ value }}', '"' . $slug . '"');

        if (version_compare($validatorVersion, '6.3.0', '>=')) {
            $assertion->setParameter('{{ pattern }}', $constraint->pattern);
        }

        $assertion->assertRaised();
    }

    public static function getInvalidSlugs(): iterable
    {
        yield ['accent-in-café'];
        yield ['some space'];
        yield ['Test-with-uppercase'];
    }

    protected function createValidator(): RegexValidator
    {
        return new RegexValidator();
    }
}
