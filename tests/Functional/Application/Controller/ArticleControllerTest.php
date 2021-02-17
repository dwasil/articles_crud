<?php

declare(strict_types=1);

namespace App\Functional\Application\Controller;

use App\Infrastructure\Persistence\Doctrine\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ArticleControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        parent::setUp();
    }

    public function testGetArticlesAction(): void
    {
        $this->client->request(
            'GET',
            '/article/',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => '123456']
        );

        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        self::assertStringContainsString(
            'Tesla Invests $1.5 Billion in Bitcoin',
            $this->client->getResponse()->getContent()
        );
        self::assertStringContainsString('the bills are coming due to', $this->client->getResponse()->getContent());
    }

    public function testGetArticlesActionSort(): void
    {
        $this->client->request(
            'GET',
            '/article/?orderBy=title',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => '123456']
        );

        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        self::assertIsArray($data);
        self::assertCount(2, $data);
        self::assertArrayHasKey('title', $data[0]);
        self::assertEquals(
            'Malls Spent Billions on Theme Parks to Woo Shoppers. It Made Matters Worse.',
            $data[0]['title']
        );

        $this->client->request(
            'GET',
            '/article/?orderBy=title&order=desc',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => '123456']
        );
        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        self::assertEquals(
            'Tesla Invests $1.5 Billion in Bitcoin',
            $data[0]['title']
        );
    }

    public function testGetArticlesActionLimit(): void
    {
        $this->client->request(
            'GET',
            '/article/?limit=1',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => '123456']
        );

        self::assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        self::assertIsArray($data);
        self::assertCount(1, $data);
    }

    public function testGetArticlesActionWrongAuth(): void
    {
        $this->client->request(
            'GET',
            '/article/',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => '1234567']
        );

        self::assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteArticleAction(): void
    {
        $this->client->request(
            'DELETE',
            '/article/1',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => '123456']
        );

        self::assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteArticleActionNotFound(): void
    {
        $this->client->request(
            'DELETE',
            '/article/3',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => '123456']
        );

        self::assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateArticleAction(): void
    {
        $this->client->request(
            'PUT',
            '/article/1',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => '123456'],
            '{ 
                "title": "The updated article title",
                "body": "The updated article body"
             }'
        );

        self::assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateArticleActionBadRequest(): void
    {
        $this->client->request(
            'PUT',
            '/article/1',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => '123456'],
            '{ 
                "title": "The updated article title"
             }'
        );

        self::assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }

    public function testAddArticleAction(): void
    {
        $this->client->request(
            'POST',
            '/article/',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => '123456'],
            '{ 
                "title": "The added article title",
                "body": "The added article body"
             }'
        );

        self::assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }
}