<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ApiResource(
    operations: [
        new Get(uriTemplate: '/get_account/{id}',
            normalizationContext:['groups' => 'extraread']
        ),
        new GetCollection(uriTemplate: '/list_accounts',
            normalizationContext:['groups' => 'read']
        ),
    ],
    
    // denormalizationContext:[
    //     'groups' => ['write']
    // ],
    // normalizationContext:[
    //     'groups' => ['read']
    // ]
)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read','extraread'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read','extraread', 'write'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['private'])]
    private ?string $lastname = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[Groups('extraread')]
    private ?Company $company = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
