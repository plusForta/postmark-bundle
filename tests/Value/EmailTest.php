<?php


namespace PlusForta\PostmarkBundle\Tests\Value;


use PHPUnit\Framework\TestCase;
use PlusForta\PostmarkBundle\Value\Email;

class EmailTest extends TestCase
{

    /**
     * @param string $emailString
     * @dataProvider getValidEmails
     */
    public function testValidEmailString($emailString)
    {
        $email = Email::fromString($emailString);
        $this->assertEquals($emailString, $email->toString());
    }

    /**
     * @return array<string>
     */
    public function getValidEmails(): array
    {
        return [
            ['somthing@example.com'],
            ['somthing.else@sub.domain.de'],
            ['somthing-else@sub.domain.de'],
            ['somthing_else@sub.domain.de'],
            ['marc+TEST@runkel.org'],
        ];
    }


    /**
     * @param $emailString
     * @dataProvider getInvalidEmails
     */
    public function testInvalidEmailStringThrowsException($emailString): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Email::fromString($emailString);
    }

    /**
     * @return array<string>
     */
    public function getInvalidEmails(): array
    {
        return [
            ['somthingexample.com'],
            ['s@∆omthingexample.com'],
            ['somthing.elsesub.domain.de@'],
            ['@somthing.else@sub.domain.de'],
            [' marc+TEST@runkel.org'],
            ['marc+TEST@runkel.org '],
            ['mar c+TEST@runkel.org '],
            ['mar c+TEST@runkel.org '],
        ];
    }

    public function testNullThrowsTypeError(): void
    {
        $this->expectException(\TypeError::class);
        Email::fromString(null);
    }

}