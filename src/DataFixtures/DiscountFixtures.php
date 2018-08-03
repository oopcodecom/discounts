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
use App\Service\DiscountManager\Rules\DiscountForCheapestProductFromProductsOfOneCategory;
use App\Service\DiscountManager\Rules\DiscountForEveryNextProductItemRule;
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
