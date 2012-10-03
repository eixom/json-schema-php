--TEST--
Check for jsonschema generate array
--SKIPIF--
<?php
if (!extension_loaded("jsonschema")) {
    print "skip";
}
?>
--FILE--
<?php
echo 'array map:', PHP_EOL;
$value = array();
$value['name'] = 'a name';
$value['age'] = 23;
$value['height'] = 183.5;

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
            'maxLength' => 2147483647
        ),
        'age' =>
        array(
            'type' => 'integer',
            'default' => 0,
            'minimum' => 0,
            'maximum' => 2147483647,
            'exclusiveMinimum' => 0,
            'exclusiveMaximum' => 2147483647
        ),
        'height' =>
        array(
            'type' => 'number',
            'default' => 0,
            'minimum' => 0,
            'maximum' => 2147483647,
            'exclusiveMinimum' => 0,
            'exclusiveMaximum' => 2147483647
        )
    )
);

$jsonSchema = new JsonSchema();
echo assert($expect == $jsonSchema->getSchema((object) $value)), PHP_EOL;

$value = array();
$value[] = 'a name';
$value[] = 23;
$value[] = 183.5;

$expect = array(
    'type' => 'array',
    'minItems' => 0,
    'maxItems' => 20,
    'items' =>
    array(
        'type' => 'string',
        'format' => 'regex',
        'pattern' => '/^[a-z0-9]+$/i',
        'minLength' => 0,
        'maxLength' => 2147483647,
    )
);
$jsonSchema = new JsonSchema();
echo assert($expect == $jsonSchema->getSchema($value)), PHP_EOL;

$value = new stdClass();
$value->users = array();

$user = new stdClass();
$user->id = 1;
$user->account = 'userA';
$value->users[] = $user;

$user = new stdClass();
$user->id = 3;
$user->account = 'userB';
$value->users[] = $user;

$user = new stdClass();
$user->id = 5;
$user->account = 'userC';
$value->users[] = $user;

$expect = array(
    'type' => 'object',
    'properties' =>
    array(
        'users' =>
        array(
            'type' => 'array',
            'minItems' => 0,
            'maxItems' => 20,
            'items' =>
            array(
                'type' => 'object',
                'properties' =>
                array(
                    'id' =>
                    array(
                        'type' => 'integer',
                        'default' => 0,
                        'minimum' => 0,
                        'maximum' => 2147483647,
                        'exclusiveMinimum' => 0,
                        'exclusiveMaximum' => 2147483647,
                    ),
                    'account' =>
                    array(
                        'type' => 'string',
                        'format' => 'regex',
                        'pattern' => '/^[a-z0-9]+$/i',
                        'minLength' => 0,
                        'maxLength' => 2147483647,
                    )
                )
            )
        )
    )
);
$jsonSchema = new JsonSchema();
echo assert($expect == $jsonSchema->getSchema((object) $value)), PHP_EOL;
?>
--EXPECT--
array map:
1
1
1
