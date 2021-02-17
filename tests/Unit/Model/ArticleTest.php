<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model;

use App\Domain\Model\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    public function testIdSetterGetter(): void
    {
        $article = new Article();

        $article->setId(234);
        self::assertEquals(234, $article->getId());
    }

    public function testTitleSetterGetter(): void
    {
        $article = new Article();

        $article->setTitle('Article title');
        self::assertEquals('Article title', $article->getTitle());
    }

    public function testBodySetterGetter(): void
    {
        $article = new Article();

        $article->setBody('Article body');
        self::assertEquals('Article body', $article->getBody());
    }

    public function testCreatedAtSetterGetter(): void
    {
        $article = new Article();
        $now = new \DateTime();

        $article->setCreatedAt($now);
        self::assertEquals($now, $article->getCreatedAt());
    }

    public function testUpdatedAtSetterGetter(): void
    {
        $article = new Article();
        $now = new \DateTime();

        $article->setUpdatedAt($now);
        self::assertEquals($now, $article->getUpdatedAt());
    }
}