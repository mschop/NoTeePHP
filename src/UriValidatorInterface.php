<?php


namespace NoTee;


interface UriValidatorInterface
{
    public function isValid(string $uri): bool;
}