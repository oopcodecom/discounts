<?php
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 7/24/18
 * Time: 9:41 PM
 */

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as Annotation;

/**
 * Class DiscountController
 */
class DiscountController extends FOSRestController
{
    /**
     * List the rewards of the specified user.
     *
     * This call takes into account all confirmed awards, but not pending or refused awards.
     *
     * @Route("/api/get-discount-for-order", methods={"GET"})
     *
     * @Annotation\Response(
     *     response=200,
     *     description="Returns the rewards of an user",
     * )
     *
     * @Annotation\Tag(name="discounts")
     *
     * @return Response
     */
    public function getDiscountForOrderAction(): Response
    {
        return new Response('yeeay');
    }
}
