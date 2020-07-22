<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @IgnoreAnnotation("depends")
 */
class MarkTest extends BaseWebTestCase
{
    const MARK_PAYLOAD = '{"subject": "%s", "value": %d, "student": "%s"}';

    public function testGetFirstStudent(): int
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/students'
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertIsArray($responseDecoded);

        return $responseDecoded[0]['id'];
    }

    /**
     * @depends testGetFirstStudent
     */
    public function testPostMark(int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/marks',
            sprintf(self::MARK_PAYLOAD, 'BIO101', 14, "/api/students/${id}")
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($responseContent);
        $this->assertIsArray($responseDecoded);
        $this->assertSame('BIO101', $responseDecoded['subject']);
    }

    /**
     * @depends testGetFirstStudent
     */
    public function testPostDuplicateMark(int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/marks',
            sprintf(self::MARK_PAYLOAD, 'ABC123', 20, "/api/students/${id}"),
            true
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJson($responseContent);
        $this->assertIsArray($responseDecoded);
        $this->assertStringContainsString('this student is already reading that subject', $responseDecoded['detail']);
    }

    /**
     * @depends testGetFirstStudent
     */
    public function testPostMarkWithoutSubject(int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/marks',
            sprintf(self::MARK_PAYLOAD, '', 20, "/api/students/${id}"),
            true
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJson($responseContent);
        $this->assertIsArray($responseDecoded);
        $this->assertStringContainsString('subject: This value should not be empty', $responseDecoded['detail']);
    }

    /**
     * @depends testGetFirstStudent
     */
    public function testPostMarkWithoutIri(int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/marks',
            sprintf(self::MARK_PAYLOAD, 'ABC123', 20, ""),
            true
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJson($responseContent);
        $this->assertIsArray($responseDecoded);
        $this->assertStringContainsString('Invalid IRI', $responseDecoded['detail']);
    }
}
