<?php

namespace App\Entity;

use App\Repository\ProviderCategoryRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="provider_categories")
 * @ORM\Entity(repositoryClass=ProviderCategoryRepository::class)
 */
class ProviderCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=99)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity=UserProvider::class, mappedBy="providerCategoryId")
     */
    private $userProviders;

    /**
     * @ORM\OneToMany(targetEntity=ServiceCategory::class, mappedBy="providerCategoryId")
     */
    private $serviceCategories;

    public function __construct()
    {
        $this->userProviders = new ArrayCollection();
        $this->serviceCategories = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): self
    {
        $this->createdAt = new DateTime("now");
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): self
    {
        $this->updatedAt = new DateTime("now");
        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(): self
    {
        $this->deletedAt = new DateTime("now");
        return $this;
    }

    /**
     * @return Collection<int, UserProvider>
     */
    public function getUserProviders(): Collection
    {
        return $this->userProviders;
    }

    public function addUserProviders(UserProvider $userProviders): self
    {
        if (!$this->userProviders->contains($userProviders)) {
            $this->userProviders[] = $userProviders;
            $userProviders->setProviderCategoryId($this);
        }

        return $this;
    }

    public function removeUserProviders(UserProvider $userProviders): self
    {
        if ($this->userProviders->removeElement($userProviders)) {
            // set the owning side to null (unless already changed)
            if ($userProviders->getProviderCategoryId() === $this) {
                $userProviders->setProviderCategoryId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ServiceCategory>
     */
    public function getServiceCategories(): Collection
    {
        return $this->serviceCategories;
    }

    public function addServiceCategory(ServiceCategory $serviceCategory): self
    {
        if (!$this->serviceCategories->contains($serviceCategory)) {
            $this->serviceCategories[] = $serviceCategory;
            $serviceCategory->setProviderCategoryId($this);
        }

        return $this;
    }

    public function removeServiceCategory(ServiceCategory $serviceCategory): self
    {
        if ($this->serviceCategories->removeElement($serviceCategory)) {
            // set the owning side to null (unless already changed)
            if ($serviceCategory->getProviderCategoryId() === $this) {
                $serviceCategory->setProviderCategoryId(null);
            }
        }

        return $this;
    }
}
