<?php

namespace App\Tests\Controller;

use App\Tests\Utils\FixtureAwareTestCase;
use Symfony\Component\HttpFoundation\Response;

class NewsControllerTest extends FixtureAwareTestCase
{
    public function testGetNewsList(): void
    {
        $client = static::createClient();
        $this->initializeTest();
        
        $client->request('GET', '/api/news');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
    }
} 