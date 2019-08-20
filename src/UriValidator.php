<?php

declare(strict_types=1);

namespace NoTee;


use VDB\Uri\Exception\UriSyntaxException;
use VDB\Uri\Uri;

/**
 * Class UriValidator
 *
 * Ensure that attributes, containing URI only contain secure schemes (e.g. "javascript:" is not allowed for security)
 *
 * @package NoTee
 */
class UriValidator implements UriValidatorInterface
{
    protected const SCHEME_WHITELIST = [
        '',
        'http',
        'https',
        'ftp',
        'ftps',
        'sftp'
    ];

    public function isValid(string $uri) : bool
    {
        try {
            $uriObject = new Uri($uri);
        } catch(UriSyntaxException $ex) {
            return false;
        }
        $scheme = mb_strtolower($uriObject->getScheme());
        return $uriObject->getScheme() === null || in_array($scheme, static::SCHEME_WHITELIST);
    }
}
