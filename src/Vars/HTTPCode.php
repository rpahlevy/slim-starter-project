<?php

namespace App\Vars;

class HTTPCode
{
    const SUCCESS               = 200;
    const BAD_PARAMETER         = 400;
    const NOT_FOUND             = 404;
    const METHOD_NOT_ALLOWED    = 405;
    const INVALID_API_KEY       = 410;
    const INVALID_TOKEN         = 411;
    const GENERAL_ERROR         = 500;
}