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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ApiResource(
    normalizationContext: ['groups' => ['order:read']],
    denormalizationContext: ['groups' => ['order:write']],
    operations: [
        new GetCollection(security: "is_granted('ROLE_USER')"), 
        new Get(security: "is_granted('ROLE_ADMIN') or object.idUser == user"),
        new Put(security: "is_granted('ROLE_ADMIN') or object.idUser == user"),
        new Post(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_ADMIN') or object.idUser == user"),
        new Delete(security: "is_granted('ROLE_ADMIN') or object.idUser == user"),
    ]
)]
class Order
{
    #[Groups(["order:read"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["order:write","order:read"])]
    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUser = null;

    #[Groups(["order:write","order:read"])]
    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adress $idAdress = null;

    #[Groups(["order:write","order:read"])]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['en cours de paiement', 'commandé', 'en cours de livraison', 'livré', 'annulé'])]
    private ?string $state = null;
    #[Groups(["order:write","order:read"])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    #[Groups(["order:read"])]
    private ?float $priceTotal = null;

    /**
     * @var Collection<int, OrderProduct>
     */
    #[Groups(["order:write", "order:read"])]
    #[ORM\OneToMany(targetEntity: OrderProduct::class, mappedBy: 'idOrder')]
    private Collection $orderProducts;

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
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
    /**
     * @return Collection<int, OrderProduct>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): static
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->add($orderProduct);
            $orderProduct->setIdOrder($this);
            $this->updatePriceTotal();
        }
    
        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): static
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getIdOrder() === $this) {
                $orderProduct->setIdOrder(null);
            }
            $this->updatePriceTotal();
        }
    
        return $this;
    }

    public function calculateTotalPrice(): float
    {
        $totalPrice = 0.0;

        foreach ($this->orderProducts as $orderProduct) {
            $totalPrice += $orderProduct->getQuantity() * $orderProduct->getPrice();
        }

        return $totalPrice;
    }

    private function updatePriceTotal(): void
    {
        $this->priceTotal = $this->calculateTotalPrice();
    }

}
