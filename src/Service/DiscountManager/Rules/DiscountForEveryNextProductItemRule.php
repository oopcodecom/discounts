<?php
declare(strict_types=1);
/**
 * Description: Discount Rule for every X product item
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Service\DiscountManager\Rules;

/**
 * Class DiscountForEveryNextProductItemRule
 */
class DiscountForEveryNextProductItemRule extends AbstractDiscountRule implements DiscountRuleInterface
{
    /**
     * @param array $order
     *
     * @return float
     */
    public function calculateDiscount(array $order): float
    {
        $discount = 0.00;

        foreach ($order['items'] as $item) {
            $productId = $this->apiClient->getProductCategoryId($item['product-id']);
            if ((string) $this->productCategoryId === $productId) {
                $countItemsForDiscount = (int) ($item['quantity'] / $this->ruleValue);
                if ($countItemsForDiscount) {
                    $discount = (((float) $item['unit-price'] * $countItemsForDiscount) * $this->discountAmount) / 100;
                }
            }
        }

        return $discount;
    }
}
