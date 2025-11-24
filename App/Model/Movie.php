<?php

namespace App\Model;

use App\Model\Category;

class Movie
{
    //Attibuts
    private ?int $id;
    private ?string $title;
    private ?string $description;
    private ?\DateTimeImmutable $publishAt;
    private ?int $duration;
    private ?String $cover;
    private array $categories;

    //Constructeur
    public function __construct()
    {
        $this->categories = [];
    }

    //Getters et Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(?int $id): void 
    {
        $this->id = $id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void 
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void 
    {
        $this->description = $description;
    }

    public function getPublishAt(): ?\DateTimeImmutable
    {
        return $this->publishAt;
    }

    public function setPublishAt(?\DateTimeImmutable $publishAt): void
    {
        $this->publishAt = $publishAt;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(?string $cover): void
    {
        $this->cover = $cover;
    }

    public function getDuration(): ?int 
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): void 
    {
        $this->duration = $duration;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function addCategory(Category $category): void
    {
        $this->categories[] = $category;
    }

    public function removeCategory(Category $category): void
    {
        unset($this->categories[array_search($category, $this->categories)]);
        sort($this->categories);
    }
}