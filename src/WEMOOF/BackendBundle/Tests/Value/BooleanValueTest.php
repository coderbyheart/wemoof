<?php

class BooleanValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ItShouldCastToString()
    {
        $value = new \WEMOOF\BackendBundle\Value\BooleanValue(true);
        $this->assertEquals(
            '1',
            (string)$value,
            "Casting a Boolean value to a string should return the string version of the Boolean"
        );
    }

    /**
     * @test
     */
    public function ItShouldParseAString()
    {
        $value = \WEMOOF\BackendBundle\Value\BooleanValue::parse('1');
        $this->assertInstanceOf('\WEMOOF\BackendBundle\Value\BooleanValue', $value, 'Parsing a Boolean value should return the value object.');
    }


    /**
     * @test
     */
    public function ItShouldReturnBoolean()
    {
        $true = \WEMOOF\BackendBundle\Value\BooleanValue::parse('1');
        $this->assertEquals(true, $true->getBoolean());
        $false = \WEMOOF\BackendBundle\Value\BooleanValue::parse('0');
        $this->assertEquals(false, $false->getBoolean());
    }

    /**
     * @test
     * @expectedException \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function ItShouldThrowExceptionOnInvalidString()
    {
        new \WEMOOF\BackendBundle\Value\BooleanValue('a');
        $this->fail('Parsing an invalid email address should throw an exception.');
    }

    /**
     * @test
     */
    public function ItShouldBeCreateabledFalse()
    {
        $this->assertFalse(\WEMOOF\BackendBundle\Value\BooleanValue::false()->getBoolean());
    }

    /**
     * @test
     */
    public function ItShouldBeCreateabledTrue()
    {
        $this->assertTrue(\WEMOOF\BackendBundle\Value\BooleanValue::true()->getBoolean());
    }
}