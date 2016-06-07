<?php

namespace NoTee;


use VDB\Uri\Uri;

/**
 * Class UriValidator
 *
 * Ensure that attributes, containing URI only contain secure schemes (e.g. "javascript:" is not allowed for security)
 *
 * @package NoTee
 */
class UriValidator
{
    protected static $schemeWhitelist = [
        '',
        'http',
        'https',
        'ftp',
        'ftps',
        'sftp'
    ];

    /**
     * @param $uri
     * @return bool
     */
    public function isValid($uri)
    {
        $uriObject = new Uri($uri);
        return in_array(strtolower($uriObject->getScheme()), static::$schemeWhitelist);
    }
}
