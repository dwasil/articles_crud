<?php
declare(strict_types=1);

namespace App\Domain\Model;

use DateTime;

/**
 * Class Article
 * @package App\Domain\Model
 */
class Article
{
    private int $id;
    private string $title;
    private string $body;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

	public function getCreatedAt() :?DateTime
	{
		return $this->createdAt;
	}

	public function setCreatedAt(DateTime $createdAt): self
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	public function getUpdatedAt() :?DateTime
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(DateTime $updatedAt): self
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}
}
