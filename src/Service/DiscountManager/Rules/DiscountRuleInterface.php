<?php
declare(strict_types=1);
/**
 * Description: Discount Rule interface
 *
 * @copyright 2018 Bogdan Hmarnii
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
