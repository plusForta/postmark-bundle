<?php


namespace PlusForta\PostmarkBundle\Tests\Value;


use PHPUnit\Framework\TestCase;
use PlusForta\PostmarkBundle\Value\EmailName;

class EmailNameTest extends TestCase
{

    /**
     * @param string $emailNameString
     * @dataProvider getValidEmailName
     */
    public function testValidEmailName(string $emailNameString): void
    {
        $name = EmailName::fromString($emailNameString);
        $this->assertEquals($emailNameString, $name->toString());
    }

    public function getValidEmailName()
    {
        return [
            ['Max Mustermann'],
            ['Max'],
            ['Mit-Bindestrich'],
            ['Mit-Bindestrich'],
        ];
    }

    public function testNullThrowsTypeError()
    {
        $this->expectException(\TypeError::class);
        EmailName::fromString(null);
    }

}