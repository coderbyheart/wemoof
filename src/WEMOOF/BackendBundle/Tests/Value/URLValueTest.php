<?php

class URLValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ItShouldCastToString()
    {
        $url = new \WEMOOF\BackendBundle\Value\URLValue('http://tckr.cc/path');
        $this->assertEquals(
            'http://tckr.cc/path',
            (string)$url,
            "Casting an url value to a string should return the string version of the url"
        );
    }

    /**
     * @test
     */
    public function ItShouldParseAString()
    {
        $url = \WEMOOF\BackendBundle\Value\URLValue::parse('http://tckr.cc/path');
        $this->assertInstanceOf('\WEMOOF\BackendBundle\Value\URLValue', $url, 'Parsing an url value should return the value object.');
    }

    /**
     * @test
     * @expectedException \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function ItShouldThrowExceptionOnInvalidString()
    {
        $url = new \WEMOOF\BackendBundle\Value\URLValue('abc');
        $this->fail('Parsing an invalid url should throw an exception.');
    }
}