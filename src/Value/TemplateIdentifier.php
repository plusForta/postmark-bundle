<?php


namespace PlusForta\PostmarkBundle\Value;


use Webmozart\Assert\Assert;

final class TemplateIdentifier
{
    /**
     * @var int|string
     */
    private $id;


    private function __construct(int $id = null, string $alias = null)
    {
        if ($id === null) {
            Assert::notNull($alias);
            $this->id = $alias;
        } else {
            $this->id = $id;
        }

    }

    /**
     * @param int $id
     * @return TemplateIdentifier
     */
    public static function fromId(int $id): TemplateIdentifier
    {
        return new self($id, null);
    }


    public static function fromAlias(string $alias)
    {
        return new self(null, $alias);
    }

    /**
     * @return int|string
     */
    public function get()
    {
        return $this->id;
    }
}