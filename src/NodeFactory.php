<?php

namespace NoTee;

/**
 * Class NodeFactory
 * @package NoTee
 * @method static a
 * @method static abbr
 * @method static acronym
 * @method static address
 * @method static applet
 * @method static area
 * @method static article
 * @method static aside
 * @method static audio
 * @method static base
 * @method static basefont
 * @method static b
 * @method static bdo
 * @method static bgsound
 * @method static big
 * @method static blink
 * @method static blockquote
 * @method static body
 * @method static br
 * @method static button
 * @method static canvas
 * @method static caption
 * @method static center
 * @method static cite
 * @method static code
 * @method static col
 * @method static colgroup
 * @method static command
 * @method static datalist
 * @method static dd
 * @method static del
 * @method static details
 * @method static dfn
 * @method static div
 * @method static dl
 * @method static dt
 * @method static embed
 * @method static em
 * @method static fieldset
 * @method static figcaption
 * @method static figure
 * @method static font
 * @method static footer
 * @method static form
 * @method static frame
 * @method static frameset
 * @method static h1
 * @method static h2
 * @method static h3
 * @method static h4
 * @method static h5
 * @method static h6
 * @method static header
 * @method static head
 * @method static hgroup
 * @method static hr
 * @method static html
 * @method static iframe
 * @method static i
 * @method static img
 * @method static input
 * @method static ins
 * @method static isindex
 * @method static kbd
 * @method static keygen
 * @method static label
 * @method static legend
 * @method static li
 * @method static link
 * @method static listing
 * @method static map
 * @method static mark
 * @method static marquee
 * @method static math
 * @method static menu
 * @method static meta
 * @method static meter
 * @method static nav
 * @method static nextid
 * @method static nobr
 * @method static noembed
 * @method static noframes
 * @method static noscript
 * @method static object
 * @method static ol
 * @method static optgroup
 * @method static option
 * @method static output
 * @method static param
 * @method static plaintext
 * @method static p
 * @method static pre
 * @method static progress
 * @method static q
 * @method static rp
 * @method static rt
 * @method static ruby
 * @method static samp
 * @method static script
 * @method static section
 * @method static select
 * @method static small
 * @method static source
 * @method static spacer
 * @method static span
 * @method static s
 * @method static strike
 * @method static strong
 * @method static style
 * @method static sub
 * @method static sup
 * @method static summary
 * @method static svg
 * @method static table
 * @method static tbody
 * @method static td
 * @method static textarea
 * @method static tfoot
 * @method static thead
 * @method static th
 * @method static time
 * @method static title
 * @method static tr
 * @method static tt
 * @method static ul
 * @method static u
 * @method static _var
 * @method static video
 * @method static wbr
 * @method static xmp
 */
class NodeFactory
{
    public static function __callStatic($name, $arguments)
    {
        return new DefaultNode($name, array_shift($arguments), $arguments);
    }

    public static function text($text)
    {
        return new TextNode($text);
    }

    public static function raw($text)
    {
        return new Raw($text);
    }
}
