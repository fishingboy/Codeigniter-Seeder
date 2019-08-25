<?php

namespace fishingboy\ci_seeder;

/**
 * 種子的父類別
 */
abstract class CI_Seeder_base
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    abstract public function run();
}