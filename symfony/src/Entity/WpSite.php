<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\WpSiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WpSiteRepository::class)]
#[ApiResource]
class WpSite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $websiteurl = null;

    #[ORM\Column(length: 255)]
    private ?string $csKey = null;

    #[ORM\Column(length: 255)]
    private ?string $csSecret = null;

    #[ORM\ManyToOne(inversedBy: 'wpSites')]
    private ?Account $account = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'website')]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebsiteurl(): ?string
    {
        return $this->websiteurl;
    }

    public function setWebsiteurl(string $websiteurl): static
    {
        $this->websiteurl = $websiteurl;

        return $this;
    }

    public function getCsKey(): ?string
    {
        return $this->csKey;
    }

    public function setCsKey(string $csKey): static
    {
        $this->csKey = $csKey;

        return $this;
    }

    public function getCsSecret(): ?string
    {
        return $this->csSecret;
    }

    public function setCsSecret(string $csSecret): static
    {
        $this->csSecret = $csSecret;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setWebsite($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getWebsite() === $this) {
                $product->setWebsite(null);
            }
        }

        return $this;
    }
}
