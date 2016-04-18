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

    private function execute($method, $params)
    {
        $pathAmount = count($this->allPaths);
        for($x = 0; $x < $pathAmount; $x++) {
            $replacementMap = static::generateReplacementMap($this->allPaths[$x]);
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

    public function addClass($class)
    {
        $this->execute('addClass', [$class]);
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


    public function toggleClass($class)
    {
        $this->execute('toggleClass', [$class]);
        return $this;
    }

}
