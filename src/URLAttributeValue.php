<?php

namespace NoTee;


class URLAttributeValue implements AttributeValue
{

    private $url;

    public function __construct($base, array $parameter)
    {
        $this->url = $base . '?' . http_build_query($parameter);
    }

    public function __toString()
    {
        return $this->url;
    }

}
