<?php 
/**
* Name:  Xcrud 
*
* @package Xcrud_ajex
* @version 1.0
* @ignore
*/
include ('xcrud.php');
header('Content-Type: text/html; charset=' . Xcrud_config::$mbencoding);
echo Xcrud::get_requested_instance();
