<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 7/24/18
 * Time: 9:41 PM
 */


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="Discount", inversedBy="appliedDiscount")
     * @ORM\JoinColumn(name="discount_id", referencedColumnName="id")
     */
    private $discount;

    /**
     * @var DiscountHistory
     *
     * @ORM\ManyToOne(targetEntity="DiscountHistory", inversedBy="appliedDiscount")
     * @ORM\JoinColumn(name="discount_history_id", referencedColumnName="id")
     */
    private $discountHistory;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $amount;

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
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return AppliedDiscount
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
