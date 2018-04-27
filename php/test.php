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

//echo html_entity_decode("FDP-Fraktionsvize f&uuml;r Neugliederung der Bundesl&auml;nder") . "\n";

//$fieldValues = [];
//$fieldValues += [
//    'field_channel' => 1,
//    'title[0][value]' => 'Test FB MetaTags Article',
//    'field_seo_title[0][value]' => 'Facebook MetaTags',
//    'field_teaser_text[0][value]' => 'Facebook MetaTags Testing',
//];
//
//print_r($fieldValues);

//$str = "a:4:{s:5:\"title\";s:22:\"[node:field_seo_title]\";s:11:\"description\";s:24:\"[node:field_teaser_text]\";s:13:\"canonical_url\";s:11:\"[node:path]\";s:16:\"content_language\";s:15:\"[node:langcode]\";}";
//print_r(unserialize($str));

//function uuid_make($string)
//{
//    $string = substr($string, 0, 8 ) .'-'.
//        substr($string, 8, 4) .'-'.
//        substr($string, 12, 4) .'-'.
//        substr($string, 16, 4) .'-'.
//        substr($string, 20);
//    return $string;
//}
//
//$test = md5("Patrick");
//echo "$test\n";
//echo uuid_make($test) . "\n";

//$var = "<xml";
//echo $var[ 0 ] . "\n";
//
//echo strpos("bodytextafa", "text") . "\n";
//echo strpos("bodytextafa", "nicht") . "\n";

//$var = [
//    "1234" => "asdf"
//];
//
//echo (is_string($var[ "1234" ]) ? "true" : "false") . "\n";
//
//$var = [
//    "1234" => ["asdf"]
//];
//echo (is_array($var[ "1234" ]) ? "true" : "false") . "\n";
echo ([] ? "true" : "false") . "\n";
echo (["asdf"] ? "true" : "false") . "\n";