<?php


namespace PlusForta\PostmarkBundle\Value;


use Webmozart\Assert\Assert;

class Email
{
    /**
     * @var string
     */
    private $email;

    private function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param string $email
     * @return Email
     */
    public static function fromString(string $email): Email
    {
        Assert::notNull($email);
        return new static($email);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->email;
    }

}