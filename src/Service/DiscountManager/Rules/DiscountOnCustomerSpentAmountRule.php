<?php
declare(strict_types=1);
/**
 * Description: Discount Rule based on total customer spent amount in the portal
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Service\DiscountManager\Rules;

/**
 * Class DiscountOnCustomerSpentAmountRule
 */
class DiscountOnCustomerSpentAmountRule extends AbstractDiscountRule implements DiscountRuleInterface
{
    /**
     * @param array $order
     *
     * @return float
     */
    public function calculateDiscount(array $order): float
    {
        $discount = 0.00;
        $customerSpentAmount = $this->apiClient->getCustomerSpentAmountByCustomerId($order['customer-id']);

        if ($customerSpentAmount > $this->ruleValue) {
            $discount = (float) $order['total'] * $this->discountAmount / 100;
        }

        return $discount;
    }
}
