<?php

namespace Framework\ESDBaccess;

/**
 * ESDBaccess exception handler.
 *
 */
class ESDBaccessException extends \Exception
{
    public function __construct($message = "", $code = 0, \mysqli_sql_exception $mysqli_exception = null)
    {
        parent::__construct($message, $code, $mysqli_exception);

        // echo "ESDBaccess error ".$code." : " . $message . " " . $mysqli_exception;
    }
}

?>
