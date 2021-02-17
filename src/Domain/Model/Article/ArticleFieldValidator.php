<?php
declare(strict_types=1);

namespace App\Domain\Model\Article;

/**
 * Class ArticleFieldValidator
 * @package App\Domain\Model\Article
 */
class ArticleFieldValidator
{
    public function checkFields(array $fields): void
    {
        if(empty($fields['title']))
        {
            throw new InvalidFieldException('Article must have a title');
        }

        if(empty($fields['body']))
        {
            throw new InvalidFieldException('Article must have a body');
        }
    }
}
