<?php


namespace PlusForta\PostmarkBundle\Value;


use Webmozart\Assert\Assert;

class Email
{
    private function __construct(private string $email)
    {
    }

    public static function fromString(string $email): Email
    {
        return new static($email);
    }

    public function toString(): string
    {
        return $this->email;
    }

}
