<?php

namespace NoTee;

class NodeFactory
{

    const A = 'a';
    const ABBR = 'abbr';
    const ACRONYM = 'acronym';
    const ADDRESS = 'address';
    const APPLET = 'applet';
    const AREA = 'area';
    const ARTICLE = 'article';
    const ASIDE = 'aside';
    const AUDIO = 'audio';
    const BASE = 'base';
    const BASEFONT = 'basefont';
    const B = 'b';
    const BDO = 'bdo';
    const BGSOUND = 'bgsound';
    const BIG = 'big';
    const BLINK = 'blink';
    const BLOCKQUOTE = 'blockquote';
    const BODY = 'body';
    const BR = 'br';
    const BUTTON = 'button';
    const CANVAS = 'canvas';
    const CAPTION = 'caption';
    const CENTER = 'center';
    const CITE = 'cite';
    const CODE = 'code';
    const COL = 'col';
    const COLGROUP = 'colgroup';
    const COMMAND = 'command';
    const DATALIST = 'datalist';
    const DD = 'dd';
    const DEL = 'del';
    const DETAILS = 'details';
    const DFN = 'dfn';
    const DIV = 'div';
    const DL = 'dl';
    const DT = 'dt';
    const EMBED = 'embed';
    const EM = 'em';
    const FIELDSET = 'fieldset';
    const FIGCAPTION = 'figcaption';
    const FIGURE = 'figure';
    const FONT = 'font';
    const FOOTER = 'footer';
    const FORM = 'form';
    const FRAME = 'frame';
    const FRAMESET = 'frameset';
    const H1 = 'h1';
    const H2 = 'h2';
    const H3 = 'h3';
    const H4 = 'h4';
    const H5 = 'h5';
    const H6 = 'h6';
    const HEADER = 'header';
    const HEAD = 'head';
    const HGROUP = 'hgroup';
    const HR = 'hr';
    const HTML = 'html';
    const IFRAME = 'iframe';
    const I = 'i';
    const IMG = 'img';
    const INPUT = 'input';
    const INS = 'ins';
    const ISINDEX = 'isindex';
    const KBD = 'kbd';
    const KEYGEN = 'keygen';
    const LABEL = 'label';
    const LEGEND = 'legend';
    const LI = 'li';
    const LINK = 'link';
    const LISTING = 'listing';
    const MAP = 'map';
    const MARK = 'mark';
    const MARQUEE = 'marquee';
    const MATH = 'math';
    const MENU = 'menu';
    const META = 'meta';
    const METER = 'meter';
    const NAV = 'nav';
    const NEXTID = 'nextid';
    const NOBR = 'nobr';
    const NOEMBED = 'noembed';
    const NOFRAMES = 'noframes';
    const NOSCRIPT = 'noscript';
    const OBJECT = 'object';
    const OL = 'ol';
    const OPTGROUP = 'optgroup';
    const OPTION = 'option';
    const OUTPUT = 'output';
    const PARAM = 'param';
    const PLAINTEXT = 'plaintext';
    const P = 'p';
    const PRE = 'pre';
    const PROGRESS = 'progress';
    const Q = 'q';
    const RP = 'rp';
    const RT = 'rt';
    const RUBY = 'ruby';
    const SAMP = 'samp';
    const SCRIPT = 'script';
    const SECTION = 'section';
    const SELECT = 'select';
    const SMALL = 'small';
    const SOURCE = 'source';
    const SPACER = 'spacer';
    const SPAN = 'span';
    const S = 's';
    const STRIKE = 'strike';
    const STRONG = 'strong';
    const STYLE = 'style';
    const SUB = 'sub';
    const SUP = 'sup';
    const SUMMARY = 'summary';
    const SVG = 'svg';
    const TABLE = 'table';
    const TBODY = 'tbody';
    const TD = 'td';
    const TEXTAREA = 'textarea';
    const TFOOT = 'tfoot';
    const THEAD = 'thead';
    const TH = 'th';
    const TIME = 'time';
    const TITLE = 'title';
    const TR = 'tr';
    const TT = 'tt';
    const UL = 'ul';
    const U = 'u';
    const _VAR = 'var';
    const VIDEO = 'video';
    const WBR = 'wbr';
    const XMP = 'xmp';

    public static function create($name, $arguments)
    {
        if(
            count($arguments) === 0
            || !is_array($arguments[0])
            || count($arguments[0]) === 0
            || reset($arguments[0]) instanceof Node
        ) {
            return new DefaultNode($name, [], static::flatten($arguments));
        }

        return new DefaultNode($name, array_shift($arguments), static::flatten($arguments));
    }

    private static function flatten($arguments)
    {
        $result = [];
        foreach($arguments as $argument) {
            if($argument === null) {
                // ignore null
            } elseif($argument instanceof Node || is_string($argument)) {
                $result[] = $argument;
            } else {
                foreach($argument as $node) {
                    $result[] = $node;
                }
            }
        }
        return $result;
    }

    public static function text($text)
    {
        return new TextNode($text);
    }

    public static function raw($text)
    {
        return new Raw($text);
    }

    public static function rawAttr($raw)
    {
        return new RawAttribute($raw);
    }
}
