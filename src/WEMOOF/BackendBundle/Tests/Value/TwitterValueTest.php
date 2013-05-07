<?php

class TwitterValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ItShouldCastToString()
    {
        $value = new \WEMOOF\BackendBundle\Value\TwitterValue('@markustacker');
        $this->assertEquals(
            '@markustacker',
            (string)$value,
            "Casting an twitter value to a string should return the string version of the twitter account"
        );
    }

    /**
     * @test
     */
    public function ItShouldParseAString()
    {
        $value = \WEMOOF\BackendBundle\Value\TwitterValue::parse('@markustacker');
        $this->assertInstanceOf('\WEMOOF\BackendBundle\Value\TwitterValue', $value, 'Parsing an twitter value should return the value object.');
    }

    /**
     * @test
     * @expectedException \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function ItShouldThrowExceptionOnInvalidString()
    {
        new \WEMOOF\BackendBundle\Value\TwitterValue('abc');
        $this->fail('Parsing an invalid twitter address should throw an exception.');
    }
}