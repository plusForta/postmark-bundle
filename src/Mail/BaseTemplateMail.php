<?php


namespace PlusForta\PostmarkBundle\Mail;


use PlusForta\PostmarkBundle\Exceptions\TemplateMailException;
use PlusForta\PostmarkBundle\Value\Email;
use PlusForta\PostmarkBundle\Value\FromEmail;
use PlusForta\PostmarkBundle\Value\TemplateIdentifier;

abstract class BaseTemplateMail implements TemplateMailInterface
{
    protected const SERVER_ID = null;

    protected const TEMPLATE_ID = null;

    protected const TEMPLATE_ALIAS = null;

    protected const PARAM_NOT_FOUND_MESSAGE = 'Parameter "%s" ist not defined for email of type "%s".';

    /**
     * @var array<int, string>
     */
    protected static $parameters;

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var ?FromEmail
     */
    protected $from = null;

    /**
     * @var Email
     */
    protected $to;

    /**
     * @return array<string, string>
     */
    abstract function getTemplate(): array;

    /**
     * @param string $name
     * @return mixed|string
     * @throws TemplateMailException
     * 
     */
    public function __get(string $name)
    {
        if (!in_array($name, static::$parameters)) {
            throw new TemplateMailException(
                sprintf(self::PARAM_NOT_FOUND_MESSAGE, $name, static::class)
            );
        }

        if (isset($this->values[$name])) {
            return $this->values[$name];
        }

        return '';
    }

    /**
     * @throws TemplateMailException
     */
    public function __set(string $name, mixed $value)
    {
        if (!in_array($name, static::$parameters)) {
            throw new TemplateMailException(
                sprintf(self::PARAM_NOT_FOUND_MESSAGE, $name, static::class)
            );

        }

        $this->values[$name] = $value;
    }

    /**
     * @throws TemplateMailException
     */
    public function __isset(string $name): bool
    {
        if (!in_array($name, static::$parameters, true)) {
            throw new TemplateMailException(
                sprintf(self::PARAM_NOT_FOUND_MESSAGE, $name, static::class)
            );

        }

        return isset($this->values[$name]);
    }

    public function from(FromEmail $from): void
    {
        $this->from = $from;
    }

    public function getFrom(): ?FromEmail
    {
        return $this->from;
    }

    public function to(string $to): void
    {
        $this->to = Email::fromString($to);
    }

    public function getTo(): Email
    {
        return $this->to;
    }

    /**
     * @throws TemplateMailException
     */
    public function getTemplateIdOrAlias(): TemplateIdentifier
    {
        if (static::TEMPLATE_ID) {
            return TemplateIdentifier::fromId((int) static::TEMPLATE_ID);
        }

        if (static::TEMPLATE_ALIAS) {
            return TemplateIdentifier::fromAlias(static::TEMPLATE_ALIAS);
        }

        throw new TemplateMailException(
            sprintf('No TEMPLATE_ID or TEMPLATE_ALIAS specified for template email of class "%s"', static::class)
        );
    }


    /**
     * @return string
     * @throws TemplateMailException
     */
    public function getServer(): string
    {
        if (static::SERVER_ID) {
            return static::SERVER_ID;
        }

        throw new TemplateMailException(
            sprintf('No SERVER_ID specified for template email of class "%s"', static::class)
        );
    }

}