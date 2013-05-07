<?php

class NameValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ItShouldCastToString()
    {
        $value = new \WEMOOF\BackendBundle\Value\NameValue('Markus');
        $this->assertEquals(
            'Markus',
            (string)$value,
            "Casting a name value to a string should return the string version of the name"
        );
    }

    /**
     * @test
     */
    public function ItShouldParseAString()
    {
        $value = \WEMOOF\BackendBundle\Value\NameValue::parse('Markus');
        $this->assertInstanceOf('\WEMOOF\BackendBundle\Value\NameValue', $value, 'Parsing a name value should return the value object.');
    }

    /**
     * @test
     * @expectedException \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function ItShouldThrowExceptionOnInvalidString()
    {
        new \WEMOOF\BackendBundle\Value\NameValue('<strong>Markus');
        $this->fail('Parsing an invalid email address should throw an exception.');
    }
}