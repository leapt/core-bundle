<?php

namespace Leapt\CoreBundle\Tests\Util;

use Leapt\CoreBundle\Util\PasswordStrengthChecker;
use PHPUnit\Framework\TestCase;

/**
 * Test the password strength checker utility
 */
class PasswordStrengthCheckerTest extends TestCase
{
    public function testStrength()
    {
        $checker = new PasswordStrengthChecker();

        $this->assertEquals(0, $checker->getStrength(''), 'Strength should be equal to 0 when an empty string is provided');
        $this->assertEquals(0, $checker->getStrength('toto', 'toto'), 'Strength should be equal to 0 when the password is the same as the username');
        $this->assertLessThan(10, $checker->getStrength('toto'), 'Strength should be less than 10 when a string with 4 chars and two repetitive syllables is provided');
        $this->assertLessThan(10, $checker->getStrength('dora'), 'Strength should be less than 10 when a string with 4 chars and no repetitive syllable is provided');
        $this->assertLessThan(30, $checker->getStrength('Dora'), 'Strength should be less than 30 when a string with 4 chars and one capital letter is provided');
        $this->assertLessThan(50, $checker->getStrength('dora1'), 'Strength should be less than 50 when a string with 4 chars and 1 digit is provided');
        $this->assertLessThan(50, $checker->getStrength('dora12'), 'Strength should be less than 50 when a string with 4 chars and 2 digits is provided');
        $this->assertLessThan(50, $checker->getStrength('dora*'), 'Strength should be less than 50 when a string with 4 chars and 1 symbol is provided');
        $this->assertLessThan(80, $checker->getStrength('dora1*'), 'Strength should be less than 80 when a string with 4 chars, 1 digit and 1 symbol is provided');
        $this->assertLessThan(80, $checker->getStrength('dora12*'), 'Strength should be less than 90 when a string with 4 chars, 1 digit and 1 symbol is provided');
        $this->assertLessThan(90, $checker->getStrength('Dora12*'), 'Strength should be less than 90 when a string with 4 chars, one capital letter, 1 digit and 1 symbol is provided');
        $this->assertLessThan(90, $checker->getStrength('E=mc^2'), 'Strength should be less than 90 when we use the forumla E=mc^2');
        $this->assertLessThan(90, $checker->getStrength('Doraa12*'), 'Strength should be less than 90 when a string with 5 chars, one capital letter, 1 digit and 1 symbol is provided');
        $this->assertGreaterThan(90, $checker->getStrength('Dor+a12*'), 'Strength should be greater than 90 when a string with 4 chars, one capital letter, 1 digit and 2 symbols is provided');
        $this->assertGreaterThan(90, $checker->getStrength('DorH+a12*5'), 'Strength should be greater than 90 when a string with 5 chars, 2 capital letters, 3 digits and 2 symbols is provided');
        $this->assertEquals(100, $checker->getStrength('DorH+a12*5'), 'Strength should be equal to 100 when a string with 5 chars, 2 capital letters, 3 digits and 2 symbols is provided');
        $this->assertEquals(100, $checker->getStrength('My password is f*cking awesome'), 'Strength should be equal to 100 when it is a long string');
    }
}
