<?php
declare(strict_types=1);
/**
 * Description: Rest Client Stub simulates communication channel
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Service\RestClientStub;

/**
 * Class RestClientStub
 */
class RestClientStub
{
    /**
     * @param string $customerId
     *
     * @return float
     */
    public function getCustomerSpentAmountByCustomerId(string $customerId): float
    {
        switch ($customerId) {
            case "1":
                $spentAmount = 1205.05;
                break;
            case "2":
                $spentAmount = 800.05;
                break;
            case "3":
                $spentAmount = 1;
                break;
            default:
                $spentAmount = 1001;
        }

        return $spentAmount;
    }

    /**
     * @param string $productId
     *
     * @return string
     */
    public function getProductCategoryId(string $productId): string
    {
        switch ($productId) {
            case "A101":
            case "A102":
            case "A103":
                $categoryId = "1";
                break;
            case "B101":
            case "B102":
            case "B103":
                $categoryId = "2";
                break;
            default:
                $categoryId = "2";
        }

        return $categoryId;
    }
}
