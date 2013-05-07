<?php

class MarkdownTextValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ItShouldCastToString()
    {
        $value = new \WEMOOF\BackendBundle\Value\MarkdownTextValue('Dies ist ein Text, in dem [Markdown](http://daringfireball.net/projects/markdown/) verwendet werden darf.');
        $this->assertEquals(
            'Dies ist ein Text, in dem [Markdown](http://daringfireball.net/projects/markdown/) verwendet werden darf.',
            (string)$value,
            "Casting a markdown text value to a string should return the string version of the markdown text"
        );
    }

    /**
     * @test
     * @expectedException \WEMOOF\BackendBundle\Exception\ValueException
     */
    public function ItShouldThrowExceptionOnInvalidString()
    {
        new \WEMOOF\BackendBundle\Value\MarkdownTextValue('Dies ist ein Text, in dem <a href="http://daringfireball.net/projects/markdown/">Markdown</a> verwendet werden darf.');
        $this->fail('Parsing an invalid markdown text should throw an exception.');
    }
}