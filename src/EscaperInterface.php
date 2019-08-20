<?php

namespace NoTee;


interface EscaperInterface
{
    public function escapeHtml(string $value) : string;
    public function escapeAttribute(string $value) : string;
}
