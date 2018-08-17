<?php
declare(strict_types=1);
/**
 * Description: Test for rule type "Discount On Customer Spent Amount Rule"
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Tests\Service\DiscountRules;

use App\Entity\Discount;
use App\Entity\Rule;
use App\Service\DiscountRules\DiscountOnCustomerSpentAmountRule;
use PHPUnit\Framework\TestCase;

/**
 * Class DiscountManagerTest
 */
class DiscountOnCustomerSpentAmountRuleTest extends TestCase
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

        $discountRule = new DiscountOnCustomerSpentAmountRule($discount->getRuleValue(), $discount->getDiscountRate(), $discount->getProductCategory());

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
              \"id\": \"2\",
              \"customer-id\": \"2\",
              \"items\": [
                {
                  \"product-id\": \"B102\",
                  \"quantity\": \"5\",
                  \"unit-price\": \"4.99\",
                  \"total\": \"24.95\"
                }
              ],
              \"total\": \"24.95\"
            }",
            'discount' => [
                'name' => 'Discount for customer who has already bought for over € 1000, gets a discount of 20% on the whole order.',
                'amount' => 20,
                'id' => 1,
                'productCategory' => null,
                'ruleValue' => 1000,
                'discountPriority' => 1,
                'isActive' => true,
                'rule' => DiscountOnCustomerSpentAmountRule::class,
                'discountAmount' => 2.495,
            ],
            'expectedCalculatedDiscount' => 4.99,
        ];

        return [
            [$orderAndDiscount],
        ];
    }
}
