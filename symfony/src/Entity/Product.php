<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?WpSite $website = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $productId = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $productName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $productDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $AiName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $AiDescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): static
    {
        $this->productId = $productId;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): static
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductDescription(): ?string
    {
        return $this->productDescription;
    }

    public function setProductDescription(string $productDescription): static
    {
        $this->productDescription = $productDescription;

        return $this;
    }

    public function getAiName(): ?string
    {
        return $this->AiName;
    }

    public function setAiName(?string $AiName): static
    {
        $this->AiName = $AiName;

        return $this;
    }

    public function getAiDescription(): ?string
    {
        return $this->AiDescription;
    }

    public function setAiDescription(?string $AiDescription): static
    {
        $this->AiDescription = $AiDescription;

        return $this;
    }

    public function getWebsite(): ?WpSite
    {
        return $this->website;
    }

    public function setWebsite(?WpSite $website): static
    {
        $this->website = $website;

        return $this;
    }

    
}
