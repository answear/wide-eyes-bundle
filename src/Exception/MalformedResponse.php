<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Exception;

class MalformedResponse extends \RuntimeException
{
    public function __construct(
        $message,
        public readonly mixed $response,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
