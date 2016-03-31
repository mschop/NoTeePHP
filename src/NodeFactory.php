<?php

namespace NoTee;

/**
 * Class NodeFactory
 * @package NoTee
 * @method static DefaultNode a
 * @method static DefaultNode abbr
 * @method static DefaultNode acronym
 * @method static DefaultNode address
 * @method static DefaultNode applet
 * @method static DefaultNode area
 * @method static DefaultNode article
 * @method static DefaultNode aside
 * @method static DefaultNode audio
 * @method static DefaultNode base
 * @method static DefaultNode basefont
 * @method static DefaultNode b
 * @method static DefaultNode bdo
 * @method static DefaultNode bgsound
 * @method static DefaultNode big
 * @method static DefaultNode blink
 * @method static DefaultNode blockquote
 * @method static DefaultNode body
 * @method static DefaultNode br
 * @method static DefaultNode button
 * @method static DefaultNode canvas
 * @method static DefaultNode caption
 * @method static DefaultNode center
 * @method static DefaultNode cite
 * @method static DefaultNode code
 * @method static DefaultNode col
 * @method static DefaultNode colgroup
 * @method static DefaultNode command
 * @method static DefaultNode datalist
 * @method static DefaultNode dd
 * @method static DefaultNode del
 * @method static DefaultNode details
 * @method static DefaultNode dfn
 * @method static DefaultNode div
 * @method static DefaultNode dl
 * @method static DefaultNode dt
 * @method static DefaultNode embed
 * @method static DefaultNode em
 * @method static DefaultNode fieldset
 * @method static DefaultNode figcaption
 * @method static DefaultNode figure
 * @method static DefaultNode font
 * @method static DefaultNode footer
 * @method static DefaultNode form
 * @method static DefaultNode frame
 * @method static DefaultNode frameset
 * @method static DefaultNode h1
 * @method static DefaultNode h2
 * @method static DefaultNode h3
 * @method static DefaultNode h4
 * @method static DefaultNode h5
 * @method static DefaultNode h6
 * @method static DefaultNode header
 * @method static DefaultNode head
 * @method static DefaultNode hgroup
 * @method static DefaultNode hr
 * @method static DefaultNode html
 * @method static DefaultNode iframe
 * @method static DefaultNode i
 * @method static DefaultNode img
 * @method static DefaultNode input
 * @method static DefaultNode ins
 * @method static DefaultNode isindex
 * @method static DefaultNode kbd
 * @method static DefaultNode keygen
 * @method static DefaultNode label
 * @method static DefaultNode legend
 * @method static DefaultNode li
 * @method static DefaultNode link
 * @method static DefaultNode listing
 * @method static DefaultNode map
 * @method static DefaultNode mark
 * @method static DefaultNode marquee
 * @method static DefaultNode math
 * @method static DefaultNode menu
 * @method static DefaultNode meta
 * @method static DefaultNode meter
 * @method static DefaultNode nav
 * @method static DefaultNode nextid
 * @method static DefaultNode nobr
 * @method static DefaultNode noembed
 * @method static DefaultNode noframes
 * @method static DefaultNode noscript
 * @method static DefaultNode object
 * @method static DefaultNode ol
 * @method static DefaultNode optgroup
 * @method static DefaultNode option
 * @method static DefaultNode output
 * @method static DefaultNode param
 * @method static DefaultNode plaintext
 * @method static DefaultNode p
 * @method static DefaultNode pre
 * @method static DefaultNode progress
 * @method static DefaultNode q
 * @method static DefaultNode rp
 * @method static DefaultNode rt
 * @method static DefaultNode ruby
 * @method static DefaultNode samp
 * @method static DefaultNode script
 * @method static DefaultNode section
 * @method static DefaultNode select
 * @method static DefaultNode small
 * @method static DefaultNode source
 * @method static DefaultNode spacer
 * @method static DefaultNode span
 * @method static DefaultNode s
 * @method static DefaultNode strike
 * @method static DefaultNode strong
 * @method static DefaultNode style
 * @method static DefaultNode sub
 * @method static DefaultNode sup
 * @method static DefaultNode summary
 * @method static DefaultNode svg
 * @method static DefaultNode table
 * @method static DefaultNode tbody
 * @method static DefaultNode td
 * @method static DefaultNode textarea
 * @method static DefaultNode tfoot
 * @method static DefaultNode thead
 * @method static DefaultNode th
 * @method static DefaultNode time
 * @method static DefaultNode title
 * @method static DefaultNode tr
 * @method static DefaultNode tt
 * @method static DefaultNode ul
 * @method static DefaultNode u
 * @method static DefaultNode _var
 * @method static DefaultNode video
 * @method static DefaultNode wbr
 * @method static DefaultNode xmp
 */
class NodeFactory
{

    public static function __callStatic($name, $arguments)
    {
        $name = static::removePrefix($name);

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
            if($argument instanceof Node || is_string($argument)) {
                $result[] = $argument;
            } else {
                foreach($argument as $node) {
                    $result[] = $node;
                }
            }
        }
        return $result;
    }

    private static function removePrefix($name)
    {
        if(substr($name, 0, 1) === '_') {
            return substr($name, 1, strlen($name) - 1);
        }
        return $name;
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
