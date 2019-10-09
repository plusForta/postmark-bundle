<?php


namespace PlusForta\PostmarkBundle;


use Postmark\PostmarkClient;

interface PlusFortaPostmarkClientFactoryInterface
{
    /**
     * @param string $apiKey
     * @return PostmarkClient
     */
    public function createWithApiKey(string $apiKey): PostmarkClient;
}