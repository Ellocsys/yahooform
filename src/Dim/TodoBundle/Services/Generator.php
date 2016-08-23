<?php

namespace Dim\TodoBundle\Services;

//Генеравтор паролей для сброса пароля
// На надежное решение не претендует, но для целей тестового задания пойдет
class Generator
{
    public function randomPassword($len = 10)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $len; ++$i) {
            $n = mt_rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);
    }
}
