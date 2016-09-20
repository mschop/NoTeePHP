<?php

namespace NoTee;


interface Escaper
{
    public function escapeHtml(string $value) : string;
    public function escapeAttribute(string $value) : string;
}
