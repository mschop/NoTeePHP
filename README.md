# NoTeePHP

[![Circle CI](https://circleci.com/gh/mschop/NoTeePHP/tree/master.svg?style=svg)](https://circleci.com/gh/mschop/NoTeePHP/tree/master)
[![Coverage Status](https://coveralls.io/repos/github/mschop/NoTeePHP/badge.svg?branch=master)](https://coveralls.io/github/mschop/NoTeePHP?branch=master)

PHP HTML generation library.

## What is NoTeePHP

NoTeePHP is a replacement for template engines that focuses on security and correctness. Instead of simply concatenating
string, NoTeePHP creates an object tree that represents an html structure.

Advantages of NoTeePHP compared to template engines:

- more secure
- less error-prone
- easier setup (no compile step)
- debuggable

Further great things about NoTeePHP:

- immutable node tree (unlimited node reuse)
- register events

## Setup

Install NoTeePHP with composer.

```
composer install mschop/noteephp
```

That's it.

## Basic Usage

This is a tiny example:

    $nf = new NodeFactory('utf-8');

    function getItems use ($nf)()
    {
        $result = [];
        for($x = 1; $x < 10; $x++) {
            $result[] = $nf->li('item ' . $x);
        }
        return $result;
    }
    
    $root = $nf->div(
        ['class' => 'a b c'],
        $nf->abbr(
            ['title' => 'hypertext markup language'],
            'html'
        ),
        $nf->ul(
            $nf->li('item 0'),
            getItems()
        )
    );
    
    echo($root);

This would produce the following result:

    <div class="a b c">
        <abbr title="hypertext markup language">html</abbr>
        <ul>
            <li>item 0</li>
            <li>item 1</li>
            <li>item 2</li>
            <li>item 3</li>
            <li>item 4</li>
            <li>item 5</li>
            <li>item 6</li>
            <li>item 7</li>
            <li>item 8</li>
        </ul>
    </div>

## Security

Many template engines do not offer escaping by default. The developer must remember to escape every information used in
a template. This is called 'discipline based security'. The problem here is, that a developer escapes 100 times properly
and then forgets it once. NoTeePHP has escaping by default.

Other template engines like Twig offer escaping by default. But even in Twig you can have XSS vulnerabilies in some
special cases. Imagine you want to create an anchor, changing get parameters based on user input.

A naive developer could think that relying on Twigs escaping is enough. This could produce this code (yes, that's real
code I've seen)

    <a href="{{ user.name }}">click me</a>
    
Now an attacker could just create an account with the username "javascript:alert(1)" and you have the exploit.

NoTeePHP creates an object tree instead of concatenating strings. Therefore it knows, in which context a variable is
used and can therefore uses proper escaping or additional validation for variables.

## Less error-prone

Syntax errors can cause hard to find bugs in your application. With NoTeePHP you will not face such problems.
Never again have enclosing tag errors or missing quotes. Always get well formatted HTML.

## Debugging

Template engines compiles the templates to plain PHP. This PHP is most often hard to read and therefore hard to debug.
With NoTeePHP you don't have such compile step. This simplifies setup, increases security and enables easy debugging
by default.

## Examples

### Create a NodeFactory

The NodeFactory class is the pivot of NoTeePHP.

    $nf = new NodeFactory('utf-8'); // using the right encoding is security relevant
    
### Create Nodes

    $node = $nf->div(
        ['id' => 'someid'], // optional assoc array, containing all attributes
        'some text, that will be escaped',
        $nf->raw('some text, that will not be escaped'),
        $this->span(), // nodes without children will be self-closing tags -> <span />
        [ // children can be passed as arrays for using the result of other methods
            $nf->span('text'),
            $nf->span('text2')
        ]
    );

### Events

You can register events globally. Lets imagine you want to add an xsrf-token to every form:

    $nf->onTag('form', function(array $attributes, array $children) use ($nf) {
        $children[] = $nf->input(['type' => 'hidden', 'name' => 'xsrf-token', 'value' => '12345']);
        return [$attributes, $children];
    });

The following events are available:

- onTag
- onAttr
- onClass
