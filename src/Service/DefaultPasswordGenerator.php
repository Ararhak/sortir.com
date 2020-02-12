<?php


namespace App\Service;


class DefaultPasswordGenerator
{
    //Password made with first letter of surname and name (lower case)
    static function defaultPasswordFromNameAndSurname($name, $surname)
    {
        $name = strtolower($name);
        $surname = strtolower($surname);
        return substr($name, 0, 1).$surname;
    }
}