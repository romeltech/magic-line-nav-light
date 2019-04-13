<?php
/**
 * Activate
 * 
 * @package Mel-7
 * 
**/

class MLLActivate{
    public static function activate(){ 
        flush_rewrite_rules();
    }
}