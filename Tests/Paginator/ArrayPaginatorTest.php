<?php

namespace Snowcap\CoreBundle\Tests\Paginator;

use Snowcap\CoreBundle\Paginator\ArrayPaginator;

class ArrayPaginatorTest extends \PHPUnit_Framework_TestCase
{
    private $firstNames = array(
        'Pierre', 'Paul', 'Jacques', 'Jean', 'Stijn', 'Kurt', 'John', 'Jack', 'Bernard',
        'Louis', 'Marie', 'Paola', 'Cynthia', 'Kimberly', 'Julien', 'Bernard'
    );



    public function testGetPageCount()
    {

    }

    public function buildSimpleArray()
    {
        return array(
            array('first_name' => 'Pierre', 'last_name' => 'Dupont'),
            array('first_name' => 'Paul', 'last_name' => 'Dupont'),
            array('first_name' => 'Jacques', 'last_name' => 'Dupont'),
            array('first_name' => 'Bernard', 'last_name' => 'Durant'),
            array('first_name' => 'Jules', 'last_name' => 'Durant'),
            array('first_name' => 'Sophie', 'last_name' => 'Latour'),
            array('first_name' => 'Juliette', 'last_name' => 'Latour'),
            array('first_name' => 'Marc', 'last_name' => 'Latour'),
            array('first_name' => 'Solange', 'last_name' => 'Latour'),
            array('first_name' => 'Kim', 'last_name' => 'Vandenberg'),
            array('first_name' => 'Stijn', 'last_name' => 'Vandenberg'),
            array('first_name' => 'Kurt', 'last_name' => 'Vandenberg'),
        );
    }
}