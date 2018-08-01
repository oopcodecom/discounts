<?php
declare(strict_types=1);
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
     * Note: In real world, $order should be an object of Order object
     *
     * @param array $order
     *
     * @return float
     */
    public function calculateDiscount(array $order): float;
}
