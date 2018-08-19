<?php
declare(strict_types=1);
/**
 * Description: Serializer Factory interface
 *
 * @copyright 2018 Bogdan Hmarnii
 */

namespace App\Service\SerializerFactory;

use JMS\Serializer\Serializer;

/**
 * Interface SerializerClientInterface
 */
interface SerializerFactoryInterface
{
    /**
     * @return Serializer
     */
    public function getSerializer(): Serializer;
}
