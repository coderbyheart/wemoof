<?php

class SchemeAndHostValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ItShouldCastToString()
    {
        $url = new \WEMOOF\BackendBundle\Value\SchemeAndHostValue('http://tckr.cc');
        $this->assertEquals(
            'http://tckr.cc',
            (string)$url,
            "Casting an url value to a string should return the string version of the url"
        );
    }

    /**
     * @test
     */
    public function ItShouldParseAString()
    {
        $url = \WEMOOF\BackendBundle\Value\SchemeAndHostValue::parse('http://tckr.cc');
        $this->assertInstanceOf('\WEMOOF\BackendBundle\Value\SchemeAndHostValue', $url, 'Parsing an url value should return the value object.');
    }

    /**
     * @test
     * @expectedException \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function ItShouldThrowExceptionOnInvalidString()
    {
        $url = new \WEMOOF\BackendBundle\Value\SchemeAndHostValue('abc');
        $this->fail('Parsing an invalid url should throw an exception.');
    }

    /**
     * @test
     * @expectedException \WEMOOF\BackendBundle\Exception\ValueException
     * @depend ItShouldThrowExceptionOnInvalidString
     */
    public function ItShouldThrowExceptionOnURLs()
    {
        $url = new \WEMOOF\BackendBundle\Value\SchemeAndHostValue('http://tckr.cc/path');
        $this->fail('Parsing an invalid url should throw an exception.');
    }
}