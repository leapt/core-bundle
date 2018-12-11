<?php

namespace Leapt\CoreBundle\Tests\Validator;

use Leapt\CoreBundle\Validator\Constraints\PasswordStrength;
use Leapt\CoreBundle\Validator\Constraints\PasswordStrengthValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;

final class PasswordStrengthValidatorTest extends TestCase
{
    private $context;
    private $validator;

    protected function setUp()
    {
        $this->context = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->validator = new PasswordStrengthValidator();
        $this->validator->initialize($this->context);
    }

    protected function tearDown()
    {
        $this->context = null;
        $this->validator = null;
    }

    public function testNullIsValid()
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate(null, new PasswordStrength());
    }

    public function testEmptyStringIsValid()
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate('', new PasswordStrength());
    }

    /**
     * @dataProvider getValidPasswords
     */
    public function testValidPassword($password)
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate($password, new PasswordStrength(array('score' => 50, 'min' => 5, 'max' => 255)));
    }

    public function getValidPasswords()
    {
        return array(
            array('dora1*'),
            array('dora12*'),
            array('Dora12*'),
            array('E=mc^2'),
            array('Doraa12*'),
            array('Dor+a12*'),
            array('DorH+a12*5'),
            array('DorH+a12*5'),
            array('My password is f*cking awesome'),
        );
    }

    /**
     * @dataProvider getInvalidPasswords
     */
    public function testInvalidPasswords($password)
    {
        $constraint = new PasswordStrength(array(
            'scoreMessage' => 'scoreMessage',
            'score' => 50,
        ));

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with('scoreMessage');

        $this->validator->validate($password, $constraint);
    }

    public function getInvalidPasswords()
    {
        return array(
            array('toto'),
            array('dora'),
            array('Dora'),
        );
    }

    public function testMinPasswords()
    {
        $constraint = new PasswordStrength(array(
            'minMessage' => 'minMessage',
            'score' => 50,
            'min' => 5,
        ));

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with('minMessage');

        $this->validator->validate('abc', $constraint);
    }

    public function testMaxPasswords()
    {
        $constraint = new PasswordStrength(array(
            'maxMessage' => 'maxMessage',
            'score' => 50,
            'max' => 5,
        ));

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with('maxMessage');

        $this->validator->validate('abcdefgh', $constraint);
    }
}
