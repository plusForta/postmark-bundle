<?php


namespace PlusForta\PostmarkBundle\Tests\Value;


use PHPUnit\Framework\TestCase;
use PlusForta\PostmarkBundle\Value\Email;
use PlusForta\PostmarkBundle\Value\EmailName;
use PlusForta\PostmarkBundle\Value\FromEmail;

class FromEmailTest extends TestCase
{

    /**
     * @param  $email
     * @dataProvider getEmailProvider
     */
    public function testFromEmail($email)
    {
        $fromEmail = FromEmail::fromEmail($email);
        $this->assertEquals($email->toString(), $fromEmail->toString());
    }

    private function getValidEmailStrings()
    {
        return [
            'somthing@example.com',
            'somthing.else@sub.domain.de',
            'somthing-else@sub.domain.de',
            'somthing_else@sub.domain.de',
            'marc+TEST@runkel.org',
        ];
    }

    public function getValidEmailStringProvider()
    {
        return array_map(function ($email) {
            return [$email];
        }, $this->getValidEmailStrings());
    }

    public function getEmailProvider()
    {
        try {
            return array_map(function ($email) {
                return [Email::fromString($email)];
            }, $this->getValidEmailStrings());
        } catch (\InvalidArgumentException $exception) {
        }
    }

    /**
     * @dataProvider getEmailAndNameProvider
     */
    public function testFromEmailAndName(Email $email, EmailName $name)
    {

        $fromEmail = FromEmail::fromEmailAndName($email, $name);
        $expectedEmail = sprintf('%s <%s>', $name->toString(), $email->toString());
        $this->assertEquals($expectedEmail, $fromEmail->toString());
    }

    /**
     * @param $emailString
     * @dataProvider getValidEmailStringProvider
     */
    public function testFromStringWithValidEmails(string $emailString)
    {
        $fromEmail = FromEmail::fromString($emailString);
        $this->assertEquals($emailString, $fromEmail->toString());
    }

    public function getEmailAndNameProvider()
    {

        $emails = array_map(function ($email) {
            return Email::fromString($email);
        }, $this->getValidEmailStrings());

        $names = array_map(function ($name) {
            return EmailName::fromString($name);
        }, [
            'Max Mustermann',
            'Max',
            'Mit-Bindestrich',
            'Mit-Bindestrich',
        ]);

        $emailsAndNames = [];
        foreach ($emails as $email) {
            foreach ($names as $name) {
                $emailsAndNames[] = [
                  $email,
                  $name,
                ];
            }
        }

        return $emailsAndNames;
    }


}