<?php
declare(strict_types=1);
/**
 * Description: Discount Fixtures loads initial data in database
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\DataFixtures;

use App\Entity\Discount;
use App\Entity\Rule;
use App\Service\DiscountRules\DiscountForCheapestProductFromProductsOfOneCategory;
use App\Service\DiscountRules\DiscountForEveryNextProductItemRule;
use App\Service\DiscountRules\DiscountOnCustomerSpentAmountRule;
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
        /** @var Rule[] $ruleObjects */
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
            $discountObject->setDiscountRate($discount['amount']);
            $discountObject->setProductCategory($discount['productCategory']);
            $discountObject->setDiscountOrder($discount['discountPriority']);
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
            DiscountOnCustomerSpentAmountRule::class,
            DiscountForEveryNextProductItemRule::class,
            DiscountForCheapestProductFromProductsOfOneCategory::class,
        ];
    }

    /**
     * @return array
     */
    private function getDiscounts(): array
    {
        return [
            [
                'name' => 'Discount for customer who has already bought for over € 1000, gets a discount of 10% on the whole order.',
                'amount' => 10,
                'productCategory' => null,
                'ruleValue' => 1000,
                'discountPriority' => 1,
                'isActive' => true,
            ],
            [
                'name' => 'Discount for every product of category Switches (id 2), when customer buy five, customer get a sixth for free.',
                'amount' => 100,
                'productCategory' => 2,
                'ruleValue' => 6,
                'discountPriority' => 2,
                'isActive' => true,
            ],
            [
                'name' => 'Discount if customer buy two or more products of category Tools (id 1), he get a 20% discount on the cheapest product.',
                'amount' => 20,
                'productCategory' => 1,
                'ruleValue' => 2,
                'discountPriority' => 3,
                'isActive' => true,
            ],
        ];
    }
}
