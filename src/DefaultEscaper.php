<?php

declare(strict_types=1);

namespace NoTee;

use InvalidArgumentException;

/**
 * Class SecureContextEscaper
 *
 * you should not use this class for escaping, if you do not really know what you are doing.
 * This class relies on the NoTeePHP behavior. Some cases, relevant for template engines are not covered.
 *
 * @package NoTee
 */
class DefaultEscaper implements EscaperInterface
{
    protected const SUPPORTED_ENCODINGS = [
        'iso-8859-1',   'iso8859-1',    'iso-8859-5',   'iso8859-5',
        'iso-8859-15',  'iso8859-15',   'utf-8',        'cp866',
        'ibm866',       '866',          'cp1251',       'windows-1251',
        'win-1251',     '1251',         'cp1252',       'windows-1252',
        '1252',         'koi8-r',       'koi8-ru',      'koi8r',
        'big5',         '950',          'gb2312',       '936',
        'big5-hkscs',   'shift_jis',    'sjis',         'sjis-win',
        'cp932',        '932',          'euc-jp',       'eucjp',
        'eucjp-win',    'macroman'
    ];

    protected string $encoding;

    /**
     * EscaperForNoTeeContext constructor.
     * @param string $encoding
     * @throws \InvalidArgumentException
     */
    public function __construct(string $encoding)
    {
        static::checkEncoding($encoding);
        $this->encoding = $encoding;
    }

    public function escapeHtml(string $value) : string
    {
        /*
         * NoTeePHP knows the context of a text. Therefore we can be sure, that this escape function is not used for
         * attributes. So we can use ENT_NOQUOTES, because we we only need to escape '<' and '>'.
         */
        return htmlspecialchars($value, ENT_NOQUOTES, $this->encoding, false);
    }

    public function escapeAttribute(string $value) : string
    {
        // ENT_COMPAT because NoTeePHP always uses double quotes for attributes
        return htmlspecialchars($value, ENT_COMPAT, $this->encoding, false);
    }

    /**
     * @param string $encoding
     * @throws InvalidArgumentException
     */
    protected static function checkEncoding(string $encoding)
    {
        $encoding = strtolower($encoding);
        if(!in_array($encoding, static::SUPPORTED_ENCODINGS)) {
            throw new \InvalidArgumentException('Invalid encoding. Please use an encoding allowed for htmlspecialchars');
        }
    }
}
