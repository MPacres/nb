<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class UserControllerTest extends TestCase
{
    public function testValidData(): void
    {
        $client = HttpClient::create();

        $response = $client->request('POST', 'http://localhost:8080/users', [
            'json' => [
                'email' => 'test@example.com',
                'firstName' => 'John',
                'lastName' => 'Doe',
            ]
        ]);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function testMissingRequiredFields(): void
    {
        $client = HttpClient::create();

        $response = $client->request('POST', 'http://localhost:8080/users', [
            'json' => [
                'email' => 'test@example.com',
                // Missing firstName and lastName
            ]
        ]);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testInvalidEmailFormat(): void
    {
        $client = HttpClient::create();

        $response = $client->request('POST', 'http://localhost:8080/users', [
            'json' => [
                'email' => 'invalid-email', // Invalid email format
                'firstName' => 'John',
                'lastName' => 'Doe',
            ]
        ]);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testInvalidJsonData(): void
    {
        $client = HttpClient::create();

        $response = $client->request('POST', 'http://localhost:8080/users', [
            'body' => 'invalid json data',
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->assertSame(400, $response->getStatusCode());
    }

 

}
