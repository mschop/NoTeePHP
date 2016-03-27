<?php

namespace NoTee;


class RawURL implements URLAttribute
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function toString()
    {
        return $this->url;
    }

}
