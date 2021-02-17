<?php

declare(strict_types=1);

namespace App\Domain\Model\Article;

use App\Domain\Model\Article;
use DateTime;

/**
 * Class ArticleFactory
 * @package App\Domain\Model\Article
 */
class ArticleFactory
{
    private ArticleFieldValidator $articleValidator;

    public function __construct(ArticleFieldValidator $articleValidator)
    {
        $this->articleValidator = $articleValidator;
    }

    public function createArticle(array $fields): ?Article
    {
        try {
            $this->articleValidator->checkFields($fields);
        } catch (InvalidFieldException $exception) {
            return null;
        }

        $now = new DateTime();

        return (new Article())
            ->setTitle($fields['title'])
            ->setBody($fields['body'])
            ->setCreatedAt($now)
            ->setUpdatedAt($now);
    }
}
