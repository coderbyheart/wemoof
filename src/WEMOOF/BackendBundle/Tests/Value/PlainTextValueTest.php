<?php

class PlainTextValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ItShouldCastToString()
    {
        $value = new \WEMOOF\BackendBundle\Value\PlainTextValue('Dies ist ein einfacher Text.');
        $this->assertEquals(
            'Dies ist ein einfacher Text.',
            (string)$value,
            "Casting a plain text value to a string should return the string version of the plain text"
        );
    }

    /**
     * @test
     */
    public function ItShouldStripHTML()
    {
        $value = new \WEMOOF\BackendBundle\Value\PlainTextValue('Dies ist ein Text, in dem <a href="http://daringfireball.net/projects/markdown/">Markdown</a> verwendet werden darf.');
        $this->assertEquals(
            'Dies ist ein Text, in dem Markdown verwendet werden darf.',
            (string)$value,
            "Creating a plain text value with HTML markup should create a value object with the markup removed."
        );
    }
}
