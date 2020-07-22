<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseWebTestCase extends WebTestCase
{
    use FixturesTrait;

    const SERVER_INFO = [
      'ACCEPT' => 'application/json',
      'CONTENT_TYPE' => 'application/json'
    ];

    public function getResponseFromRequest(
        string $method,
        string $uri,
        string $payload = '',
        bool $sendRequestTwice = false
    ): Response {
        $client = self::createClient();
        $this->loadFixtures([AppFixtures::class]);

        $client->request(
            $method,
            $uri . '.json',
            [],
            [],
            self::SERVER_INFO,
            $payload
        );

        if ($sendRequestTwice) {
            $client->request(
                $method,
                $uri . '.json',
                [],
                [],
                self::SERVER_INFO,
                $payload
            );
        }

        return $client->getResponse();
    }
}
