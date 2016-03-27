<?php

namespace NoTee;


class Processor implements ModifiableNode
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
//        $this->allPaths = static::orderPaths($allPaths);
        $this->allPaths = $allPaths;
        $this->root = $root;
    }

    public function getAllPaths()
    {
        return $this->allPaths;
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

    public function addClass($class)
    {
        $this->execute('addClass', [$class]);
        return $this;
    }

    public function setRaw($raw)
    {
        $this->execute('setRaw', [$raw]);
    }

    public function setText($text)
    {
        $this->execute('setText', [$text]);
    }

    public function setRoot(Node $root)
    {
        $this->root = $root;
    }

    public function getRoot()
    {
        return $this->root;
    }


    /**
     * @param array-of-array-of-PathStep $paths
     * @return array-of-Path
     */
//    private static function orderPaths(array $paths)
//    {
//        usort($paths, function(array $path1, array $path2){
//            $amount1 = count($path1);
//            $amount2 = count($path2);
//            if($amount1 < $amount2) {
//                return 1;
//            } else {
//                return $amount1 === $amount2 ? 0 : -1;
//            }
//        });
//        return $paths;
//    }

}
