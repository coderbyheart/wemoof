<?php

class EmailValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ItShouldCastToString()
    {
        $email = new \WEMOOF\BackendBundle\Value\EmailValue('m@tckr.cc');
        $this->assertEquals(
            'm@tckr.cc',
            (string)$email,
            "Casting an email value to a string should return the string version of the email"
        );
    }

    /**
     * @test
     */
    public function ItShouldParseAString()
    {
        $email = \WEMOOF\BackendBundle\Value\EmailValue::parse('m@tckr.cc');
        $this->assertInstanceOf('\WEMOOF\BackendBundle\Value\EmailValue', $email, 'Parsing an email value should return the value object.');
    }

    /**
     * @test
     * @expectedException \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function ItShouldThrowExceptionOnInvalidString()
    {
        $email = new \WEMOOF\BackendBundle\Value\EmailValue('abc');
        $this->fail('Parsing an invalid email address should throw an exception.');
    }
}