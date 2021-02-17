<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Article;

use App\Domain\Model\Article;
use PHPUnit\Framework\TestCase;

class ArticleFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $validator = $this->getMockBuilder(Article\ArticleFieldValidator::class)
            ->getMock();

        $factory = new Article\ArticleFactory($validator);
        $article = $factory->createArticle([
            'title' => 'Article title',
            'body' => 'Article body'
        ]);

        self::assertInstanceOf(Article::class, $article);
    }

    public function testNotCreate(): void
    {
        $validator = $this->getMockBuilder(Article\ArticleFieldValidator::class)
            ->getMock();

        $validator->method('checkFields')
            ->willThrowException(new Article\InvalidFieldException);

        $factory = new Article\ArticleFactory($validator);
        $factory->createArticle([
           'title' => 'Article title',
           'body' => 'Article body'
       ]);

        self::assertNull(null);
    }
}