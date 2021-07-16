<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=1000, nullable=true)
	 */
	private $description;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $cover;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $publish_year;

	/**
	 * @ORM\ManyToMany(targetEntity=Author::class, mappedBy="books")
	 */
	private $authors;

	public function __construct()
	{
		$this->authors = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): self
	{
		$this->description = $description;

		return $this;
	}

	public function getCover(): ?string
	{
		return $this->cover;
	}

	public function setCover(?string $cover): self
	{
		$this->cover = $cover;

		return $this;
	}

	public function getPublishYear(): ?string
	{
		return $this->publish_year;
	}

	public function setPublishYear(?string $publish_year): self
	{
		$this->publish_year = $publish_year;

		return $this;
	}

	/**
	 * @return Collection|Author[]
	 */
	public function getAuthors(): Collection
	{
		return $this->authors;
	}

	public function addAuthor(Author $author): self
	{
		if (!$this->authors->contains($author)) {
			$this->authors[] = $author;
			$author->addBook($this);
		}

		return $this;
	}

	public function removeAuthor(Author $author): self
	{
		if ($this->authors->removeElement($author)) {
			$author->removeBook($this);
		}

		return $this;
	}
}
