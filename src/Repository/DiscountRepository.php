<?php
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 8/1/18
 * Time: 1:19 PM
 */

namespace App\Repository;

use App\Entity\Discount;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class DiscountRepository
 */
class DiscountRepository extends EntityRepository
{
    /**
     * @return mixed
     */
    public function findOrderedActiveDiscountsWithRuleNames()
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select("d.id, d.name, d.amount, d.productCategory, d.ruleValue, r.name as ruleName")
            ->from(Discount::class, 'd')
            ->innerJoin('d.rule', 'r')
            ->where('d.isActive = :isActive')
            ->orderBy('d.DiscountRepositorydiscountOrder')
            ->setParameter('isActive', true);
        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}
