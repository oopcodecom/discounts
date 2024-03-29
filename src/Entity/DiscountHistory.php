<?php
declare(strict_types=1);
/**
 * Description: Discount History entity responsible for storing total discount on whole order
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="discount_history", uniqueConstraints={
 * @ORM\UniqueConstraint(name="unique_order_id", columns="order_id"),
 * })
 */
class DiscountHistory
{
    /**
     * @Serializer\Type("string")
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=191)
     */
    private $orderId;

    /**
     * @Serializer\Type("string")
     *
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $totalDiscountAmount;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppliedDiscount", mappedBy="discountHistory")
     */
    private $appliedDiscounts;

    /**
     * DiscountHistory constructor
     */
    public function __construct()
    {
        $this->appliedDiscounts = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return DiscountHistory
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     *
     * @return DiscountHistory
     */
    public function setOrderId(string $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalDiscountAmount(): float
    {
        return $this->totalDiscountAmount;
    }

    /**
     * @param float $totalDiscountAmount
     *
     * @return DiscountHistory
     */
    public function setTotalDiscountAmount(float $totalDiscountAmount): self
    {
        $this->totalDiscountAmount = $totalDiscountAmount;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAppliedDiscounts(): Collection
    {
        return $this->appliedDiscounts;
    }

    /**
     * @param AppliedDiscount $appliedDiscounts
     *
     * @return DiscountHistory
     */
    public function addAppliedDiscount(AppliedDiscount $appliedDiscounts): self
    {
        $appliedDiscounts->setDiscountHistory($this);
        $this->appliedDiscounts[] = $appliedDiscounts;

        return $this;
    }

    /**
     * @param ArrayCollection $appliedDiscounts
     *
     * @return DiscountHistory
     */
    public function addAppliedDiscounts(ArrayCollection $appliedDiscounts): self
    {
        foreach ($appliedDiscounts as $appliedDiscount) {
            $this->addAppliedDiscount($appliedDiscount);
        }

        return $this;
    }
}
