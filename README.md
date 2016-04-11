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

- immutable node tree (reuse every node in unlimited places)
- jquery inspired api for changing your node structure

## Setup

Install NoTeePHP with composer.

```
composer install mschop/noteephp
```

That's it.

## Basic Usage

This is a tiny example:

    $nf = new NodeFactory();

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

You can modify the node tree, by either using the find method or creating an instance of Processor:

    $root = $root->find('li')->addClass('item')->getRoot();
    echo($root);
   
This would produce the following output:

    <div class="a b c">
        <abbr title="hypertext markup language">html</abbr>
        <ul>
            <li class="item">item 0</li>
            <li class="item">item 1</li>
            <li class="item">item 2</li>
            <li class="item">item 3</li>
            <li class="item">item 4</li>
            <li class="item">item 5</li>
            <li class="item">item 6</li>
            <li class="item">item 7</li>
            <li class="item">item 8</li>
        </ul>
    </div>


## Security

Template engines are prone to XSS attacks. For preventing XSS, a developer has to use the right escaping strategy
for the context he is using an information. See https://www.owasp.org/index.php/Cross-site_Scripting_%28XSS%29 for more
details on this topic.

Most often the frontend developers and designers have no clue about security and therefore do not use proper escaping.
Previously escaping an information does not help because you don't know, where in the template a variable could be
used. Using the right escaping is for depends on, which quoting style you use for attributes. Not using any
quotes at all is perfectly valid html. All these aspects get addressed by NoTeePHP. NoTeePHP does proper escaping by
default or forces the user to do so.

## Less error-prone

Syntax errors can cause hard to find bugs in your application. With NoTeePHP you will not face such problems.
Never again have enclosing tag errors or missing quotes. Always get well formatted HTML.

## Debugging

Template engines compiles the templates to plain PHP. This PHP is most often hard to read and therefore hard to debug.
With NoTeePHP you don't have such compile step. This simplifies setup, increases security and enables easy debugging
by default.
