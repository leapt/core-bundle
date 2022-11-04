<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Validator;

use Leapt\CoreBundle\Validator\Constraints\PasswordStrength;
use Leapt\CoreBundle\Validator\Constraints\PasswordStrengthValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class PasswordStrengthValidatorTest extends ConstraintValidatorTestCase
{
    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new PasswordStrength());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid(): void
    {
        $this->validator->validate('', new PasswordStrength());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getValidPasswords
     */
    public function testValidPassword(string $password): void
    {
        $this->validator->validate($password, new PasswordStrength(['score' => 50, 'min' => 5, 'max' => 255]));
        $this->assertNoViolation();
    }

    public function getValidPasswords(): iterable
    {
        return [
            ['dora1*'],
            ['dora12*'],
            ['Dora12*'],
            ['E=mc^2'],
            ['Doraa12*'],
            ['Dor+a12*'],
            ['DorH+a12*5'],
            ['DorH+a12*5'],
            ['My password is f*cking awesome'],
        ];
    }

    /**
     * @dataProvider getInvalidPasswords
     */
    public function testInvalidPasswords(string $password): void
    {
        $constraint = new PasswordStrength([
            'scoreMessage' => 'scoreMessage',
            'score'        => 50,
        ]);

        $this->validator->validate($password, $constraint);

        $this->buildViolation('scoreMessage')
            ->assertRaised();
    }

    public function getInvalidPasswords(): iterable
    {
        return [
            ['toto'],
            ['dora'],
            ['Dora'],
        ];
    }

    public function testMinPasswords(): void
    {
        $constraint = new PasswordStrength([
            'minMessage' => 'minMessage',
            'score'      => 50,
            'min'        => 5,
        ]);
        $this->validator->validate('abc', $constraint);

        $this->buildViolation('minMessage')
            ->setParameter('{{ limit }}', '5')
            ->assertRaised();
    }

    public function testMaxPasswords(): void
    {
        $constraint = new PasswordStrength([
            'maxMessage' => 'maxMessage',
            'score'      => 50,
            'max'        => 5,
        ]);
        $this->validator->validate('abcdefgh', $constraint);

        $this->buildViolation('maxMessage')
            ->setParameter('{{ limit }}', '5')
            ->assertRaised();
    }

    protected function createValidator(): PasswordStrengthValidator
    {
        return new PasswordStrengthValidator();
    }
}
