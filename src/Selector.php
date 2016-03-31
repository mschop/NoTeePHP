<?php

namespace NoTee;

use CSSSelectorParser\Parser;

class Selector
{

    /**
     * @param Fertile $node
     * @param $selector
     * @return array--of-array-of-PathStep
     */
    public static function select(DefaultNode $node, $selector)
    {
        $parser = new Parser();
//        $parser->registerSelectorPseudos('has');
        $parser->registerNestingOperators('>', '+', '~');
        $parser->registerAttrEqualityMods('^', '$', '*', '~');
        $parser->enableSubstitutes();
        $rules = $parser->parse($selector);
        $paths = static::getPaths($node, $rules);
        return $paths;
    }

    private static function getPaths($node, $rules)
    {
        if($rules['type'] === 'ruleSet') {
            $result = static::walkNode($rules['rule'], $node, [new PathStep(null, $node)]);
        } else {
            $result = array_reduce($rules['selectors'], function($carry, $ruleSet) use ($node){
                return array_merge($carry, static::walkNode($ruleSet['rule'], $node, [new PathStep(null, $node)]));
            }, []);
        }
        return static::deleteDuplicates($result);
    }

    private static function deleteDuplicates($paths)
    {
        $known = [];
        return array_reduce($paths, function($carry, $path) use (&$known) {
            $identification = static::getIdentification($path);
            if(!in_array($identification, $known)) {
                $known[] = $identification;
                $carry[] = $path;
                return $carry;
            }
            return $carry;
        }, []);
    }

    private static function getIdentification($path)
    {
        $result = '';
        foreach($path as $pathStep) {
            $result .= '-' . $pathStep->getIndex();
        }
        return $result;
    }

    private static function walkNode($rule, DefaultNode $node, array $nodePath = [])
    {
        $result = [];

        /*
         * test rule on this node
         */
        if (
            static::isMatchingRule($rule, $node)
        ) {
            if (isset($rule['rule'])) {
                $result = array_merge($result, static::walkSubNodes($rule['rule'], $node, $nodePath));
            } elseif(!isset($rule['nestingOperator']) || !in_array($rule['nestingOperator'], ['~', '+'])) {
                $result[] = $nodePath;
            }

            /**
             * test same nesting level nesting operator
             */
            if(
                isset($rule['rule']['rule']['nestingOperator'])
                && in_array($rule['rule']['rule']['nestingOperator'], ['+', '~'])
            ) {
                $result = array_merge($result, static::walkByNestingOperator($rule['rule'], $node, $nodePath));
            }
        }

        /*
         * test rule on children if nesting operator > is not set
         */
        if (
            !isset($rule['nestingOperator'])
            || $rule['nestingOperator'] !== '>'
        ) {
            $result = array_merge($result, static::walkSubNodes($rule, $node, $nodePath));
        }


        if(
            isset($rule['rule']['nestingOperator'])
            && in_array($rule['rule']['nestingOperator'], ['~', '+'])
        ) {
            $result = array_merge($result, static::walkByNestingOperator($rule, $node, $nodePath));
        }

        return $result;
    }

    private static function walkSubNodes($rule, $node, $nodePath)
    {
        $result = [];
        foreach ($node->getChildren() as $index => $child) {
            if ($child instanceof DefaultNode) {
                $pathStep = new PathStep($index, $child);
                $extendedPath = array_merge($nodePath, [$pathStep]);
                $result = array_merge($result, static::walkNode($rule, $child, $extendedPath));
            }
        }
        return $result;
    }

    private static function walkByNestingOperator($rule, $node, $nodePath)
    {
        $result = [];
        $children = $node->getChildren();
        $childAmount = count($children);
        for($x = 0; $x < $childAmount; $x++) {
            $child = $children[$x];
            if(static::isMatchingRule($rule, $child)) {
                if($rule['rule']['nestingOperator'] === '+') {
                    // get the next child element matching the rule
                    $otherIndex = $x + 1;
                    for( ;$otherIndex < $childAmount; $otherIndex++) {
                        $otherChild = $children[$otherIndex];
                        if(static::isMatchingRule($rule['rule'], $otherChild)) {
                            if(isset($rule['rule']['rule'])) {
                                $result = array_merge($result, static::walkSubNodes(
                                    $rule['rule']['rule'],
                                    $otherChild,
                                    array_merge($nodePath, [new PathStep($otherIndex, $otherChild)])
                                ));
                            } else {
                                $result[] = array_merge($nodePath, [new PathStep($x, $children[$otherIndex])]);
                            }
                            break;
                        }
                    }
                } else {
                    foreach($children as $otherIndex => $otherChild) {
                        if($otherChild !== $child &&  static::isMatchingRule($rule['rule'], $otherChild)) {
                            if(isset($rule['rule']['rule'])) {
                                $result = array_merge($result, static::walkSubNodes(
                                    $rule['rule']['rule'],
                                    $otherChild,
                                    array_merge($nodePath, [new PathStep($otherIndex, $otherChild)])
                                ));
                            } else {
                                $result[] = array_merge($nodePath, [new PathStep($otherIndex, $otherChild)]);
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }

    private static function isMatchingRule($rule, DefaultNode $node)
    {
        if (isset($rule['tagName']) && strtolower($rule['tagName']) !== strtolower($node->getTagName())) {
            return false;
        }

        if (isset($rule['attrs']) && !static::hasMatchingAttributes($node, $rule['attrs'])) {
            return false;
        }

        if(isset($rule['classNames']) && !static::hasMatchingClasses($node, $rule['classNames'])) {
            return false;
        }

        return true;
    }

    private static function hasMatchingAttributes(DefaultNode $node, $expectedAttributes)
    {
        foreach ($expectedAttributes as $expectedAttribute) {
            $attributes = $node->getAttributes();
            if (!isset($attributes[$expectedAttribute['name']])) {
                return false;
            }

            $val = $attributes[$expectedAttribute['name']];

            if ($expectedAttribute['operator'] === '=' && $expectedAttribute['value'] !== $val) {
                return false;
            }

            if ($expectedAttribute['operator'] === '~=') {
                throw new \Exception('Not Implemented');
            }

            if ($expectedAttribute['operator'] === '|=') {
                throw new \Exception('Not Implemented');
            }

            if ($expectedAttribute['operator'] === '^=') {
                throw new \Exception('Not Implemented');
            }

            if ($expectedAttribute['operator'] === '$=') {
                throw new \Exception('Not Implemented');
            }

            if ($expectedAttribute['operator'] === '*=') {
                throw new \Exception('Not Implemented');
            }
        }
        return true;
    }


    private static function hasMatchingClasses(DefaultNode $node, array $classNames)
    {
        $attributes = $node->getAttributes();
        if(!isset($attributes['class'])) {
            return false;
        }
        return count(array_intersect(explode(' ', $attributes['class']), $classNames)) === count($classNames);
    }

}
