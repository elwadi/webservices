<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ApiResource(
    operations: [
        new Get(uriTemplate: '/get_account/{id}',
            normalizationContext:['groups' => 'extraread']
        ),
        new GetCollection(uriTemplate: '/list_accounts',
            normalizationContext:['groups' => 'read'],
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
    #[Assert\NotBlank]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['private'])]
    private ?string $lastname = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[Groups('extraread')]
    private ?Company $company = null;

    /**
     * @var Collection<int, LogMessage>
     */
    #[ORM\OneToMany(targetEntity: LogMessage::class, mappedBy: 'account')]
    private Collection $logMessages;

    public function __construct()
    {
        $this->logMessages = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, LogMessage>
     */
    public function getLogMessages(): Collection
    {
        return $this->logMessages;
    }

    public function addLogMessage(LogMessage $logMessage): static
    {
        if (!$this->logMessages->contains($logMessage)) {
            $this->logMessages->add($logMessage);
            $logMessage->setAccount($this);
        }

        return $this;
    }

    public function removeLogMessage(LogMessage $logMessage): static
    {
        if ($this->logMessages->removeElement($logMessage)) {
            // set the owning side to null (unless already changed)
            if ($logMessage->getAccount() === $this) {
                $logMessage->setAccount(null);
            }
        }

        return $this;
    }
}
