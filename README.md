# NoTeePHP
PHP HTML generation library.

## What is NoTeePHP

NoTeePHP is a replacement for template engines. With NoTeePHP you can create HTML without writing on line of HTML.
Advantages of NoTeePHP compared to template engines:

- more secure (e.g. proper escaping by default / only double-quotes for attributes)
- less error-prone (never again have enclosing tag errors)
- easy (templates needs be compiled)
- fast (cache components like the footer of you page in your object cache)
- fewer code due to minimal syntax

Further great things about NoTeePHP:

- immutable node tree (reuse every node in unlimited places)
- jquery like api for changing your nodes (e.g. middleware adding xsrf-fields to all forms)

## Installation

Install NoTeePHP with composer.

```
composer install mschop/noteephp
```

There are two different notations, depending on whether you include globalfunctions.php or not.
The following notations are equivalent:

with globalfunctions.php:

```
$node = _p();
```

without globalfunctions.php:

```
$node = NoTee\NodeFactory::p();
```

If you prefer using short notation, you should use globalfunctions.php.

## Basic Usage

This is a tiny example:

    function getItems()
    {
        $result = [];
        for($x = 1; $x < 10; $x++) {
            $result[] = _li('item ' . $x);
        }
        return $result;
    }
    
    $root = _div(
        ['class' => 'a b c'],
        _abbr(
            ['title' => 'hypertext markup language'],
            'html'
        ),
        _ul(
            _li('item 0'),
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
