<?php


namespace PlusForta\PostmarkBundle\Value;


use Webmozart\Assert\Assert;

final class TemplateIdentifier
{
    private function __construct(private ?int $id = null, string $alias = null)
    {
        if ($id === null) {
            Assert::notNull($alias);
            $this->id = $alias;
        }
    }

    public static function fromId(int $id): self
    {
        return new self($id, null);
    }

    public static function fromAlias(string $alias): self
    {
        return new self(null, $alias);
    }

    public function get(): int|string|null
    {
        return $this->id;
    }
}