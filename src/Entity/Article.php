<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\Table(indexes={
 *     @Index(name="published_at_index", columns={"published_at"}),
 *     @Index(name="category_index", columns={"category"})
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min = 2,
     *     max = 255,
     *     maxMessage = "title cannot be longer than {{ limit }} characters",
     *     minMessage = "title must be at least {{ limit }} characters long"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     maxMessage = "category name cannot be longer than {{ limit }} characters",
     *     minMessage = "category must be at least {{ limit }} characters long"
     * )
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=128)
     *
     * @Assert\Length(
     *     min = 6,
     *     max = 128,
     *     minMessage = "author name must be at least {{ limit }} characters long",
     *     minMessage = "author name must be at least {{ limit }} characters long"
     * )
     */
    private $author;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $publishedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function updateCreatedDatetime()
    {
        // update crated time
        $this->setPublishedAt(new \DateTimeImmutable());
    }
}
