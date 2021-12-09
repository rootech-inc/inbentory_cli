<?php


class anton
{
    function set_session($arr_of_data)
    {
        for ($i = 0, $iMax = count($arr_of_data); $i < $iMax; $i++)
        {
            print_r($arr_of_data[$i]);
        }
    }

    function post($field_name) // post form
    {
        if(isset($_POST[$field_name]))
        {
            return htmlentities($_POST[$field_name]);
        }

        return 'empty';
    }

    function get($field_name) // get form
    {
        if(isset($_GET[$field_name]))
        {
            return htmlentities($_GET[$field_name]);
        }

        return 'empty';
    }

}