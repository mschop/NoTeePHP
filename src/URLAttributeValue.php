<?php

namespace NoTee;


class URLAttributeValue implements AttributeValue
{

    private $url;

    public function __construct($base, array $parameter)
    {
        $base = trim($base);
        if(strpos($base, 'javascript:') === 0) {
            throw new \InvalidArgumentException('javascript injection detected for URLAttributeValue');
        }
        $this->url = $base . '?' . http_build_query($parameter);
    }

    public function __toString()
    {
        return $this->url;
    }

}
