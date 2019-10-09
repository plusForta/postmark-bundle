<?php


namespace PlusForta\PostmarkBundle;


use Postmark\PostmarkClient;

class PlusFortaPostmarkClientFactory implements PlusFortaPostmarkClientFactoryInterface
{
    /**
     * @param string $apiKey
     * @return PostmarkClient
     */
    public function createWithApiKey(string $apiKey): PostmarkClient
    {
        return new PostmarkClient($apiKey);
    }
}