--TEST--
Check for jsonschema generate number
--SKIPIF--
<?php
if (!extension_loaded("jsonschema")) {
    print "skip";
}
?>
--FILE--
<?php
/* test number generate */
echo 'number:', PHP_EOL;
/* double */
$value = 123.321;
$expect = array(
    'type' => 'number',
    'default' => 0,
    'minimum' => 0,
    'maximum' => 2147483647,
    'exclusiveMinimum' => 0,
    'exclusiveMaximum' => 2147483647,
);

$jsonSchema = new JsonSchema();

echo assert($expect == $jsonSchema->getSchema($value)), PHP_EOL;

/* integer */
$value = 123;
$expect = array(
    'type' => 'integer',
    'default' => 0,
    'minimum' => 0,
    'maximum' => 2147483647,
    'exclusiveMinimum' => 0,
    'exclusiveMaximum' => 2147483647
);

$jsonSchema = new JsonSchema();
echo assert($expect == $jsonSchema->getSchema($value)), PHP_EOL;
?>
--EXPECT--
number:
1
1