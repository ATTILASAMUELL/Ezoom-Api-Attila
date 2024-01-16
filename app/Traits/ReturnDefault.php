<?php
namespace App\Traits;
trait ReturnDefault
{
    public function error($msg)
    {
        return [
            "erro" => true,
            "msg" => $msg
        ];
    }

    public function success($msg)
    {
        return [
            "erro" => false,
            "msg" => $msg
        ];
    }

}