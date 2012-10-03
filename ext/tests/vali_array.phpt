--TEST--
Check for jsonschema vali array
--SKIPIF--

<?php
if (!extension_loaded("jsonschema")) {
    print "skip";
}
?>
--FILE--
<?php
echo 'array map:';
$value = array();
$value['name'] = 'a name';
$value['age'] = 23;
$value['height'] = 183.5;

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
echo assert($jsonSchema->validate($schema, $value)), PHP_EOL;

echo 'array list:';
$value = array();
$value[] = 'str A';
$value[] = 'str B';
$value[] = 'str C';
$jsonSchema = new JsonSchema();
echo assert($jsonSchema->validate(array(
            'type' => 'array',
            'items' =>
            array(
                'type' => 'string',
                'format' => 'regex',
                'pattern' => '/^[a-z0-9\s]+$/i',
                'minLength' => 0,
                'maxLength' => 2147483647
            )
                ), $value)), PHP_EOL;


echo 'array list number:';
$value = array();
$value[] = 'str A';
$value[] = 'str B';
$value[] = 'str C';
$schema = array(
    'type' => 'array',
    'minItems' => 0,
    'maxItems' => 20,
    'items' =>
    array(
        'type' => 'string',
        'format' => 'regex',
        'pattern' => '/^[a-z0-9\\s]+$/i',
        'minLength' => 0,
        'maxLength' => 2147483647,
    )
);
echo assert($jsonSchema->validate($schema, $value)), PHP_EOL;


echo 'array list-map:';
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

$jsonSchema = new JsonSchema();
$schema = array(
    'type' => 'object',
    'properties' =>
    array(
        'users' =>
        array(
            'type' => 'array',
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
                        'minLength' => 0,
                        'maxLength' => 2147483647,
                    )
                )
            )
        )
    )
);
echo assert($jsonSchema->validate($schema, $value));
$value = array();
$value['users'][] = array('id' => 1, 'account' => 'userA');
$value['users'][] = array('id' => 3, 'account' => 'userB');
$value['users'][] = array('id' => 5, 'account' => 'userC');
echo ',', assert($jsonSchema->validate($schema, $value)), PHP_EOL;
?>
--EXPECT--
array map:1
array list:1
array list number:1
array list-map:1,1