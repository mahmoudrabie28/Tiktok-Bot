<?php

function checkInt($inserted_value)
{
    if(preg_match('/^[1-9][0-9]*$/', $inserted_value))
    {
        $return_value = "integer";
    }
    else
    {
        $return_value = "not integer";
    }

    return $return_value;
}



 ?>