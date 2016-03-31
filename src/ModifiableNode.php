<?php

namespace NoTee;


interface ModifiableNode
{
    public function addClass($class);
    public function removeClass($class);
}
