<?php

namespace NoTee;


class URL implements URLAttribute
{

    private $url;

    public function __construct($base, array $parameter)
    {
        $this->url = $base . '?' . http_build_query($parameter);
    }

    public function toString()
    {
        return $this->url;
    }

}
