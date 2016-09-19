<?php

namespace NoTee;


class Document implements Node
{
    const DOCTYPE_HTML5 = 'html';
    const DOCTYPE_HTML401_STRICT = 'HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"';
    const DOCTYPE_HTML401_TRANSITIONAL = 'HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"';

    protected $doctype;
    protected $htmlNode;

    /**
     * Document constructor.
     * @param string $doctype
     * @param DefaultNode $htmlNode
     */
    public function __construct($doctype, DefaultNode $htmlNode)
    {
        $this->doctype = $doctype;
        $this->htmlNode = $htmlNode;
    }

    public function __toString()
    {

        return '<!DOCTYPE ' . $this->doctype . ">\n" . (string)$this->htmlNode;
    }

}
