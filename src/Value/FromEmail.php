<?php


namespace PlusForta\PostmarkBundle\Value;


final class FromEmail
{
    /**
     * @var Email
     */
    private $email;

    /**
     * @var ?EmailName
     */
    private $name;

    /**
     * FromEmail constructor.
     * @param Email $email
     * @param EmailName|null $name
     */
    private function __construct(Email $email, EmailName $name = null)
    {

        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @param string $email
     * @return FromEmail
     */
    public static function fromString(string $email): FromEmail
    {
        return new self(Email::fromString($email));
    }

    /**
     * @param Email $email
     * @return FromEmail
     */
    public static function fromEmail(Email $email): FromEmail
    {
        return new self($email);
    }

    /**
     * @param Email $email
     * @param EmailName $name
     * @return FromEmail
     */
    public static function fromEmailAndName(Email $email, EmailName $name): FromEmail
    {
        return new self($email, $name);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        if (null === $this->name) {
            return $this->email->toString();
        }

        return sprintf('%s <%s>', $this->name->toString(), $this->email->toString());
    }

}