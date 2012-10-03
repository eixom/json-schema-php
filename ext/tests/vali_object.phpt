--TEST--
Check for jsonschema vali object
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

$schema = array(
    'type' => 'object',
    'properties' =>
    array(
        'name' =>
        array(
            'type' => 'string',
            'format' => 'regex',
            'pattern' => '/^[a-z0-9 ]+$/i',
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
echo assert($jsonSchema->validate($schema, $value)), PHP_EOL;


echo 'array:';
$value = array();
$value['name'] = 'a name';
$value['age'] = 23;
$value['height'] = 183.5;
echo assert($jsonSchema->validate($schema, $value));
?>
--EXPECT--
object:1
array:1