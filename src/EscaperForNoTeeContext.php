<?php

declare(strict_types=1);

namespace NoTee;


/**
 * Class SecureContextEscaper
 *
 * you should not use this class for escaping, if you do not really know what you are doing.
 * This class relies on the NoTeePHP behavior. Some cases, relevant for template engines are not covered.
 *
 * @package NoTee
 */
class EscaperForNoTeeContext implements Escaper
{
    protected static $supportedEncodings = [
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

    protected $encoding;

    /**
     * EscaperForNoTeeContext constructor.
     * @param string $encoding
     * @throws \InvalidArgumentException
     */
    public function __construct($encoding)
    {
        $encoding = strtolower($encoding);
        if(!in_array($encoding, static::$supportedEncodings)) {
            throw new \InvalidArgumentException('Invalid encoding. Please use an encoding allowed for htmlspecialchars');
        }
        $this->encoding = $encoding;
    }


    /*
     * I used ENT_COMPAT because escaping single quotes is only relevant, if attribute are written with single quotes.
     * Since NoTeePHP does not use single quotes, using ENT_COMPAT is enough.
     */

    public function escapeHtml(string $value) : string
    {
        return htmlspecialchars($value, ENT_COMPAT, $this->encoding, false);
    }

    public function escapeAttribute(string $value) : string
    {
        return htmlspecialchars($value, ENT_COMPAT, $this->encoding, false);
    }

}
