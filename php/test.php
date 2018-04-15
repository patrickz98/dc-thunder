<?php

include("./Simple.php");

$var = [
    "test" => 123,
    "test2" => [ 123 ]
];

$patch = [
    "test2" => [ 456 ]
];

//echo (($var[ "fail" ] == null) ? "test" : "test2") . "\n";

$bla = ($var[ "fail" ] != null) ? $var[ "fail" ] : [];

array_push($bla, "test");

print_r($bla);

print_r(array_merge($var, $patch));

