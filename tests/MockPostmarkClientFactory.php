<?php


namespace PlusForta\PostmarkBundle\Tests;


use PHPUnit\Framework\MockObject\MockObject;
use PlusForta\PostmarkBundle\PlusFortaPostmarkClientFactoryInterface;
use Postmark\PostmarkClient;

class MockPostmarkClientFactory implements PlusFortaPostmarkClientFactoryInterface
{
    /**
     * @var PostmarkClient|MockObject
     */
    private $mockClient;

    public function __construct($mockClient)
    {
        $this->mockClient = $mockClient;
    }


    public function createWithApiKey(string $apiKey): PostmarkClient
    {
        return $this->mockClient;
    }
}