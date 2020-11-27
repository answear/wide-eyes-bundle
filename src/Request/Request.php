<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Request;

interface Request
{
    /**
     * Converts request object to json that will be sent via the API.
     */
    public function toJson(): string;
}
