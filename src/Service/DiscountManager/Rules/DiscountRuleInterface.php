<?php
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 8/1/18
 * Time: 8:06 AM
 */

namespace App\Service\DiscountManager\Rules;

/**
 * Interface DiscountRuleInterface
 */
interface DiscountRuleInterface
{

    /**
     * DiscountRuleInterface constructor.
     *
     * @param mixed    $ruleValue
     * @param int      $discountAmount
     * @param int|null $productCategoryId
     */
    public function __construct($ruleValue, int $discountAmount, int $productCategoryId = null);

    /**
     * @param object $order
     *
     * @return float
     */
    public function calculateDiscount(object $order): float;
}
