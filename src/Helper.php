<?php

namespace NoTee;


class Helper
{
    public static function map($iteratable, callable $callable, $preserveKey = false)
    {
        $result = [];
        foreach($iteratable as $key => $value) {
            if($preserveKey) {
                $result[$key] = $callable($key, $value);
            } else {
                $result[] = $callable($key, $value);
            }
        }
        return $result;
    }
}