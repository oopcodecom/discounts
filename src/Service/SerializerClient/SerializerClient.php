<?php
declare(strict_types=1);
/**
 * Description: Serializer Client responsible to provide serializer class object
 *
 * @copyright 2018 Bogdan Hmarnii
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
