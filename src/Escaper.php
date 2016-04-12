<?php

namespace NoTee;


interface Escaper
{
    /**
     * @param string $value
     * @return string
     */
    public function escapeHtml($value);

    /**
     * @param string $value
     * @return string
     */
    public function escapeAttribute($value);

}
