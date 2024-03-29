<?php

namespace PlusForta\PostmarkBundle;

use PlusForta\PostmarkBundle\Mail\TemplateMailInterface;
use PlusForta\PostmarkBundle\Value\Email;
use PlusForta\PostmarkBundle\Value\EmailName;
use PlusForta\PostmarkBundle\Value\FromEmail;
use PlusForta\PostmarkBundle\Value\TemplateIdentifier;
use Postmark\Models\DynamicResponseModel;
use Postmark\PostmarkClient;
use Psr\Log\LoggerInterface;

class PlusFortaPostmarkClient
{
    /**
     * @var ?Email
     */
    private $overrideTo;
    /**
     * @var FromEmail
     */
    private $defaultFrom;
    /**
     * @var array<string, string>
     */
    private $servers;
    /**
     * @var bool
     */
    private $disableDelivery;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var PlusFortaPostmarkClientFactoryInterface
     */
    private $clientFactory;

    /**
     * PlusFortaPostmarkClient constructor.
     * @param LoggerInterface $logger
     * @param PlusFortaPostmarkClientFactoryInterface $clientFactory
     * @param array<string, string> $servers
     * @param string $defaultFrom
     * @param string|null $defaultFromName
     * @param string $overrideTo
     * @param bool $disableDelivery
     */
    public function __construct(
        LoggerInterface $logger,
        PlusFortaPostmarkClientFactoryInterface $clientFactory,
        array $servers,
        string $defaultFrom,
        string $defaultFromName = null,
        string $overrideTo = null,
        bool $disableDelivery = false
    ) {
        $this->servers = $servers;

        if ($defaultFromName) {
            $emailName = EmailName::fromString($defaultFromName);
            $email = Email::fromString($defaultFrom);
            $from = FromEmail::fromEmailAndName($email, $emailName);
        } else {
            $from = FromEmail::fromString($defaultFrom);
        }
        $this->defaultFrom = $from;
        $this->overrideTo = $overrideTo ? Email::fromString($overrideTo) : null;
        $this->disableDelivery = $disableDelivery;
        $this->logger = $logger;

        $logger->debug(
            sprintf(
                'Set up %s with default_from="%s" and override_to="%s". Delivery is %sdisabled.',
                self::class,
                $this->defaultFrom->toString(),
                $this->overrideTo ? $this->overrideTo->toString() : '',
                $this->disableDelivery ? '' : 'not '
            )
        );
        $this->clientFactory = $clientFactory;
    }


    /**
     * @param TemplateMailInterface $email
     * @throws \Exception
     */
    public function sendMail(TemplateMailInterface $email): DynamicResponseModel
    {
        $this->logger->debug(sprintf('Called sendMail with email of type %s.', get_class($email)));
        $from = $email->getFrom();
        $to = $email->getTo();
        $templateId = $email->getTemplateIdOrAlias();
        $templateModel = $email->getTemplate();
        $serverId = $email->getServer();
        return $this->sendEmailWithTemplate($to, $templateId, $templateModel, $serverId, $from);
    }

    /**
     * @param Email $to
     * @param TemplateIdentifier $templateId
     * @param array $templateModel
     * @param string $serverId
     * @param FromEmail|null $from
     * @throws \Exception
     */
    public function sendEmailWithTemplate(
        $to,
        $templateId,
        $templateModel,
        $serverId,
        $from = null
    ): DynamicResponseModel {
        $templateIdentifier = $templateId->get();
        $this->logger->debug(sprintf('Called sendEmailWithTemplate with templateId %s.', $templateIdentifier));


        if (null !== $this->overrideTo) {
            /*
             * Overrides recipient (useful for test or dev environment)
             * can be set in config: overrides.to_email
             */
            $to = $this->overrideTo;
            $this->logger->warning(sprintf('Overridden $to with %s', $to->toString()));
        }

        if (null === $from) {
            $from = $this->defaultFrom;
            $this->logger->debug(sprintf('Used default $from with %s', $from->toString()));
        }


        $this->logger->debug(\json_encode($templateModel));
        if ($this->disableDelivery) {
            $this->logger->warning('Email Delivery is disabled!');
            return new DynamicResponseModel(['errorcode' => 0, 'message' => 'Email Delivery is disabled!']);
        }

        $apiKey = $this->servers[$serverId];
        $client = $this->clientFactory->createWithApiKey($apiKey);
        $recipient = $to->toString();
        $response = $client->sendEmailWithTemplate($from->toString(), $recipient, $templateIdentifier, $templateModel);
        $this->logger->info(sprintf('Sent email with template id "%s" to %s', $templateIdentifier, $recipient));

        return $response;
    }

    public function sendEmail(
        $to,
        $subject,
        $html,
        $text,
        $serverId,
        $from = null
    ): DynamicResponseModel {
        $this->logger->debug(sprintf('Called sendEmail with subject: "%s".', $subject));


        if (null !== $this->overrideTo) {
            /*
             * Overrides recipient (useful for test or dev environment)
             * can be set in config: overrides.to_email
             */
            $to = $this->overrideTo;
            $this->logger->warning(sprintf('Overridden $to with %s', $to->toString()));
        }

        if (null === $from) {
            $from = $this->defaultFrom;
            $this->logger->debug(sprintf('Used default $from with %s', $from->toString()));
        }


        if ($this->disableDelivery) {
            $this->logger->warning('Email Delivery is disabled!');
            return new DynamicResponseModel(['errorcode' => 0, 'message' => 'Email Delivery is disabled!']);
        }

        $apiKey = $this->servers[$serverId];
        $client = $this->clientFactory->createWithApiKey($apiKey);
        $recipient = $to->toString();
        $response = $client->sendEmail($from->toString(), $recipient, $subject, $html, $text);
        $this->logger->info(sprintf('Sent email to %s', $recipient));

        return $response;
    }


    public function getBounces(
        string $serverId,
        int $count = 100,
        int $offset = 0,
        ?\DateTimeImmutable $fromDate = null,
        ?\DateTimeImmutable $toDate = null
    ): DynamicResponseModel {
        $type = null;
        $inactive = null;
        $emailFilter = null;
        $tag = null;
        $messageID = null;

        $apiKey = $this->servers[$serverId];
        $client = $this->clientFactory->createWithApiKey($apiKey);
        $fromdate = ($fromDate instanceof \DateTimeImmutable) ? $fromDate->format('Y-m-d') : null;
        $todate = ($toDate instanceof \DateTimeImmutable) ? $toDate->format('Y-m-d') : null;

        return $client->getBounces(
            $count,
            $offset,
            $type,
            $inactive,
            $emailFilter,
            $tag,
            $messageID,
            $fromdate,
            $todate
        );
    }
}
