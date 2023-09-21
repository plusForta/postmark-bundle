<?php


namespace PlusForta\PostmarkBundle\Value;


final class FromEmail
{
    private function __construct(private Email $email, private ?EmailName $name = null)
    {
    }

    public static function fromString(string $email): self
    {
        return new self(Email::fromString($email));
    }

    public static function fromEmail(Email $email): self
    {
        return new self($email);
    }

    public static function fromEmailAndName(Email $email, EmailName $name): self
    {
        return new self($email, $name);
    }

    public function toString(): string
    {
        if (null === $this->name) {
            return $this->email->toString();
        }

        return sprintf('%s <%s>', $this->name->toString(), $this->email->toString());
    }

}