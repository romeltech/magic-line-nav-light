<?php
/**
 * Activate
 * 
 * @package Mel-7
 * 
**/

class MLLDeactivate{
    public static function deactivate(){
        flush_rewrite_rules();
    }
}