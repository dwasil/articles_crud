<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\Article;

use App\Domain\Model\Article;
use PHPUnit\Framework\TestCase;

class ArticleFieldValidatorTest extends TestCase
{
    public function testCheckFields(): void
    {
        $validator = new Article\ArticleFieldValidator();

        self::assertNull(
            $validator->checkFields(
                [
                    'title' => 'Article title',
                    'body' => 'Article body'
                ]
            )
        );
    }

    public function testCheckFieldsNoTitle(): void
    {
        $validator = new Article\ArticleFieldValidator();

        $this->expectException(Article\InvalidFieldException::class);
        self::assertNull(
            $validator->checkFields(
                [
                    'body' => 'Article body'
                ]
            )
        );
    }

    public function testCheckFieldsNoBody(): void
    {
        $validator = new Article\ArticleFieldValidator();

        $this->expectException(Article\InvalidFieldException::class);
        self::assertNull(
            $validator->checkFields(
                [
                    'title' => 'Article title'
                ]
            )
        );
    }
}