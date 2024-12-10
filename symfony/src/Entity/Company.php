<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ]
)]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Account>
     */
    #[ORM\OneToMany(targetEntity: Account::class, mappedBy: 'company')]
    private Collection $accounts;

    /**
     * @var Collection<int, LogMessage>
     */
    #[ORM\OneToMany(targetEntity: LogMessage::class, mappedBy: 'company')]
    private Collection $logMessages;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->logMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Account>
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): static
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts->add($account);
            $account->setCompany($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): static
    {
        if ($this->accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getCompany() === $this) {
                $account->setCompany(null);
            }
        }

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
            $logMessage->setCompany($this);
        }

        return $this;
    }

    public function removeLogMessage(LogMessage $logMessage): static
    {
        if ($this->logMessages->removeElement($logMessage)) {
            // set the owning side to null (unless already changed)
            if ($logMessage->getCompany() === $this) {
                $logMessage->setCompany(null);
            }
        }

        return $this;
    }
}
