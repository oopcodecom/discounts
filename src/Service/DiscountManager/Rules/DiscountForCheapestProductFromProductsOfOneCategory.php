<?php
declare(strict_types=1);
/**
 * Description: Discount Rule on the cheapest product when client have X quantity of products
 *                from similar product category.
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Service\DiscountManager\Rules;

/**
 * Class DiscountForCheapestProductFromProductsOfOneCategory
 */
class DiscountForCheapestProductFromProductsOfOneCategory extends AbstractDiscountRule implements DiscountRuleInterface
{
    /**
     * @param array $order
     *
     * @return float
     */
    public function calculateDiscount(array $order): float
    {
        $discount = 0.00;

        $productsForDiscount = $this->sortProductsForDiscount($order['items']);

        if (count($productsForDiscount) >= $this->ruleValue) {
            $discount = (float) $productsForDiscount[0]['total'];
            array_map(function ($product) use (&$discount) {
                if ((float) $product['total'] < $discount) {
                    $discount = (float) $product['total'];
                }
            }, $productsForDiscount);
        }

        return $discount;
    }

    /**
     * @param array $products
     *
     * @return array
     */
    private function sortProductsForDiscount(array $products): array
    {
        $productsForDiscount = [];

        foreach ($products as $product) {
            $productCategoryId = $this->apiClient->getProductCategoryId($product['product-id']);
            if ((string) $this->productCategoryId === $productCategoryId) {
                $productsForDiscount[] = $product;
            }
        }

        return $productsForDiscount;
    }
}