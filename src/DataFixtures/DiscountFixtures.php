<?php
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 7/30/18
 * Time: 9:29 PM
 */

namespace App\DataFixtures;

use App\Entity\Discount;
use App\Entity\Rule;
use App\Service\DiscountManager\Rules\DiscountAfterProductQuantityRule;
use App\Service\DiscountManager\Rules\DiscountForEveryNextProductRule;
use App\Service\DiscountManager\Rules\DiscountOnCustomerSpentAmountRule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class AppFixtures
 */
class DiscountFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $ruleObjects = [];
        foreach ($this->getRules() as $rule) {
            $ruleObject = new Rule();
            $ruleObject->setName($rule);
            $manager->persist($ruleObject);
            $ruleObjects[] = $ruleObject;
        }

        $index = 0;

        foreach ($this->getDiscounts() as $discount) {
            $discountObject = new Discount();
            $discountObject->setName($discount['name']);
            $discountObject->setAmount($discount['amount']);
            $discountObject->setProductCategory($discount['productCategory']);
            $discountObject->setDiscountOrder($discount['discountOrder']);
            $discountObject->setRule($ruleObjects[$index]);
            $discountObject->setRuleValue($discount['ruleValue']);
            $discountObject->setIsActive($discount['isActive']);
            $manager->persist($discountObject);
            $index++;
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    private function getRules(): array
    {
        return [
            DiscountAfterProductQuantityRule::class,
            DiscountForEveryNextProductRule::class,
            DiscountOnCustomerSpentAmountRule::class,
        ];
    }

    /**
     * @return array
     */
    private function getDiscounts(): array
    {
        return [
            [
                'name' => 'Discount on customer spent amount more then 1000 euros',
                'amount' => 10,
                'productCategory' => null,
                'ruleValue' => 1000,
                'discountOrder' => 1,
                'isActive' => true,
            ],
            [
                'name' => 'Discount on every sixth product in category "Switches"',
                'amount' => 100,
                'productCategory' => 2,
                'ruleValue' => 6,
                'discountOrder' => 2,
                'isActive' => true,
            ],
            [
                'name' => 'Discount on cheapest product if two or more products in category "Tools"',
                'amount' => 20,
                'productCategory' => 1,
                'ruleValue' => 2,
                'discountOrder' => 3,
                'isActive' => true,
            ],
        ];
    }
}
