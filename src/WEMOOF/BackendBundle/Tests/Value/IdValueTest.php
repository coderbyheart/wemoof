<?php

class IdValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ItShouldCastToString()
    {
        $id = new \WEMOOF\BackendBundle\Value\IdValue(5);
        $this->assertEquals(
            5,
            (string)$id,
            "Casting an id value to a string should return the string version of the id"
        );
    }

    /**
     * @test
     */
    public function ItShouldParseAString()
    {
        $id = \WEMOOF\BackendBundle\Value\IdValue::parse(5);
        $this->assertInstanceOf('\WEMOOF\BackendBundle\Value\IdValue', $id, 'Parsing an id value should return the value object.');
    }

    /**
     * @test
     * @expectedException \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function ItShouldThrowExceptionOnInvalidString()
    {
        $id = new \WEMOOF\BackendBundle\Value\IdValue('abc');
        $this->fail('Parsing an invalid id should throw an exception.');
    }
}