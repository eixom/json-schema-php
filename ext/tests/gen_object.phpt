--TEST--
Check for jsonschema generate object
--SKIPIF--
<?php
if (!extension_loaded("jsonschema")) {
    print "skip";
}
?>
--FILE--
<?php
echo 'object:';
$value = new stdClass();
$value->name = 'a name';
$value->age = 23;
$value->height = 183.5;

$expect = array(
    'type' => 'object',
    'properties' =>
    array(
        'name' =>
        array(
            'type' => 'string',
            'format' => 'regex',
            'pattern' => '/^[a-z0-9]+$/i',
            'minLength' => 0,
            'maxLength' => 2147483647,
        ),
        'age' =>
        array(
            'type' => 'integer',
            'default' => 0,
            'minimum' => 0,
            'maximum' => 2147483647,
            'exclusiveMinimum' => 0,
            'exclusiveMaximum' => 2147483647,
        ),
        'height' =>
        array(
            'type' => 'number',
            'default' => 0,
            'minimum' => 0,
            'maximum' => 2147483647,
            'exclusiveMinimum' => 0,
            'exclusiveMaximum' => 2147483647,
        )
    )
);
$jsonSchema = new JsonSchema();
echo assert($expect == $jsonSchema->getSchema($value)), PHP_EOL;
?>
--EXPECT--
object:1