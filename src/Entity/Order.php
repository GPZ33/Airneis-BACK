<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
// Gestion des routes et autorisations à vérifier et clarifier avec serialisation
#[ApiResource]
#[GetCollection(security: "is_granted('ROLE_ADMIN')")] // Peut on ajouter or object.owner si l'user a plusieurs commandes ? Si oui, l'appel GetCollection appellera t il tous les objets ou juste les siens ?
#[Get(security: "is_granted('ROLE_ADMIN') or object.owner == user")]
#[Post(security: "is_granted('ROLE_USER')")] // sécurité suffisante ? Un autre User peut-il créer une commande et mettre l'ID d'un autre User ? Du mal à visualiser
#[Put(security: "is_granted('ROLE_ADMIN') or object.owner == user")]
#[Patch(security: "is_granted('ROLE_ADMIN') or object.owner == user")]
#[Delete(security: "is_granted('ROLE_ADMIN')")]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUser = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $idProduct = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adress $idAdress = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?float $priceTotal = null;

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

    public function getIdProduct(): ?Product
    {
        return $this->idProduct;
    }

    public function setIdProduct(?Product $idProduct): static
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    public function getIdAdress(): ?Adress
    {
        return $this->idAdress;
    }

    public function setIdAdress(?Adress $idAdress): static
    {
        $this->idAdress = $idAdress;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPriceTotal(): ?float
    {
        return $this->priceTotal;
    }

    public function setPriceTotal(float $priceTotal): static
    {
        $this->priceTotal = $priceTotal;

        return $this;
    }
}
