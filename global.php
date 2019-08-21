<?php

use NoTee\NodeFactory;
use NoTee\Nodes\DefaultNode;
use NoTee\Nodes\RawNode;
use NoTee\Nodes\TextNode;

global $noTeePHP;

if (!$noTeePHP instanceof NodeFactory) {
    throw new \Exception('You need to add a global noteephp nodefactory: global $noTeePHP = new NodeFactory(...);');
}

function node(string $name, array $attributes = [], array $children = []): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return new DefaultNode(
        $name,
        $noTeePHP->getEscaper(),
        $attributes,
        $children
    );
}

function raw(string $text): RawNode
{
    return new RawNode($text);
}

function text(string $text): TextNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return new TextNode($text, $noTeePHP->getEscaper());
}

function _a(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('a', func_get_args());
}

function _abbr(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('abbr', func_get_args());
}

function _acronym(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('acronym', func_get_args());
}

function _address(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('address', func_get_args());
}

function _applet(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('applet', func_get_args());
}

function _area(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('area', func_get_args());
}

function _article(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('article', func_get_args());
}

function _aside(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('aside', func_get_args());
}

function _audio(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('audio', func_get_args());
}

function _base(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('base', func_get_args());
}

function _basefont(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('basefont', func_get_args());
}

function _b(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('b', func_get_args());
}

function _bdo(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('bdo', func_get_args());
}

function _bgsound(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('bgsound', func_get_args());
}

function _big(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('big', func_get_args());
}

function _blink(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('blink', func_get_args());
}

function _blockquote(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('blockquote', func_get_args());
}

function _body(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('body', func_get_args());
}

function _br(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('br', func_get_args());
}

function _button(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('button', func_get_args());
}

function _canvas(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('canvas', func_get_args());
}

function _caption(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('caption', func_get_args());
}

function _center(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('center', func_get_args());
}

function _cite(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('cite', func_get_args());
}

function _code(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('code', func_get_args());
}

function _col(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('col', func_get_args());
}

function _colgroup(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('colgroup', func_get_args());
}

function _command(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('command', func_get_args());
}

function _datalist(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('datalist', func_get_args());
}

function _dd(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('dd', func_get_args());
}

function _del(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('del', func_get_args());
}

function _details(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('details', func_get_args());
}

function _dfn(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('dfn', func_get_args());
}

function _div(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('div', func_get_args());
}

function _dl(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('dl', func_get_args());
}

function _dt(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('dt', func_get_args());
}

function _embed(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('embed', func_get_args());
}

function _em(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('em', func_get_args());
}

function _fieldset(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('fieldset', func_get_args());
}

function _figcaption(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('figcaption', func_get_args());
}

function _figure(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('figure', func_get_args());
}

function _font(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('font', func_get_args());
}

function _footer(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('footer', func_get_args());
}

function _form(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('form', func_get_args());
}

function _frame(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('frame', func_get_args());
}

function _frameset(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('frameset', func_get_args());
}

function _h1(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('h1', func_get_args());
}

function _h2(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('h2', func_get_args());
}

function _h3(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('h3', func_get_args());
}

function _h4(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('h4', func_get_args());
}

function _h5(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('h5', func_get_args());
}

function _h6(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('h6', func_get_args());
}

function _header(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('header', func_get_args());
}

function _head(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('head', func_get_args());
}

function _hgroup(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('hgroup', func_get_args());
}

function _hr(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('hr', func_get_args());
}

function _html(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('html', func_get_args());
}

function _iframe(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('iframe', func_get_args());
}

function _i(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('i', func_get_args());
}

function _img(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('img', func_get_args());
}

function _input(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('input', func_get_args());
}

function _ins(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('ins', func_get_args());
}

function _isindex(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('isindex', func_get_args());
}

function _kbd(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('kbd', func_get_args());
}

function _keygen(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('keygen', func_get_args());
}

function _label(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('label', func_get_args());
}

function _legend(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('legend', func_get_args());
}

function _li(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('li', func_get_args());
}

function _link(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('link', func_get_args());
}

function _listing(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('listing', func_get_args());
}

function _map(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('map', func_get_args());
}

function _mark(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('mark', func_get_args());
}

function _marquee(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('marquee', func_get_args());
}

function _math(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('math', func_get_args());
}

function _menu(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('menu', func_get_args());
}

function _meta(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('meta', func_get_args());
}

function _meter(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('meter', func_get_args());
}

function _nav(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('nav', func_get_args());
}

function _nextid(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('nextid', func_get_args());
}

function _nobr(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('nobr', func_get_args());
}

function _noembed(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('noembed', func_get_args());
}

function _noframes(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('noframes', func_get_args());
}

function _noscript(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('noscript', func_get_args());
}

function _object(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('object', func_get_args());
}

function _ol(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('uol', func_get_args());
}

function _optgroup(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('optgroup', func_get_args());
}

function _option(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('option', func_get_args());
}

function _output(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('output', func_get_args());
}

function _param(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('param', func_get_args());
}

function _plaintext(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('plaintext', func_get_args());
}

function _p(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('p', func_get_args());
}

function _pre(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('pre', func_get_args());
}

function _progress(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('progress', func_get_args());
}

function _q(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('q', func_get_args());
}

function _rp(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('rp', func_get_args());
}

function _rt(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('rt', func_get_args());
}

function _ruby(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('ruby', func_get_args());
}

function _samp(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('samp', func_get_args());
}

function _script(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('script', func_get_args());
}

function _section(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('section', func_get_args());
}

function _select(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('select', func_get_args());
}

function _small(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('small', func_get_args());
}

function _source(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('source', func_get_args());
}

function _spacer(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('spacer', func_get_args());
}

function _span(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('span', func_get_args());
}

function _s(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('s', func_get_args());
}

function _strike(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('strike', func_get_args());
}

function _strong(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('strong', func_get_args());
}

function _style(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('style', func_get_args());
}

function _sub(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('sub', func_get_args());
}

function _sup(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('sup', func_get_args());
}

function _summary(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('summary', func_get_args());
}

function _svg(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('svg', func_get_args());
}

function _table(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('table', func_get_args());
}

function _tbody(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('tbody', func_get_args());
}

function _td(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('td', func_get_args());
}

function _textarea(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('textarea', func_get_args());
}

function _tfoot(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('tfoot', func_get_args());
}

function _thead(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('thead', func_get_args());
}

function _th(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('th', func_get_args());
}

function _time(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('time', func_get_args());
}

function _title(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('title', func_get_args());
}

function _tr(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('tr', func_get_args());
}

function _tt(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('tt', func_get_args());
}

function _ul(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('ul', func_get_args());
}

function _u(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('u', func_get_args());
}

function __var(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('var', func_get_args());
}

function _video(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('video', func_get_args());
}

function _wbr(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('wbr', func_get_args());
}

function _xmp(): DefaultNode
{
    global $noTeePHP;
    assert($noTeePHP instanceof NodeFactory);
    return $noTeePHP->create('xmp', func_get_args());
}
