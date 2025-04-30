<?php

namespace App\Tests\Utils;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DSYClient
{

    public function __construct(private KernelBrowser $client) { }

    public function getClient(): KernelBrowser
    {
        return $this->client;
    }

    public function getResponse(): Response
    {
        return $this->client->getResponse();
    }

    public function getResponseAsArray()
    {
        $response = $this->client->getResponse();
        return json_decode($response->getContent(), true);
    }

    public function getJson(string $url, array $params = [], array $headers = []): ?Crawler
    {
        return $this->client->request(
            Request::METHOD_GET,
            $url,
            $params,
            [],
            array_merge($headers, [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
            ])
        );
    }
}
