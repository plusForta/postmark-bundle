<?php

namespace PlusForta\PostmarkBundle\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PlusForta\PostmarkBundle\Mail\KFKundenanfrageTemplateMail;
use PlusForta\PostmarkBundle\PlusFortaPostmarkClient;
use PlusForta\PostmarkBundle\Value\FromEmail;
use Postmark\PostmarkClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PlusFortaPostmarClientTest extends TestCase
{

    /**
     * @var PlusFortaPostmarkClient
     */
    private $postmarkClient;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function testInitialization()
    {
        $this->givenIHaveAContainer();
        $this->whenIInitializeThePostmarkClient();
        $this->thenIExpectItToHaveTheCorrectType();
    }

    public function testInvalidOverrideEmail()
    {
        $this->givenIHaveAContainerWithConfiguration(['overrides' => ['to' => ['email' => 'override']]]);
        $this->thenIExpectAnException(
            InvalidArgumentException::class,
            'Expected a value to be a valid e-mail address. Got "override"'
        );
        $this->whenIInitializeThePostmarkClient();
    }

    public function testInvalidDefaultFromEmail()
    {
        $this->givenIHaveAContainerWithConfiguration(['defaults' => ['from' => ['email' => 'default']]]);
        $this->thenIExpectAnException(
            InvalidArgumentException::class,
            'Expected a value to be a valid e-mail address. Got "default"'
        );
        $this->whenIInitializeThePostmarkClient();
    }

    public function testOverrideEmailIsUsedWhenRecipientIsGiven()
    {
        $overrideTo = 'override@email.de';
        $this->givenIHaveAContainerWithConfiguration(['overrides' => ['to' => ['email' => $overrideTo]]]);
        $this->IExpectRecipientToBe($overrideTo);
        $this->whenIsendAnEmailTo('recipieant@email.de');
    }

    public function testDefaultFromEmailIsUsedWhenNoSenderIsGiven()
    {
        $from = 'default@email.de';
        $this->givenIHaveAContainerWithConfiguration(['defaults' => ['from' => ['email' => $from]]]);
        $this->IExpectSenderToBe($from);
        $this->whenIsendAnEmailTo('recipieant@email.de');
    }

    public function testDefaultFromEmailIsNotUsedWhenSenderIsGiven()
    {
        $defaultFrom = 'default@email.de';
        $from = 'newSender@email.de';
        $this->givenIHaveAContainerWithConfiguration(['defaults' => ['from' => ['email' => $defaultFrom]]]);
        $this->IExpectSenderToBe($from);
        $this->whenIsendAnEmailFrom($from, 'recipient@email.de');
    }

    private function givenIHaveAContainerWithConfiguration($config = []): void
    {
        $config = array_merge([
            'servers' => [
                [
                    'name' => 'plusforta.de',
                    'api_key' => 'dummyApiKey'
                ]
            ]
        ], $config);
        $kernel = new PostmarkTestingKernel($config);
        $kernel->boot();
        $this->container = $kernel->getContainer();
    }

    private function givenIHaveAContainer()
    {
        $this->givenIHaveAContainerWithConfiguration();
    }

    private function thenIExpectAnException(string $class, string $string)
    {
        $this->expectException($class);
        $this->expectExceptionMessage($string);
    }

    private function whenIsendAnEmailTo(string $to = null)
    {
        $email = new KFKundenanfrageTemplateMail();
        if ($to) {
            $email->to($to);
        }

        $this->sendEmail($email);
    }

    private function whenIsendAnEmailFrom(string $from, string $to)
    {
        $email = new KFKundenanfrageTemplateMail();
        $email->from(FromEmail::fromString($from));
        $email->to($to);

        $this->sendEmail($email);
    }

    private function IExpectRecipientToBe(string $overrideTo)
    {
        $mock = $this->getMockBuilder(PostmarkClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['sendEmailWithTemplate'])
            ->getMock();
        $mock->expects($this->once())
            ->method('sendEmailWithTemplate')
            ->with(
                $this->anything(),
                $this->equalTo($overrideTo)
            );
        $mockFactory = new MockPostmarkClientFactory($mock);
        $this->container->set('postmark.client_factory', $mockFactory);
    }

    private function IExpectSenderToBe(string $from)
    {
        $mock = $this->getMockBuilder(PostmarkClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['sendEmailWithTemplate'])
            ->getMock();
        $mock->expects($this->once())
            ->method('sendEmailWithTemplate')
            ->with($this->equalTo($from));
        $mockFactory = new MockPostmarkClientFactory($mock);
        $this->container->set('postmark.client_factory', $mockFactory);
    }

    private function whenIInitializeThePostmarkClient()
    {
        $this->postmarkClient = $this->container->get(PlusFortaPostmarkClient::class);
    }

    private function thenIExpectItToHaveTheCorrectType()
    {
        $this->assertEquals(PlusFortaPostmarkClient::class, get_class($this->postmarkClient));
    }

    /**
     * @param KFKundenanfrageTemplateMail $email
     * @throws \Exception
     */
    private function sendEmail(KFKundenanfrageTemplateMail $email): void
    {
        $this->postmarkClient = $this->container->get(PlusFortaPostmarkClient::class);
        $this->postmarkClient->sendMail($email);
    }


}