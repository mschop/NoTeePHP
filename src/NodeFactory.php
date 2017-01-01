<?php

declare(strict_types=1);

namespace NoTee;

use VDB\Uri\Exception\UriSyntaxException;
use VDB\Uri\Uri;

class NodeFactory
{

    protected $escaper;
    protected $debug;
    protected $attributeEvents = [];
    protected $classEvents = [];
    protected $tagEvents = [];

    protected static $uriAttributes = [
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

    /** @var  UriValidator */
    protected $uriValidator;

    /**
     * NodeFactory constructor.
     * @param string $encoding
     * @param bool $debug
     * @throws \InvalidArgumentException
     */
    public function __construct(string $encoding, bool $debug = false) {
        $this->escaper = new EscaperForNoTeeContext($encoding);
        $this->debug = $debug;
        $this->uriValidator = new UriValidator();
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return DefaultNode
     * @throws \InvalidArgumentException
     * @throws UriSyntaxException
     */
    public function create(string $name, array $arguments) : DefaultNode
    {
        $debugAttributes = [];
        if($this->debug) {
            $debugAttributes = [
                'data-source' => $this->generateDebugSource()
            ];
        }

        if(
            !isset($arguments[0])
            || !is_array($arguments[0])
            || reset($arguments[0]) instanceof Node
            || reset($arguments[0]) === null
        ) {
            list($attributes, $children) = $this->triggerEvents($name, $debugAttributes, static::flatten($arguments));
            return new DefaultNode($name, $this->escaper, $attributes, $children);
        }

        $attributes = array_shift($arguments);
        $this->validateAttributes($attributes);
        list($attributes, $children) = $this->triggerEvents(
            $name,
            array_merge($attributes, $debugAttributes),
            static::flatten($arguments)
        );
        return new DefaultNode($name, $this->escaper, $attributes, $children);
    }

    /**
     * Get information on where a node has been created
     * @return string
     */
    protected function generateDebugSource()
    {
        $trace = debug_backtrace();
        $callee = $trace[2];
        return $callee['file'] . ':' . $callee['line'];
    }

    /**
     * @param array $attributes
     * @throws \InvalidArgumentException
     * @throws UriSyntaxException
     */
    protected function validateAttributes(array $attributes)
    {
        foreach($attributes as $key => $value) {
            if(!$this->isValidAttributeKey($key)) {
                throw new \InvalidArgumentException('invalid attribute name ' . $key);
            }
            if(!$this->isValidAttributeValue($key, $value)) {
                throw new \InvalidArgumentException('invalid attribute value for ' . $key);
            }
        }
    }

    protected function isValidAttributeKey(string $key) : bool
    {
        if(!preg_match('/^[0-9a-z-_]*$/i', $key)) {
            return false;
        }
        return true;
    }

    protected function isValidAttributeValue(string $key, string $value) : bool
    {
        if(array_key_exists($key, static::$uriAttributes)) {
            return $this->uriValidator->isValid($value);
        }
        return true;
    }

    /**
     * An api consumer can pass arrays coming from function calls as children to the method "create". Elements in this
     * array are direct children of the node created with the method "create". Those must therefore be flattened.
     * @param array $arguments
     * @return array
     */
    protected static function flatten(array $arguments) : array
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

    /**
     * @param string $tagName
     * @param array $attributes
     * @param array $children
     * @return array
     */
    protected function triggerEvents(string $tagName, array $attributes, array $children) : array
    {
        list($attributes, $children) = $this->triggerTagEvents($tagName, $attributes, $children);
        list($attributes, $children) = $this->triggerClassEvents($attributes, $children);
        list($attributes, $children) = $this->triggerAttributeEvents($attributes, $children);
        return [$attributes, $children];
    }

    protected function triggerTagEvents(string $tagName, array $attributes, array $children) : array
    {
        $lowerTagName = strtolower($tagName);
        foreach($this->tagEvents as $tagEvent) {
            if(strtolower($tagEvent[0]) === $lowerTagName) {
                list($attributes, $children) = call_user_func_array($tagEvent[1], [$attributes, $children]);
            }
        }
        return [$attributes, $children];
    }

    protected function triggerClassEvents(array $attributes, array $children) : array
    {
        foreach($this->classEvents as $classEvent) {
            if(isset($attributes['class']) && in_array($classEvent[0], explode(' ', $attributes['class']))) {
                list($attributes, $children) = call_user_func_array($classEvent[1], [$attributes, $children]);
            }
        }
        return [$attributes, $children];
    }

    protected function triggerAttributeEvents(array $attributes, array $children) : array
    {
        foreach($this->attributeEvents as $attributeEvent) {
            if(
                isset($attributes[$attributeEvent[0]])
                && $attributes[$attributeEvent[0]] === $attributeEvent[1]
            ) {
                list($attributes, $children) = call_user_func_array($attributeEvent[2], [$attributes, $children]);
            }
        }
        return [$attributes, $children];
    }

    public function onClass(string $class, callable $callable)
    {
        $this->classEvents[] = [$class, $callable];
    }

    public function onAttr(string $key, string $value, callable $callable)
    {
        $this->attributeEvents[] = [$key, $value, $callable];
    }

    public function onTag(string $tag, callable $callable)
    {
        $this->tagEvents[] = [$tag, $callable];
    }


    public function a() : DefaultNode { return $this->create('a', func_get_args()); }
    public function abbr() : DefaultNode { return $this->create('abbr', func_get_args()); }
    public function acronym() : DefaultNode { return $this->create('acronym', func_get_args()); }
    public function address() : DefaultNode { return $this->create('address', func_get_args()); }
    public function applet() : DefaultNode { return $this->create('applet', func_get_args()); }
    public function area() : DefaultNode { return $this->create('area', func_get_args()); }
    public function article() : DefaultNode { return $this->create('article', func_get_args()); }
    public function aside() : DefaultNode { return $this->create('aside', func_get_args()); }
    public function audio() : DefaultNode { return $this->create('audio', func_get_args()); }
    public function base() : DefaultNode { return $this->create('base', func_get_args()); }
    public function basefont() : DefaultNode { return $this->create('basefont', func_get_args()); }
    public function b() : DefaultNode { return $this->create('b', func_get_args()); }
    public function bdo() : DefaultNode { return $this->create('bdo', func_get_args()); }
    public function bgsound() : DefaultNode { return $this->create('bgsound', func_get_args()); }
    public function big() : DefaultNode { return $this->create('big', func_get_args()); }
    public function blink() : DefaultNode { return $this->create('blink', func_get_args()); }
    public function blockquote() : DefaultNode { return $this->create('blockquote', func_get_args()); }
    public function body() : DefaultNode { return $this->create('body', func_get_args()); }
    public function br() : DefaultNode { return $this->create('br', func_get_args()); }
    public function button() : DefaultNode { return $this->create('button', func_get_args()); }
    public function canvas() : DefaultNode { return $this->create('canvas', func_get_args()); }
    public function caption() : DefaultNode { return $this->create('caption', func_get_args()); }
    public function center() : DefaultNode { return $this->create('center', func_get_args()); }
    public function cite() : DefaultNode { return $this->create('cite', func_get_args()); }
    public function code() : DefaultNode { return $this->create('code', func_get_args()); }
    public function col() : DefaultNode { return $this->create('col', func_get_args()); }
    public function colgroup() : DefaultNode { return $this->create('colgroup', func_get_args()); }
    public function command() : DefaultNode { return $this->create('command', func_get_args()); }
    public function datalist() : DefaultNode { return $this->create('datalist', func_get_args()); }
    public function dd() : DefaultNode { return $this->create('dd', func_get_args()); }
    public function del() : DefaultNode { return $this->create('del', func_get_args()); }
    public function details() : DefaultNode { return $this->create('details', func_get_args()); }
    public function dfn() : DefaultNode { return $this->create('dfn', func_get_args()); }
    public function div() : DefaultNode { return $this->create('div', func_get_args()); }
    public function dl() : DefaultNode { return $this->create('dl', func_get_args()); }
    public function dt() : DefaultNode { return $this->create('dt', func_get_args()); }
    public function embed() : DefaultNode { return $this->create('embed', func_get_args()); }
    public function em() : DefaultNode { return $this->create('em', func_get_args()); }
    public function fieldset() : DefaultNode { return $this->create('fieldset', func_get_args()); }
    public function figcaption() : DefaultNode { return $this->create('figcaption', func_get_args()); }
    public function figure() : DefaultNode { return $this->create('figure', func_get_args()); }
    public function font() : DefaultNode { return $this->create('font', func_get_args()); }
    public function footer() : DefaultNode { return $this->create('footer', func_get_args()); }
    public function form() : DefaultNode { return $this->create('form', func_get_args()); }
    public function frame() : DefaultNode { return $this->create('frame', func_get_args()); }
    public function frameset() : DefaultNode { return $this->create('frameset', func_get_args()); }
    public function h1() : DefaultNode { return $this->create('h1', func_get_args()); }
    public function h2() : DefaultNode { return $this->create('h2', func_get_args()); }
    public function h3() : DefaultNode { return $this->create('h3', func_get_args()); }
    public function h4() : DefaultNode { return $this->create('h4', func_get_args()); }
    public function h5() : DefaultNode { return $this->create('h5', func_get_args()); }
    public function h6() : DefaultNode { return $this->create('h6', func_get_args()); }
    public function header() : DefaultNode { return $this->create('header', func_get_args()); }
    public function head() : DefaultNode { return $this->create('head', func_get_args()); }
    public function hgroup() : DefaultNode { return $this->create('hgroup', func_get_args()); }
    public function hr() : DefaultNode { return $this->create('hr', func_get_args()); }
    public function html() : DefaultNode { return $this->create('html', func_get_args()); }
    public function iframe() : DefaultNode { return $this->create('iframe', func_get_args()); }
    public function i() : DefaultNode { return $this->create('i', func_get_args()); }
    public function img() : DefaultNode { return $this->create('img', func_get_args()); }
    public function input() : DefaultNode { return $this->create('input', func_get_args()); }
    public function ins() : DefaultNode { return $this->create('ins', func_get_args()); }
    public function isindex() : DefaultNode { return $this->create('isindex', func_get_args()); }
    public function kbd() : DefaultNode { return $this->create('kbd', func_get_args()); }
    public function keygen() : DefaultNode { return $this->create('keygen', func_get_args()); }
    public function label() : DefaultNode { return $this->create('label', func_get_args()); }
    public function legend() : DefaultNode { return $this->create('legend', func_get_args()); }
    public function li() : DefaultNode { return $this->create('li', func_get_args()); }
    public function link() : DefaultNode { return $this->create('link', func_get_args()); }
    public function listing() : DefaultNode { return $this->create('listing', func_get_args()); }
    public function map() : DefaultNode { return $this->create('map', func_get_args()); }
    public function mark() : DefaultNode { return $this->create('mark', func_get_args()); }
    public function marquee() : DefaultNode { return $this->create('marquee', func_get_args()); }
    public function math() : DefaultNode { return $this->create('math', func_get_args()); }
    public function menu() : DefaultNode { return $this->create('menu', func_get_args()); }
    public function meta() : DefaultNode { return $this->create('meta', func_get_args()); }
    public function meter() : DefaultNode { return $this->create('meter', func_get_args()); }
    public function nav() : DefaultNode { return $this->create('nav', func_get_args()); }
    public function nextid() : DefaultNode { return $this->create('nextid', func_get_args()); }
    public function nobr() : DefaultNode { return $this->create('nobr', func_get_args()); }
    public function noembed() : DefaultNode { return $this->create('noembed', func_get_args()); }
    public function noframes() : DefaultNode { return $this->create('noframes', func_get_args()); }
    public function noscript() : DefaultNode { return $this->create('noscript', func_get_args()); }
    public function object() : DefaultNode { return $this->create('object', func_get_args()); }
    public function ol() : DefaultNode { return $this->create('uol', func_get_args()); }
    public function optgroup() : DefaultNode { return $this->create('optgroup', func_get_args()); }
    public function option() : DefaultNode { return $this->create('option', func_get_args()); }
    public function output() : DefaultNode { return $this->create('output', func_get_args()); }
    public function param() : DefaultNode { return $this->create('param', func_get_args()); }
    public function plaintext() : DefaultNode { return $this->create('plaintext', func_get_args()); }
    public function p() : DefaultNode { return $this->create('p', func_get_args()); }
    public function pre() : DefaultNode { return $this->create('pre', func_get_args()); }
    public function progress() : DefaultNode { return $this->create('progress', func_get_args()); }
    public function q() : DefaultNode { return $this->create('q', func_get_args()); }
    public function rp() : DefaultNode { return $this->create('rp', func_get_args()); }
    public function rt() : DefaultNode { return $this->create('rt', func_get_args()); }
    public function ruby() : DefaultNode { return $this->create('ruby', func_get_args()); }
    public function samp() : DefaultNode { return $this->create('samp', func_get_args()); }
    public function script() : DefaultNode { return $this->create('script', func_get_args()); }
    public function section() : DefaultNode { return $this->create('section', func_get_args()); }
    public function select() : DefaultNode { return $this->create('select', func_get_args()); }
    public function small() : DefaultNode { return $this->create('small', func_get_args()); }
    public function source() : DefaultNode { return $this->create('source', func_get_args()); }
    public function spacer() : DefaultNode { return $this->create('spacer', func_get_args()); }
    public function span() : DefaultNode { return $this->create('span', func_get_args()); }
    public function s() : DefaultNode { return $this->create('s', func_get_args()); }
    public function strike() : DefaultNode { return $this->create('strike', func_get_args()); }
    public function strong() : DefaultNode { return $this->create('strong', func_get_args()); }
    public function style() : DefaultNode { return $this->create('style', func_get_args()); }
    public function sub() : DefaultNode { return $this->create('sub', func_get_args()); }
    public function sup() : DefaultNode { return $this->create('sup', func_get_args()); }
    public function summary() : DefaultNode { return $this->create('summary', func_get_args()); }
    public function svg() : DefaultNode { return $this->create('svg', func_get_args()); }
    public function table() : DefaultNode { return $this->create('table', func_get_args()); }
    public function tbody() : DefaultNode { return $this->create('tbody', func_get_args()); }
    public function td() : DefaultNode { return $this->create('td', func_get_args()); }
    public function textarea() : DefaultNode { return $this->create('textarea', func_get_args()); }
    public function tfoot() : DefaultNode { return $this->create('tfoot', func_get_args()); }
    public function thead() : DefaultNode { return $this->create('thead', func_get_args()); }
    public function th() : DefaultNode { return $this->create('th', func_get_args()); }
    public function time() : DefaultNode { return $this->create('time', func_get_args()); }
    public function title() : DefaultNode { return $this->create('title', func_get_args()); }
    public function tr() : DefaultNode { return $this->create('tr', func_get_args()); }
    public function tt() : DefaultNode { return $this->create('tt', func_get_args()); }
    public function ul() : DefaultNode { return $this->create('ul', func_get_args()); }
    public function u() : DefaultNode { return $this->create('u', func_get_args()); }
    public function _var() : DefaultNode { return $this->create('var', func_get_args()); }
    public function video() : DefaultNode { return $this->create('video', func_get_args()); }
    public function wbr() : DefaultNode { return $this->create('wbr', func_get_args()); }
    public function xmp() : DefaultNode { return $this->create('xmp', func_get_args()); }


    public function text(string $text): TextNode
    {
        return new TextNode($text, $this->escaper);
    }

    public function raw(string $text) : RawNode
    {
        return new RawNode($text);
    }

    public function document(string $doctype, DefaultNode $html) : Document
    {
        return new Document($doctype, $html);
    }

    public function wrapper()
    {
        return new WrapperNode(static::flatten(func_get_args()), $this->escaper);
    }

}
