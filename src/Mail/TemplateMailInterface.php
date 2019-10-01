<?php


namespace PlusForta\PostmarkBundle\Mail;


use PlusForta\PostmarkBundle\Value\Email;
use PlusForta\PostmarkBundle\Value\FromEmail;
use PlusForta\PostmarkBundle\Value\TemplateIdentifier;

interface TemplateMailInterface
{
    public function getTemplate(): array;

    public function getTemplateIdOrAlias(): TemplateIdentifier;

    public function getFrom(): ?FromEmail;

    public function getTo(): Email;

    public function getServer(): string;

}