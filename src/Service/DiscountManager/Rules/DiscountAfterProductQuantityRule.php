<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 8/1/18
 * Time: 8:11 AM
 */

namespace App\Service\DiscountManager\Rules;

/**
 * Class DiscountAfterProductQuantityRule
 */
class DiscountAfterProductQuantityRule implements DiscountRuleInterface
{
    /**
     * @var mixed|mixed
     */
    private $ruleValue;
    /**
     * @var int
     */
    private $discountAmount;
    /**
     * @var int|null
     */
    private $productCategoryId;

    /**
     * DiscountRuleInterface constructor.
     *
     * @param mixed    $ruleValue
     * @param int      $discountAmount
     * @param int|null $productCategoryId
     */
    public function __construct($ruleValue, int $discountAmount, ?int $productCategoryId = null)
    {
        $this->ruleValue = $ruleValue;
        $this->discountAmount = $discountAmount;
        $this->productCategoryId = $productCategoryId;
    }

    /**
     * @param object $order
     *
     * @return float
     */
    public function calculateDiscount(object $order): float
    {
        return 1.0;
    }
}
