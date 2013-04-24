<?php

class TemplateIdentifierValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ItShouldCastToString()
    {
        $templateIdentifier = new \WEMOOF\BackendBundle\Value\TemplateIdentifierValue('HelloBundle:Hello:templateIdentifier.txt.twig');
        $this->assertEquals(
            'HelloBundle:Hello:templateIdentifier.txt.twig',
            (string)$templateIdentifier,
            "Casting an templateIdentifier value to a string should return the string version of the templateIdentifier"
        );
    }

    /**
     * @test
     */
    public function ItShouldParseAString()
    {
        $templateIdentifier = \WEMOOF\BackendBundle\Value\TemplateIdentifierValue::parse('HelloBundle:Hello:templateIdentifier.txt.twig');
        $this->assertInstanceOf('\WEMOOF\BackendBundle\Value\TemplateIdentifierValue', $templateIdentifier, 'Parsing an templateIdentifier value should return the value object.');
    }
}