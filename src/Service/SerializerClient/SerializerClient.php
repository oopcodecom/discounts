<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bogdan
 * Date: 8/1/18
 * Time: 8:35 PM
 */

namespace App\Service\SerializerClient;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

/**
 * Class SerializerClient
 */
class SerializerClient
{
    /**
     * @return Serializer
     */
    public function getSerializer(): Serializer
    {
        return SerializerBuilder::create()->build();
    }
}
