--TEST--
Check for jsonschema generate string
--SKIPIF--
<?php
if (!extension_loaded("jsonschema")) {
    print "skip";
}
?>
--FILE--
<?php
/* test string generate */
$value = 'test string';
$expect = array(
    'type' => 'string',
    'format' => 'regex',
    'pattern' => '/^[a-z0-9]+$/i',
    'minLength' => 0,
    'maxLength' => 2147483647,
);

$jsonSchema = new JsonSchema();
echo assert($expect == $jsonSchema->getSchema($value));
?>
--EXPECT--
1