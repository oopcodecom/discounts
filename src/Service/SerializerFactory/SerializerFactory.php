<?php
declare(strict_types=1);
/**
 * Description: Serializer Factory responsible to create serializer instance
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Service\SerializerFactory;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

/**
 * Class SerializerClient
 */
class SerializerFactory implements SerializerFactoryInterface
{
    /**
     * @return Serializer
     */
    public function getSerializer(): Serializer
    {
        return SerializerBuilder::create()->build();
    }
}
