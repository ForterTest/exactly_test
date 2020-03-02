<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class IncorrectAmountException extends Exception
{
    public function __construct($message = "", $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct("Error: Incorrect amount: ".$message, $code, $previous);
    }
}
