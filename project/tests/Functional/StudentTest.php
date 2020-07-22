<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @IgnoreAnnotation("depends")
 */
class StudentTest extends BaseWebTestCase
{
    const STUDENT_PAYLOAD = '{"firstName": "%s", "lastName": "%s", "birthDate": "%s"}';

    public function testGetStudents(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/students'
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($responseContent);
        $this->assertIsArray($responseDecoded);
        $this->assertGreaterThan(1, count($responseDecoded));
    }

    public function testPostStudent(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/students',
            sprintf(self::STUDENT_PAYLOAD, 'JOHN', 'DOE', '2000-10-10')
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($responseContent);
        $this->assertIsArray($responseDecoded);
        $this->assertSame('JOHN', $responseDecoded['firstName']);
    }

    public function testPostDuplicateStudent(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/students',
            sprintf(self::STUDENT_PAYLOAD, 'JOHN', 'DOE', '2000-10-10'),
            true
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJson($responseContent);
        $this->assertIsArray($responseDecoded);
        $this->assertStringContainsString('firstName: this name is already in use on that birthDate', $responseDecoded['detail']);
    }

    public function testPostStudentWithoutName(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/students',
            sprintf(self::STUDENT_PAYLOAD, '', 'DOE', '2000-10-10'),
            true
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJson($responseContent);
        $this->assertIsArray($responseDecoded);
        $this->assertStringContainsString('firstName: This value should not be blank.', $responseDecoded['detail']);
    }

    public function testGetDefaultStudent(): int
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
     * @depends testGetDefaultStudent
     */
    public function testPutStudent(int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_PUT,
            '/api/students/' . $id,
            sprintf(self::STUDENT_PAYLOAD, 'JOHN', 'DOE', '2000-10-10')
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($responseContent);
        $this->assertIsArray($responseDecoded);
        $this->assertSame('JOHN', $responseDecoded['firstName']);
    }

    /**
     * @depends testGetDefaultStudent
     */
    public function testDeleteStudent(int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            '/api/students/' . $id
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEmpty($responseDecoded);
    }
}
