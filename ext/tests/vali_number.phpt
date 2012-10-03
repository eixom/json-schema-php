--TEST--
Check for jsonschema vali number
--SKIPIF--

<?php
if (!extension_loaded("jsonschema")) {
    print "skip";
}
?>
--FILE--
<?php
echo 'double:';
$value = 123.321;
$schema = array(
    'type' => 'number',
    'default' => 0,
    'minimum' => 0,
    'maximum' => 2147483647,
    'exclusiveMinimum' => 0,
    'exclusiveMaximum' => 2147483647,
);

$jsonSchema = new JsonSchema();
echo assert($jsonSchema->validate($schema, $value)), PHP_EOL;

echo 'integer:';
$value = 123;
$schema = array(
    'type' => 'integer',
    'default' => 0,
    'minimum' => 0,
    'maximum' => 2147483647,
    'exclusiveMinimum' => 0,
    'exclusiveMaximum' => 2147483647,
);

$jsonSchema = new JsonSchema();

echo assert($jsonSchema->validate($schema, $value)), PHP_EOL;
?>
--EXPECT--
double:1
integer:1