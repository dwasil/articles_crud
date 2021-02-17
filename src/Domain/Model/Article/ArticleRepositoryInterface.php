<?php

namespace App\Domain\Model\Article;

use App\Domain\Model\Article;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Domain\Model\Article
 */
interface ArticleRepositoryInterface
{
    /**
     * Delete article.
     *
     * @param Article $article
     */
    public function deleteArticle(Article $article): void;

    /**
     * Add article.
     *
     * @param Article $article
     * @return int Article identifier.
     */
    public function addArticle(Article $article): int;

    /**
     * Update Article.
     *
     * @param Article $article
     */
    public function updateArticle(Article $article): void;

    /**
     * Find Articles
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Article[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
}