--TEST--
Check for jsonschema ref error
--SKIPIF--

<?php
if (!extension_loaded("jsonschema"))
    print "skip";
?>
--FILE--
<?php
$userType = '
            {
                "id": "user",
                "description": "user info",
                "type": "object",
                "optional": true,
                "properties": {
                    "account": {"type": "boolean"},
                    "email": {"type": "string", "optional": true}
                }
            }';

/* array list<map> */
echo 'arrray list<map>:';
$value = array();
$value['users'][] = array('account' => 'userA', 'email' => 'userA@example.com');
$value['users'][] = array('account' => 'userB', 'email' => 'userB@example.com');
$value['users'][] = array('account' => 'userC', 'email' => 'userC@example.com');
$jsonSchema = new JsonSchema();
$jsonSchema->addType(json_decode($userType, true));

echo assert(!$jsonSchema->validate(array(
            '$ref' => 'user'
                ), $value));
echo ',', count($jsonSchema->getErrors());

echo ',', assert(!$jsonSchema->validate(array(
            'type' => 'object',
            'properties' => array(
                'users' => array(
                    'type' => 'array',
                    'items' => array(
                        '$ref' => 'user'
                    )
                )
            )
                ), $value));
echo ',', count($jsonSchema->getErrors()), PHP_EOL;
?>
--EXPECT--
arrray list<map>:1,2,1,3


