<?php
declare(strict_types=1);
/**
 * Description: Test for rule type "Discount For Cheapest Product From Products Of One Category"
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Tests\Service\DiscountRules;

use App\Entity\Discount;
use App\Entity\Rule;
use App\Service\DiscountRules\DiscountForCheapestProductFromProductsOfOneCategory;
use PHPUnit\Framework\TestCase;

/**
 * Class DiscountManagerTest
 */
class DiscountForCheapestProductFromProductsOfOneCategoryTest extends TestCase
{
    /**
     * @dataProvider provideOrderAndExpectedSingleDiscount
     *
     * @param array $providedData
     */
    public function testDiscountRuleShouldReturnCalculatedDiscount(array $providedData): void
    {
        $orderArray = json_decode($providedData['order'], true);
        $rule = new Rule();
        $rule->setName($providedData['discount']['rule']);
        $rule->setId($providedData['discount']['id']);

        $discount = new Discount();
        $discount->setId($providedData['discount']['id']);
        $discount->setName($providedData['discount']['name']);
        $discount->setDiscountRate($providedData['discount']['amount']);
        $discount->setProductCategory($providedData['discount']['productCategory']);
        $discount->setDiscountOrder($providedData['discount']['discountPriority']);
        $discount->setRule($rule);
        $discount->setRuleValue($providedData['discount']['ruleValue']);
        $discount->setIsActive($providedData['discount']['isActive']);

        $discountRule = new DiscountForCheapestProductFromProductsOfOneCategory($discount->getRuleValue(), $discount->getDiscountRate(), $discount->getProductCategory());

        $calculatedDiscountResult = $discountRule->calculateDiscount($orderArray);
        $this->assertEquals($providedData['expectedCalculatedDiscount'], $calculatedDiscountResult, "Discount rule should calculate discount for order");
    }


    /**
     * @return array
     */
    public function provideOrderAndExpectedSingleDiscount(): array
    {

        $orderAndDiscount = [
            'order' => "{
              \"id\": \"3\",
              \"customer-id\": \"3\",
              \"items\": [
                {
                  \"product-id\": \"A101\",
                  \"quantity\": \"2\",
                  \"unit-price\": \"9.75\",
                  \"total\": \"19.50\"
                },
                {
                  \"product-id\": \"A102\",
                  \"quantity\": \"1\",
                  \"unit-price\": \"49.50\",
                  \"total\": \"49.50\"
                }
              ],
              \"total\": \"69.00\"
            }",
            'discount' => [
                'name' => 'Discount if customer buy two or more products of category Tools (id 1), he get a 50% discount on the cheapest product.',
                'amount' => 50,
                'id' => 3,
                'productCategory' => 1,
                'ruleValue' => 2,
                'discountPriority' => 3,
                'isActive' => true,
                'rule' => DiscountForCheapestProductFromProductsOfOneCategory::class,
            ],
            'expectedCalculatedDiscount' => 9.75,
        ];

        return [
            [$orderAndDiscount],
        ];
    }
}
