<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\AdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
#[ApiResource(
    normalizationContext: ['enable_max_depth' => true, 'groups' => ['adress:read']],
    denormalizationContext: ['groups' => ['adress:write']],
    operations: [
        new GetCollection(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Post(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_USER')"),
    ]
)]

class Adress
{
    #[Groups(['adress:read', 'order:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['adress:read', 'adress:write', 'user:read'])]
    #[ORM\ManyToOne(inversedBy: 'adresses')]
    public ?User $idUser = null;

    #[Groups(['adress:read', 'adress:write'])]
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[Groups(['adress:read', 'adress:write'])]
    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[Groups(['adress:read', 'adress:write'])]
    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[Groups(['adress:read', 'adress:write'])]
    #[ORM\Column]
    private ?string $zipCode = null;

    #[Groups(['adress:read', 'adress:write'])]
    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[Groups(['adress:read', 'adress:write'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['adress:read'])]
    #[MaxDepth(1)]
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'idAdress')]
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
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

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setIdAdress($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getIdAdress() === $this) {
                $order->setIdAdress(null);
            }
        }

        return $this;
    }
}
