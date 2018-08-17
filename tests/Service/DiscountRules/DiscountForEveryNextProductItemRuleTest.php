<?php
declare(strict_types=1);
/**
 * Description: Test for rule type "Discount For Every Next Product Item Rule Test"
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Tests\Service\DiscountRules;

use App\Entity\Discount;
use App\Entity\Rule;
use App\Service\DiscountRules\DiscountForEveryNextProductItemRule;
use PHPUnit\Framework\TestCase;

/**
 * Class DiscountManagerTest
 */
class DiscountForEveryNextProductItemRuleTest extends TestCase
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

        $discountRule = new DiscountForEveryNextProductItemRule($discount->getRuleValue(), $discount->getDiscountRate(), $discount->getProductCategory());

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
             \"id\": \"1\",
             \"customer-id\": \"1\",
             \"items\": [
             {
                \"product-id\": \"B102\",
                \"quantity\": \"10\",
                \"unit-price\": \"4.99\",
                \"total\": \"49.90\"
             }       
            ],
             \"total\": \"49.90\"
            }",
            'discount' => [
                'name' => 'Discount for every product of category Switches (id 2), when customer buy five, customer get a third for free.',
                'amount' => 100,
                'id' => 2,
                'productCategory' => 2,
                'ruleValue' => 3,
                'discountPriority' => 2,
                'isActive' => true,
                'rule' => DiscountForEveryNextProductItemRule::class,
            ],
            'expectedCalculatedDiscount' => 14.97,
        ];

        return [
            [$orderAndDiscount],
        ];
    }
}
