# NoTeePHP

[![Circle CI](https://circleci.com/gh/mschop/NoTeePHP/tree/master.svg?style=svg)](https://circleci.com/gh/mschop/NoTeePHP/tree/master)
[![Coverage Status](https://coveralls.io/repos/github/mschop/NoTeePHP/badge.svg?branch=master)](https://coveralls.io/github/mschop/NoTeePHP?branch=master)

PHP HTML generation library.

## What is NoTeePHP

NoTeePHP is a replacement for template engines. With NoTeePHP you can create HTML without writing on line of HTML.
Advantages of NoTeePHP compared to template engines:

- more secure (e.g. proper escaping by default / only double-quotes for attributes)
- less error-prone (never again have enclosing tag errors)
- easy (templates needs be compiled)
- fast (cache components like the footer of you page in your object cache)

Further great things about NoTeePHP:

- immutable node tree (reuse every node in unlimited places)
- api for changing your nodes (e.g. middleware adding xsrf-fields to all forms)

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


## Full Documentation

I will add a full API-Documentation later. The project has no releases until now.
If you have any questions right now, you can send me a mail to mschopdev@gmail.com.
