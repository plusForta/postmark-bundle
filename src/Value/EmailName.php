<?php


namespace PlusForta\PostmarkBundle\Value;


final class EmailName
{
    private function __construct(private string $name)
    {
    }

    public static function fromString(string $name): EmailName
    {
        return new self($name);
    }

    public function toString(): string
    {
        return $this->name;
    }

}