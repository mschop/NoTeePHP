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

    protected static $urlAttributes = [
        'action' => true,
        'archive' => true,
        'cite' => true,
        'classid' => true,
        'codebase' => true,
        'data' => true,
        'formaction' => true,
        'href' => true,
        'icon' => true,
        'longdesc' => true,
        'manifest' => true,
        'poster' => true,
        'src' => true,
        'usemap' => true,
    ];

    private $useAttributeValidation;
    private $useAttributeNameValidation;

    /**
     * NodeFactory constructor.
     * @param $useAttributeValidation
     * @param $useAttributeNameValidation
     */
    public function __construct($useAttributeValidation = true, $useAttributeNameValidation = true)
    {
        $this->useAttributeValidation = $useAttributeValidation;
        $this->useAttributeNameValidation = $useAttributeNameValidation;
    }

    public function create($name, $arguments)
    {
        if(
            !isset($arguments[0])
            || !is_array($arguments[0])
            || reset($arguments[0]) instanceof Node
        ) {
            return new DefaultNode($name, [], static::flatten($arguments));
        }

        $attributes = array_shift($arguments);
        if($this->useAttributeValidation) {
            static::validateAttributes($attributes);
        }
        return new DefaultNode($name, $attributes, static::flatten($arguments));
    }

    /**
     * @param array $attributes
     * @throws \InvalidArgumentException
     */
    private function validateAttributes(array $attributes)
    {
        foreach($attributes as $key => $value) {
            static::validateAttribute($key, $value);
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @throws \InvalidArgumentException
     */
    private function validateAttribute($key, $value)
    {
        if($this->useAttributeNameValidation && !static::isValidAttributeName($key)){
            throw new \InvalidArgumentException("invalid attribute name $key");
        }
        if(array_key_exists($key, static::$urlAttributes)) {
            if(!$value instanceof URLAttribute) {
                throw new \InvalidArgumentException("attribute $key has to be instance of URLAttribute");
            }
        }
    }

    /**
     * @param string $attributeName
     * @return bool
     */
    private function isValidAttributeName($attributeName)
    {
        $regex = '/^[0-9a-z-_]*$/i';
        return preg_match($regex, $attributeName) ? true : false;
    }

    /**
     * @param array $arguments
     * @return array
     */
    private static function flatten(array $arguments)
    {
        $result = [];
        foreach($arguments as $argument) {
            if(is_array($argument)) {
                $result = array_merge($result, $argument);
            } elseif($argument !== null) {
                $result[] = $argument;
            }
        }
        return $result;
    }

    public function a() { return $this->create(NodeFactory::A, func_get_args()); }
    public function abbr() { return $this->create(NodeFactory::ABBR, func_get_args()); }
    public function acronym() { return $this->create(NodeFactory::ACRONYM, func_get_args()); }
    public function address() { return $this->create(NodeFactory::ADDRESS, func_get_args()); }
    public function applet() { return $this->create(NodeFactory::APPLET, func_get_args()); }
    public function area() { return $this->create(NodeFactory::AREA, func_get_args()); }
    public function article() { return $this->create(NodeFactory::ARTICLE, func_get_args()); }
    public function aside() { return $this->create(NodeFactory::ASIDE, func_get_args()); }
    public function audio() { return $this->create(NodeFactory::AUDIO, func_get_args()); }
    public function base() { return $this->create(NodeFactory::BASE, func_get_args()); }
    public function basefont() { return $this->create(NodeFactory::BASEFONT, func_get_args()); }
    public function b() { return $this->create(NodeFactory::B, func_get_args()); }
    public function bdo() { return $this->create(NodeFactory::BDO, func_get_args()); }
    public function bgsound() { return $this->create(NodeFactory::BGSOUND, func_get_args()); }
    public function big() { return $this->create(NodeFactory::BIG, func_get_args()); }
    public function blink() { return $this->create(NodeFactory::BLINK, func_get_args()); }
    public function blockquote() { return $this->create(NodeFactory::BLOCKQUOTE, func_get_args()); }
    public function body() { return $this->create(NodeFactory::BODY, func_get_args()); }
    public function br() { return $this->create(NodeFactory::BR, func_get_args()); }
    public function button() { return $this->create(NodeFactory::BUTTON, func_get_args()); }
    public function canvas() { return $this->create(NodeFactory::CANVAS, func_get_args()); }
    public function caption() { return $this->create(NodeFactory::CAPTION, func_get_args()); }
    public function center() { return $this->create(NodeFactory::CENTER, func_get_args()); }
    public function cite() { return $this->create(NodeFactory::CITE, func_get_args()); }
    public function code() { return $this->create(NodeFactory::CODE, func_get_args()); }
    public function col() { return $this->create(NodeFactory::COL, func_get_args()); }
    public function colgroup() { return $this->create(NodeFactory::COLGROUP, func_get_args()); }
    public function command() { return $this->create(NodeFactory::COMMAND, func_get_args()); }
    public function datalist() { return $this->create(NodeFactory::DATALIST, func_get_args()); }
    public function dd() { return $this->create(NodeFactory::DD, func_get_args()); }
    public function del() { return $this->create(NodeFactory::DEL, func_get_args()); }
    public function details() { return $this->create(NodeFactory::DETAILS, func_get_args()); }
    public function dfn() { return $this->create(NodeFactory::DFN, func_get_args()); }
    public function div() { return $this->create(NodeFactory::DIV, func_get_args()); }
    public function dl() { return $this->create(NodeFactory::DL, func_get_args()); }
    public function dt() { return $this->create(NodeFactory::DT, func_get_args()); }
    public function embed() { return $this->create(NodeFactory::EMBED, func_get_args()); }
    public function em() { return $this->create(NodeFactory::EM, func_get_args()); }
    public function fieldset() { return $this->create(NodeFactory::FIELDSET, func_get_args()); }
    public function figcaption() { return $this->create(NodeFactory::FIGCAPTION, func_get_args()); }
    public function figure() { return $this->create(NodeFactory::FIGURE, func_get_args()); }
    public function font() { return $this->create(NodeFactory::FONT, func_get_args()); }
    public function footer() { return $this->create(NodeFactory::FOOTER, func_get_args()); }
    public function form() { return $this->create(NodeFactory::FORM, func_get_args()); }
    public function frame() { return $this->create(NodeFactory::FRAME, func_get_args()); }
    public function frameset() { return $this->create(NodeFactory::FRAMESET, func_get_args()); }
    public function h1() { return $this->create(NodeFactory::H1, func_get_args()); }
    public function h2() { return $this->create(NodeFactory::H2, func_get_args()); }
    public function h3() { return $this->create(NodeFactory::H3, func_get_args()); }
    public function h4() { return $this->create(NodeFactory::H4, func_get_args()); }
    public function h5() { return $this->create(NodeFactory::H5, func_get_args()); }
    public function h6() { return $this->create(NodeFactory::H6, func_get_args()); }
    public function header() { return $this->create(NodeFactory::HEADER, func_get_args()); }
    public function head() { return $this->create(NodeFactory::HEAD, func_get_args()); }
    public function hgroup() { return $this->create(NodeFactory::HGROUP, func_get_args()); }
    public function hr() { return $this->create(NodeFactory::HR, func_get_args()); }
    public function NodeFactory() { return $this->create(NodeFactory::NodeFactory, func_get_args()); }
    public function iframe() { return $this->create(NodeFactory::IFRAME, func_get_args()); }
    public function i() { return $this->create(NodeFactory::I, func_get_args()); }
    public function img() { return $this->create(NodeFactory::IMG, func_get_args()); }
    public function input() { return $this->create(NodeFactory::INPUT, func_get_args()); }
    public function ins() { return $this->create(NodeFactory::INS, func_get_args()); }
    public function isindex() { return $this->create(NodeFactory::ISINDEX, func_get_args()); }
    public function kbd() { return $this->create(NodeFactory::KBD, func_get_args()); }
    public function keygen() { return $this->create(NodeFactory::KEYGEN, func_get_args()); }
    public function label() { return $this->create(NodeFactory::LABEL, func_get_args()); }
    public function legend() { return $this->create(NodeFactory::LEGEND, func_get_args()); }
    public function li() { return $this->create(NodeFactory::LI, func_get_args()); }
    public function link() { return $this->create(NodeFactory::LINK, func_get_args()); }
    public function listing() { return $this->create(NodeFactory::LISTING, func_get_args()); }
    public function map() { return $this->create(NodeFactory::MAP, func_get_args()); }
    public function mark() { return $this->create(NodeFactory::MARK, func_get_args()); }
    public function marquee() { return $this->create(NodeFactory::MARQUEE, func_get_args()); }
    public function math() { return $this->create(NodeFactory::MATH, func_get_args()); }
    public function menu() { return $this->create(NodeFactory::MENU, func_get_args()); }
    public function meta() { return $this->create(NodeFactory::META, func_get_args()); }
    public function meter() { return $this->create(NodeFactory::METER, func_get_args()); }
    public function nav() { return $this->create(NodeFactory::NAV, func_get_args()); }
    public function nextid() { return $this->create(NodeFactory::NEXTID, func_get_args()); }
    public function nobr() { return $this->create(NodeFactory::NOBR, func_get_args()); }
    public function noembed() { return $this->create(NodeFactory::NOEMBED, func_get_args()); }
    public function noframes() { return $this->create(NodeFactory::NOFRAMES, func_get_args()); }
    public function noscript() { return $this->create(NodeFactory::NOSCRIPT, func_get_args()); }
    public function object() { return $this->create(NodeFactory::OBJECT, func_get_args()); }
    public function ol() { return $this->create(NodeFactory::UOL, func_get_args()); }
    public function optgroup() { return $this->create(NodeFactory::OPTGROUP, func_get_args()); }
    public function option() { return $this->create(NodeFactory::OPTION, func_get_args()); }
    public function output() { return $this->create(NodeFactory::OUTPUT, func_get_args()); }
    public function param() { return $this->create(NodeFactory::PARAM, func_get_args()); }
    public function plaintext() { return $this->create(NodeFactory::PLAINTEXT, func_get_args()); }
    public function p() { return $this->create(NodeFactory::P, func_get_args()); }
    public function pre() { return $this->create(NodeFactory::PRE, func_get_args()); }
    public function progress() { return $this->create(NodeFactory::PROGRESS, func_get_args()); }
    public function q() { return $this->create(NodeFactory::Q, func_get_args()); }
    public function rp() { return $this->create(NodeFactory::RP, func_get_args()); }
    public function rt() { return $this->create(NodeFactory::RT, func_get_args()); }
    public function ruby() { return $this->create(NodeFactory::RUBY, func_get_args()); }
    public function samp() { return $this->create(NodeFactory::SAMP, func_get_args()); }
    public function script() { return $this->create(NodeFactory::SCRIPT, func_get_args()); }
    public function section() { return $this->create(NodeFactory::SECTION, func_get_args()); }
    public function select() { return $this->create(NodeFactory::SELECT, func_get_args()); }
    public function small() { return $this->create(NodeFactory::SMALL, func_get_args()); }
    public function source() { return $this->create(NodeFactory::SOURCE, func_get_args()); }
    public function spacer() { return $this->create(NodeFactory::SPACER, func_get_args()); }
    public function span() { return $this->create(NodeFactory::SPAN, func_get_args()); }
    public function s() { return $this->create(NodeFactory::S, func_get_args()); }
    public function strike() { return $this->create(NodeFactory::STRIKE, func_get_args()); }
    public function strong() { return $this->create(NodeFactory::STRONG, func_get_args()); }
    public function style() { return $this->create(NodeFactory::STYLE, func_get_args()); }
    public function sub() { return $this->create(NodeFactory::SUB, func_get_args()); }
    public function sup() { return $this->create(NodeFactory::SUP, func_get_args()); }
    public function summary() { return $this->create(NodeFactory::SUMMARY, func_get_args()); }
    public function svg() { return $this->create(NodeFactory::SVG, func_get_args()); }
    public function table() { return $this->create(NodeFactory::TABLE, func_get_args()); }
    public function tbody() { return $this->create(NodeFactory::TBODY, func_get_args()); }
    public function td() { return $this->create(NodeFactory::TD, func_get_args()); }
    public function textarea() { return $this->create(NodeFactory::TEXTAREA, func_get_args()); }
    public function tfoot() { return $this->create(NodeFactory::TFOOT, func_get_args()); }
    public function thead() { return $this->create(NodeFactory::THEAD, func_get_args()); }
    public function th() { return $this->create(NodeFactory::TH, func_get_args()); }
    public function time() { return $this->create(NodeFactory::TIME, func_get_args()); }
    public function title() { return $this->create(NodeFactory::TITLE, func_get_args()); }
    public function tr() { return $this->create(NodeFactory::TR, func_get_args()); }
    public function tt() { return $this->create(NodeFactory::TT, func_get_args()); }
    public function ul() { return $this->create(NodeFactory::UL, func_get_args()); }
    public function u() { return $this->create(NodeFactory::U, func_get_args()); }
    public function _var() { return $this->create(NodeFactory::_VAR, func_get_args()); }
    public function video() { return $this->create(NodeFactory::VIDEO, func_get_args()); }
    public function wbr() { return $this->create(NodeFactory::WBR, func_get_args()); }
    public function xmp() { return $this->create(NodeFactory::XMP, func_get_args()); }


    /**
     * @param string $text
     * @return TextNode
     */
    public static function text($text)
    {
        return new TextNode($text);
    }

    /**
     * @param string $text
     * @return Raw
     */
    public static function raw($text)
    {
        return new Raw($text);
    }

}
