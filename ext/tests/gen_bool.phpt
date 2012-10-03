--TEST--
Check for jsonschema generate boolean
--SKIPIF--
<?php
if (!extension_loaded("jsonschema")) {
    print "skip";
}
?>
--FILE--
<?php
$value = true;
$expect = array(
    'type' => 'boolean'
    , 'default' => false
);
$jsonSchema = new JsonSchema();

echo assert($expect == $jsonSchema->getSchema($value));
?>
--EXPECT--
1
