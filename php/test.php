<?php

include("./Simple.php");

//$var = [
//    "test" => 123,
//    "test2" => [ 123 ]
//];
//
//$patch = [
//    "test2" => [ 456 ]
//];
//
////echo (($var[ "fail" ] == null) ? "test" : "test2") . "\n";
//
//$bla = ($var[ "fail" ] != null) ? $var[ "fail" ] : [];
//
//array_push($bla, "test");
//
//print_r($bla);
//
////print_r(array_merge($var, $patch));
//print_r(array_merge_recursive($var, $patch));

//$xml = "<p>Test 1</p>";
//$xml2 = "Blabla <p>Test 2</p> Blabla";
//
//$xml = simplexml_load_string("<xml>$xml2</xml>");
//
//foreach($xml->children() as $dcxParagraph)
//{
//    echo "$dcxParagraph\n";
//}

echo html_entity_decode("FDP-Fraktionsvize f&uuml;r Neugliederung der Bundesl&auml;nder") . "\n";
