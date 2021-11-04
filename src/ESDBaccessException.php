<?php

namespace ESDBaccess;

use Throwable;

class ESDBaccessException extends \mysqli_sql_exception
{
    public function __construct($message = "", $code = 0, Throwable $mysqli_exception = null)
    {
        parent::__construct($message, $code, $mysqli_exception);

        echo "ESDBaccess error ".$code." : " . $message . " " . $mysqli_exception;
    }
}
