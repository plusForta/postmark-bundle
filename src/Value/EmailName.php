<?php


namespace PlusForta\PostmarkBundle\Value;


final class EmailName
{
    /**
     * @var string
     */
    private $name;

    /**
     * EmailName constructor.
     * @param string $name
     */
    private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @return EmailName
     */
    public static function fromString(string $name): EmailName
    {
        return new self($name);
    }

    public function toString(): string
    {
        return $this->name;
    }

}