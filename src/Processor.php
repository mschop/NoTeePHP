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
     * @param bool $cutLast Some methods to not change the selected item, but a parent item.
     */
    private function execute($method, $params, $cutLast = false)
    {
        $pathAmount = count($this->allPaths);
        for($x = 0; $x < $pathAmount; $x++) {
            $replacementMap = static::generateReplacementMap($this->allPaths[$x]);
            if($cutLast) {
                /** @var ReplacementMapStep $last */
                $last = array_pop($replacementMap);
                array_unshift($params, $last);
            }
            $this->root = $this->root->_executeOnPath($replacementMap, $method, $params);
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

    public function insertAfter(Node $node)
    {
        $this->execute('insertAfter', [$node], true);
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
