<?php
header('content-type: text/plain; charset=UTF-8');
include_once($_SERVER['DOCUMENT_ROOT']."/local/php_interface/subdomains/include/subdomains_functions.php");

echo subdomains_robots();
