--TEST--
Check for jsonschema vali ref
--SKIPIF--

<?php
if (!extension_loaded("jsonschema")) {
    print "skip";
}
?>
--FILE--
<?php
echo 'array list<map>:';
$value = array();
$value['users'][] = array('account' => 'userA', 'email' => 'userA@example.com');
$value['users'][] = array('account' => 'userB', 'email' => 'userB@example.com');
$value['users'][] = array('account' => 'userC', 'email' => 'userC@example.com');

$user_schema = array(
    'id' => 'user',
    'description' => 'user info',
    'type' => 'object',
    'optional' => true,
    'properties' =>
    array(
        'account' =>
        array(
            'type' => 'string',
        ),
        'email' =>
        array(
            'type' => 'string',
            'optional' => true,
        ),
    ),
);
$schema = array(
    'type' => 'object',
    'properties' =>
    array(
        'users' =>
        array(
            'type' => 'array',
            'items' =>
            array(
                '$ref' => 'user',
            )
        )
    )
);
$jsonSchema = new JsonSchema();
$jsonSchema->addType($user_schema);
echo assert($jsonSchema->validate($schema, $value));

echo ',', assert($jsonSchema->validate(array(
    'type' => 'object',
    'properties' =>
    array(
        'users' =>
        array(
            'type' => 'array',
            'items' =>
            array(
                '$ref' => 'user',
            )
        )
    )
), $value)), PHP_EOL;
?>
--EXPECT--
array list<map>:1,1
