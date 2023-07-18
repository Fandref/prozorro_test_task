<?php

namespace app\services\tenderApi\exceptions;

use Exception;
use Throwable;

class ApiException extends Exception
{

    public function __construct(string $description, int $code, Throwable $previous = null)
    {
        parent::__construct(
            "{$code}: {$description}",
            $code,
            $previous
        );
    }
}