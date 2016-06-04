<?php

namespace NoTee;

class NodeFactory
{

    private $escaper;
    private $attributeValidator;
    private $debug;
    private $attributeEvents = [];
    private $classEvents = [];
    private $tagEvents = [];

    /**
     * NodeFactory constructor.
     * @param string $encoding
     * @param AttributeValidator $attributeValidator
     * @param bool $debug
     */
    public function __construct(
        $encoding,
        AttributeValidator $attributeValidator = null,
        $debug = false
    ) {
        if($attributeValidator === null) {
            $attributeValidator = new AttributeValidator(true, true);
        }
        $this->escaper = new EscaperForNoTeeContext($encoding);
        $this->attributeValidator = $attributeValidator;
        $this->debug = $debug;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return DefaultNode
     */
    public function create($name, array $arguments)
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

    private function generateDebugSource()
    {
        $trace = debug_backtrace();
        $callee = $trace[2];
        return $callee['file'] . ':' . $callee['line'];
    }

    /**
     * @param array $attributes
     * @throws \InvalidArgumentException
     */
    private function validateAttributes(array $attributes)
    {
        foreach($attributes as $key => $value) {
            if(!$this->attributeValidator->isValid($key, $value)) {
                throw new \InvalidArgumentException('invalid attribute (key or value) ' . $key);
            }
        }
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

    private function triggerEvents($tagName, array $attributes, array $children)
    {
        $result = [$attributes, $children];

        foreach($this->tagEvents as $tagEvent) {
            if(strtolower($tagEvent[0]) === strtolower($tagName)) {
                $result = call_user_func_array($tagEvent[1], $result);
            }
        }

        foreach($this->classEvents as $classEvent) {
            if(isset($attributes['class']) && in_array($classEvent[0], explode(' ', $attributes['class']))) {
                $result = call_user_func_array($classEvent[1], $result);
            }
        }

        foreach($this->attributeEvents as $attributeEvent) {
            if(
                isset($attributes[$attributeEvent[0]])
                && $attributes[$attributeEvent[0]] === $attributeEvent[1]
            ) {
                $result = call_user_func_array($attributeEvent[2], $result);
            }
        }

        return $result;
    }

    public function onClass($class, $callable)
    {
        $this->classEvents[] = [$class, $callable];
    }

    public function onAttr($key, $value, $callable)
    {
        $this->attributeEvents[] = [$key, $value, $callable];
    }

    public function onTag($tag, $callable)
    {
        $this->tagEvents[] = [$tag, $callable];
    }


    public function a() { return $this->create('a', func_get_args()); }
    public function abbr() { return $this->create('abbr', func_get_args()); }
    public function acronym() { return $this->create('acronym', func_get_args()); }
    public function address() { return $this->create('address', func_get_args()); }
    public function applet() { return $this->create('applet', func_get_args()); }
    public function area() { return $this->create('area', func_get_args()); }
    public function article() { return $this->create('article', func_get_args()); }
    public function aside() { return $this->create('aside', func_get_args()); }
    public function audio() { return $this->create('audio', func_get_args()); }
    public function base() { return $this->create('base', func_get_args()); }
    public function basefont() { return $this->create('basefont', func_get_args()); }
    public function b() { return $this->create('b', func_get_args()); }
    public function bdo() { return $this->create('bdo', func_get_args()); }
    public function bgsound() { return $this->create('bgsound', func_get_args()); }
    public function big() { return $this->create('big', func_get_args()); }
    public function blink() { return $this->create('blink', func_get_args()); }
    public function blockquote() { return $this->create('blockquote', func_get_args()); }
    public function body() { return $this->create('body', func_get_args()); }
    public function br() { return $this->create('br', func_get_args()); }
    public function button() { return $this->create('button', func_get_args()); }
    public function canvas() { return $this->create('canvas', func_get_args()); }
    public function caption() { return $this->create('caption', func_get_args()); }
    public function center() { return $this->create('center', func_get_args()); }
    public function cite() { return $this->create('cite', func_get_args()); }
    public function code() { return $this->create('code', func_get_args()); }
    public function col() { return $this->create('col', func_get_args()); }
    public function colgroup() { return $this->create('colgroup', func_get_args()); }
    public function command() { return $this->create('command', func_get_args()); }
    public function datalist() { return $this->create('datalist', func_get_args()); }
    public function dd() { return $this->create('dd', func_get_args()); }
    public function del() { return $this->create('del', func_get_args()); }
    public function details() { return $this->create('details', func_get_args()); }
    public function dfn() { return $this->create('dfn', func_get_args()); }
    public function div() { return $this->create('div', func_get_args()); }
    public function dl() { return $this->create('dl', func_get_args()); }
    public function dt() { return $this->create('dt', func_get_args()); }
    public function embed() { return $this->create('embed', func_get_args()); }
    public function em() { return $this->create('em', func_get_args()); }
    public function fieldset() { return $this->create('fieldset', func_get_args()); }
    public function figcaption() { return $this->create('figcaption', func_get_args()); }
    public function figure() { return $this->create('figure', func_get_args()); }
    public function font() { return $this->create('font', func_get_args()); }
    public function footer() { return $this->create('footer', func_get_args()); }
    public function form() { return $this->create('form', func_get_args()); }
    public function frame() { return $this->create('frame', func_get_args()); }
    public function frameset() { return $this->create('frameset', func_get_args()); }
    public function h1() { return $this->create('h1', func_get_args()); }
    public function h2() { return $this->create('h2', func_get_args()); }
    public function h3() { return $this->create('h3', func_get_args()); }
    public function h4() { return $this->create('h4', func_get_args()); }
    public function h5() { return $this->create('h5', func_get_args()); }
    public function h6() { return $this->create('h6', func_get_args()); }
    public function header() { return $this->create('header', func_get_args()); }
    public function head() { return $this->create('head', func_get_args()); }
    public function hgroup() { return $this->create('hgroup', func_get_args()); }
    public function hr() { return $this->create('hr', func_get_args()); }
    public function html() { return $this->create('html', func_get_args()); }
    public function iframe() { return $this->create('iframe', func_get_args()); }
    public function i() { return $this->create('i', func_get_args()); }
    public function img() { return $this->create('img', func_get_args()); }
    public function input() { return $this->create('input', func_get_args()); }
    public function ins() { return $this->create('ins', func_get_args()); }
    public function isindex() { return $this->create('isindex', func_get_args()); }
    public function kbd() { return $this->create('kbd', func_get_args()); }
    public function keygen() { return $this->create('keygen', func_get_args()); }
    public function label() { return $this->create('label', func_get_args()); }
    public function legend() { return $this->create('legend', func_get_args()); }
    public function li() { return $this->create('li', func_get_args()); }
    public function link() { return $this->create('link', func_get_args()); }
    public function listing() { return $this->create('listing', func_get_args()); }
    public function map() { return $this->create('map', func_get_args()); }
    public function mark() { return $this->create('mark', func_get_args()); }
    public function marquee() { return $this->create('marquee', func_get_args()); }
    public function math() { return $this->create('math', func_get_args()); }
    public function menu() { return $this->create('menu', func_get_args()); }
    public function meta() { return $this->create('meta', func_get_args()); }
    public function meter() { return $this->create('meter', func_get_args()); }
    public function nav() { return $this->create('nav', func_get_args()); }
    public function nextid() { return $this->create('nextid', func_get_args()); }
    public function nobr() { return $this->create('nobr', func_get_args()); }
    public function noembed() { return $this->create('noembed', func_get_args()); }
    public function noframes() { return $this->create('noframes', func_get_args()); }
    public function noscript() { return $this->create('noscript', func_get_args()); }
    public function object() { return $this->create('object', func_get_args()); }
    public function ol() { return $this->create('uol', func_get_args()); }
    public function optgroup() { return $this->create('optgroup', func_get_args()); }
    public function option() { return $this->create('option', func_get_args()); }
    public function output() { return $this->create('output', func_get_args()); }
    public function param() { return $this->create('param', func_get_args()); }
    public function plaintext() { return $this->create('plaintext', func_get_args()); }
    public function p() { return $this->create('p', func_get_args()); }
    public function pre() { return $this->create('pre', func_get_args()); }
    public function progress() { return $this->create('progress', func_get_args()); }
    public function q() { return $this->create('q', func_get_args()); }
    public function rp() { return $this->create('rp', func_get_args()); }
    public function rt() { return $this->create('rt', func_get_args()); }
    public function ruby() { return $this->create('ruby', func_get_args()); }
    public function samp() { return $this->create('samp', func_get_args()); }
    public function script() { return $this->create('script', func_get_args()); }
    public function section() { return $this->create('section', func_get_args()); }
    public function select() { return $this->create('select', func_get_args()); }
    public function small() { return $this->create('small', func_get_args()); }
    public function source() { return $this->create('source', func_get_args()); }
    public function spacer() { return $this->create('spacer', func_get_args()); }
    public function span() { return $this->create('span', func_get_args()); }
    public function s() { return $this->create('s', func_get_args()); }
    public function strike() { return $this->create('strike', func_get_args()); }
    public function strong() { return $this->create('strong', func_get_args()); }
    public function style() { return $this->create('style', func_get_args()); }
    public function sub() { return $this->create('sub', func_get_args()); }
    public function sup() { return $this->create('sup', func_get_args()); }
    public function summary() { return $this->create('summary', func_get_args()); }
    public function svg() { return $this->create('svg', func_get_args()); }
    public function table() { return $this->create('table', func_get_args()); }
    public function tbody() { return $this->create('tbody', func_get_args()); }
    public function td() { return $this->create('td', func_get_args()); }
    public function textarea() { return $this->create('textarea', func_get_args()); }
    public function tfoot() { return $this->create('tfoot', func_get_args()); }
    public function thead() { return $this->create('thead', func_get_args()); }
    public function th() { return $this->create('th', func_get_args()); }
    public function time() { return $this->create('time', func_get_args()); }
    public function title() { return $this->create('title', func_get_args()); }
    public function tr() { return $this->create('tr', func_get_args()); }
    public function tt() { return $this->create('tt', func_get_args()); }
    public function ul() { return $this->create('ul', func_get_args()); }
    public function u() { return $this->create('u', func_get_args()); }
    public function _var() { return $this->create('var', func_get_args()); }
    public function video() { return $this->create('video', func_get_args()); }
    public function wbr() { return $this->create('wbr', func_get_args()); }
    public function xmp() { return $this->create('xmp', func_get_args()); }


    /**
     * @param string $text
     * @return TextNode
     */
    public function text($text)
    {
        return new TextNode($text, $this->escaper);
    }

    /**
     * @param string $text
     * @return RawNode
     */
    public function raw($text)
    {
        return new RawNode($text);
    }

}
