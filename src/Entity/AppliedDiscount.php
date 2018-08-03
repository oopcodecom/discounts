<?php
declare(strict_types=1);
/**
 * Description: Applied Discount entity responsible for storing every applied discount on order
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 */
class AppliedDiscount
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Discount
     *
     * @ORM\ManyToOne(targetEntity="Discount", inversedBy="appliedDiscounts")
     * @ORM\JoinColumn(name="discount_id", referencedColumnName="id")
     */
    private $discount;

    /**
     * @Serializer\Exclude()
     *
     * @var DiscountHistory
     *
     * @ORM\ManyToOne(targetEntity="DiscountHistory", inversedBy="appliedDiscounts")
     * @ORM\JoinColumn(name="discount_history_id", referencedColumnName="id")
     */
    private $discountHistory;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $discountAmount;

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
     * @return AppliedDiscount
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Discount
     */
    public function getDiscount(): Discount
    {
        return $this->discount;
    }

    /**
     * @param Discount $discount
     *
     * @return AppliedDiscount
     */
    public function setDiscount(Discount $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return DiscountHistory
     */
    public function getDiscountHistory(): DiscountHistory
    {
        return $this->discountHistory;
    }

    /**
     * @param DiscountHistory $discountHistory
     *
     * @return AppliedDiscount
     */
    public function setDiscountHistory(DiscountHistory $discountHistory): self
    {
        $this->discountHistory = $discountHistory;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    /**
     * @param float $discountAmount
     *
     * @return AppliedDiscount
     */
    public function setDiscountAmount(float $discountAmount): self
    {
        $this->discountAmount = $discountAmount;

        return $this;
    }
}
