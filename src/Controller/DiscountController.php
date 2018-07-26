<?php
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 7/24/18
 * Time: 9:41 PM
 */

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Swagger\Annotations\Property;
use Swagger\Annotations\Schema;

/**
 * Class DiscountController
 */
class DiscountController extends FOSRestController
{
    /**
     * Calculate discount for customer order
     *
     * This call service which will calculate discount by order and customer order history
     *
     * @Route("/api/calculate-discount-for-order", methods={"POST"})
     *
     * @SWG\Post(
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     tags={"discounts"},
     *
     *     @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     required=true,
     *     description="json order object",
     *     @SWG\Schema(
     *      type="object",
     *          @SWG\Property(
     *          type="string",
     *          property="id",
     *          example="1"
     *          )
     *    )
     * ),
     *
     *     @SWG\Response(
     *     response=200,
     *     description="calculated discount for order"
     * )
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function calculateDiscountForOrderAction(Request $request): Response
    {
        return new Response(json_encode("VANIA LOH"), 200);
    }
}
