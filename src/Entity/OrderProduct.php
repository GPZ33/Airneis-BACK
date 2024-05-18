<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\OrderProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderProductRepository::class)]
#[ApiResource(
    normalizationContext: ['enable_max_depth' => true,'groups' => ['order_product:read']],
    denormalizationContext: ['groups' => ['order_product:write']],
    operations: [
        new GetCollection(security: "is_granted('ROLE_USER')"), 
        new Get(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Post(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_USER')"),
    ],
)]
class OrderProduct
{
    #[Groups(["order_product:read", "order:read"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Groups(["order_product:read", "order:read"])]
    #[SerializedName("idOrder")]
    #[ORM\ManyToOne(inversedBy: 'orderProducts', cascade: ["persist"])]
    private ?Order $idOrder = null;

    #[Groups(["order_product:read","order_product:write"])]
    #[ORM\ManyToOne(inversedBy: 'orderProducts')]
    private ?Product $idProduct = null;

    #[Groups(["order_product:read","order_product:write"])]
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(1)]
    private ?int $quantity = null;

    #[Groups(["order_product:read"])]
    #[ORM\Column]
    private ?float $price = null;

    #[Groups(["order_product:read","order_product:write","user:read"])]
    #[ORM\ManyToOne(inversedBy: 'orderProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUser = null;

    public function __construct()
    {
        $this->updatePriceFromProduct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOrder(): ?Order
    {
        return $this->idOrder;
    }

    public function setIdOrder(?Order $idOrder): static
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    public function getIdProduct(): ?Product
    {
        return $this->idProduct;
    }

    public function setIdProduct(?Product $idProduct): static
    {
        $this->idProduct = $idProduct;
        $this->updatePriceFromProduct();
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    private function updatePriceFromProduct(): void
    {
        if ($this->idProduct) {
            $this->price = $this->idProduct->getPrice();
        }
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
}
