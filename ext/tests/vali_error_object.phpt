--TEST--
Check for jsonschema object error
--SKIPIF--

<?php
if (!extension_loaded("jsonschema"))
    print "skip";
?>
--FILE--
<?php
echo 'object:';
$value = new stdClass();
$value->name = 'a name';
$value->age = 30;
$value->height = "183";
$schema = array(
    'type' => 'object',
    'properties' =>
    array(
        'name' =>
        array(
            'type' => 'boolean',
        ),
        'age' =>
        array(
            'type' => 'integer',
            'default' => 0,
            'minimum' => 20,
            'maximum' => 25,
            'exclusiveMinimum' => 20,
            'exclusiveMaximum' => 25,
        ),
        'height' =>
        array(
            'type' => 'number',
            'default' => 0,
            'minimum' => 0,
            'maximum' => 2147483647,
            'exclusiveMinimum' => 0,
            'exclusiveMaximum' => 2147483647,
        ),
    ),
);

$jsonSchema = new JsonSchema();

echo assert(!$jsonSchema->validate($schema, $value));
echo ',', count($jsonSchema->getErrors()), PHP_EOL;
?>
--EXPECT--
object:1,1
