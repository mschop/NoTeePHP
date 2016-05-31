<?php

namespace NoTee;


class Processor
{
    /** @var  Node */
    private $root;
    private $allPaths;

    /**
     * @param Node $root
     * @param array-of-array-of-PathStep $allPaths
     */
    public function __construct(Node $root, array $allPaths)
    {
        $this->allPaths = $allPaths;
        $this->root = $root;
    }

    /**
     * @param $method
     * @param $params
     * @param bool $cutLast Some methods to not change the selected item, but the parent item.
     */
    private function execute($method, $params, $cutLast = false)
    {
        $pathAmount = count($this->allPaths);
        for($x = 0; $x < $pathAmount; $x++) {
            $paramsCopy = $params;
            $replacementMap = static::generateReplacementMap($this->allPaths[$x]);
            if($cutLast) {
                /** @var ReplacementMapStep $last */
                $last = array_pop($replacementMap);
                array_unshift($paramsCopy, $last);
            }
            $this->root = $this->root->_executeOnPath($replacementMap, $method, $paramsCopy);
            $this->updatePathIndexes($replacementMap);
            $this->updatePaths($replacementMap);
        }
    }

    /**
     * @param array $path
     * @return array-of-ReplacementMapStep
     */
    private static function generateReplacementMap(array $path)
    {
        return array_map(function(PathStep $step){
            return new ReplacementMapStep($step->getIndex(), $step->getNode(), clone $step->getNode());
        }, $path);
    }

    private function updatePaths(array $replacementMap)
    {
        $pathAmount = count($this->allPaths);
        for($x = 0; $x < $pathAmount; $x++) {
            $this->allPaths[$x] = static::updatePath($this->allPaths[$x], $replacementMap);
        }
    }

    private function updatePathIndexes(array $replacementMap)
    {
        $replacementDepth = count($replacementMap);

        foreach($this->allPaths as &$path) {
            $isMatching = true;
            for($x = 0; $x < $replacementDepth; $x++) {
                if(count($path) > $replacementDepth && isset($path[$x])) {
                    /** @var PathStep $pathStep */
                    $pathStep = $path[$x];
                    /** @var ReplacementMapStep $replacementMapStep */
                    $replacementMapStep = $replacementMap[$x];
                    if($pathStep->getNode() !== $replacementMapStep->getOldNode()) {
                        $isMatching = false;
                    }
                } else {
                    $isMatching = false;
                }
            }
            if($isMatching) {
                /** @var DefaultNode $node */
                $node = $replacementMap[$replacementDepth - 1]->getNewNode();
                /** @var PathStep $pathStep */
                $pathStep = $path[$replacementDepth];

                foreach($node->getChildren() as $index => $child) {
                    if($child === $pathStep->getNode()) {
                        $path[$replacementDepth] = new PathStep($index, $pathStep->getNode());
                    }
                }
            }
        }
    }

    private static function updatePath($path, $replacementMap)
    {
        $x = 0;
        while(
            isset($path[$x])
            && isset($replacementMap[$x])
            && $path[$x]->getIndex() === $replacementMap[$x]->getIndex()
            && $path[$x]->getNode() === $replacementMap[$x]->getOldNode()
        ) {
            $path[$x] = new PathStep($replacementMap[$x]->getIndex(), $replacementMap[$x]->getNewNode());
            $x++;
        }
        return $path;
    }

    public function setText($text)
    {
        $this->execute('setText', [$text]);
        return $this;
    }

    public function setRaw($raw)
    {
        $this->execute('setRaw', [$raw]);
        return $this;
    }

    /**
     * @return DefaultNode
     */
    public function getRoot()
    {
        return $this->root;
    }


    /*
     * JQUERY-Like methods
     */

    /**
     * @param string $class
     * @return $this
     */
    public function addClass($class)
    {
        $this->execute('addClass', [$class]);
        return $this;
    }

    /**
     * @param Node $child
     * @return Processor
     */
    public function append(Node $child)
    {
        $this->execute('append', [$child]);
        return $this;
    }

    /**
     * @return Processor
     */
    public function children() {
        $newPaths = [];
        foreach($this->allPaths as $path) {
            /** @var PathStep $lastPathStep */
            $lastPathStep = end($path);
            foreach($lastPathStep->getNode()->getChildren() as $key => $child) {
                $newPaths[] = array_merge($path, [new PathStep($key, $child)]);
            }
        }
        return new Processor($this->root, $newPaths);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getAttr($name)
    {
        /** @var PathStep $firstSelected */
        $firstPath = reset($this->allPaths);
        $firstSelected = end($firstPath);
        return $firstSelected->getNode()->getAttributes()[$name] ?: null;
    }

    /**
     * Inserts $node after the selected nodes
     * @param Node $node
     * @return $this
     */
    public function insertAfter(Node $node)
    {
        $this->execute('insertAfter', [$node], true);
        return $this;
    }

    /**
     * @param Node $node
     * @return $this
     */
    public function insertBefore(Node $node)
    {
        $this->execute('insertBefore', [$node], true);
        return $this;
    }

    /**
     * @param $index
     * @param Node $node
     * @return $this
     */
    public function insertChildAt($index, Node $node)
    {
        $this->execute('insertChildAt', [$index, $node]);
        return $this;
    }

    /**
     * @param Node $node
     * @return $this
     */
    public function prepend(Node $node)
    {
        $this->execute('prepend', [$node]);
        return $this;
    }

    /**
     * @return $this
     */
    public function remove()
    {
        $this->execute('remove', [], true);
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function removeAttr($name)
    {
        $this->execute('removeAttr', [$name]);
        return $this;
    }

    /**
     * @param string $class
     * @return Processor
     */
    public function removeClass($class)
    {
        $this->execute('removeClass', [$class]);
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return Processor
     */
    public function setAttr($name, $value)
    {
        $this->execute('setAttr', [$name, $value]);
        return $this;
    }

    /**
     * @param string $class
     * @return Processor
     */
    public function toggleClass($class)
    {
        $this->execute('toggleClass', [$class]);
        return $this;
    }

}
