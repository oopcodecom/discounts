<?php
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 7/24/18
 * Time: 9:41 PM
 */

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as Annotation;

/**
 * Class DiscountController
 */
class DiscountController extends FOSRestController
{
    /**
     * Get discount for customer order
     *
     * This call service which will calculate discount by order and customer order history
     *
     * @Route("/api/get-discount-for-order", methods={"POST"})
     *
     * @Rest\RequestParam(name="discount", nullable=false)
     *
     * @Annotation\Response(
     *     response=200,
     *     description="Returns the discount for order",
     * )
     *
     * @Annotation\Tag(name="discounts")
     *
     * @return Response
     */
    public function getDiscountForOrderAction(Request $request): Response
    {
        return new Response($request);
    }
}
