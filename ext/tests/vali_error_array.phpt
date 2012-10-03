--TEST--
Check for jsonschema array error
--SKIPIF--

<?php
if (!extension_loaded("jsonschema"))
    print "skip";
?>
--FILE--
<?php
echo 'array map:';
/* array Map */
$value = array();
$value['name'] = 'a name';
$value['age'] = 23;
$value['height'] = 183.5;

$jsonSchema = new JsonSchema( );
echo assert(!$jsonSchema->validate(array(
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
                )
            )
                ), $value));
echo ',', count($jsonSchema->getErrors()), PHP_EOL;


/* array list */
echo 'array list:';
$value = array();
$value[] = 'str A';
$value[] = 'str B';
$value[] = 'str C';

$jsonSchema = new JsonSchema();
echo assert(!$jsonSchema->validate(array(
            'type' => 'array',
            'items' =>
            array(
                'type' => 'string',
                'format' => 'regex',
                'pattern' => '/^[a-z0-9]+$/i',
                'minLength' => 0,
                'maxLength' => 2147483647,
            ),
                ), $value));
echo ',', count($jsonSchema->getErrors()), PHP_EOL;


/* array List<Map> */
echo 'array list<map>:';
$value = array();
$value['users'][] = array('id' => 1, 'account' => 'userA');
$value['users'][] = array('id' => 3, 'account' => 'userB');
$value['users'][] = array('id' => 5, 'account' => 'userC');
$jsonSchema = new JsonSchema( );

echo assert(!$jsonSchema->validate(array(
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
                                'maxLength' => 3,
                            )
                        )
                    )
                )
            )
                ), $value));
echo ',', count($jsonSchema->getErrors());



/* array list<map> */
$value = array();
$value['users'][] = array('id' => 1, 'account' => 'userA');
$value['users'][] = array('id' => 3, 'account' => 'userB');
$value['users'][] = array('id' => 5, 'account' => 'userC');
$jsonSchema = new JsonSchema( );
echo ',', assert(!$jsonSchema->validate(array(
            'type' => 'object',
            'properties' =>
            array(
                'users' =>
                array(
                    'type' => 'array',
                    'minItems' => 20,
                    'maxItems' => 50,
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
                                'maxLength' => 3,
                            )
                        )
                    )
                )
            )
                ), $value));
echo ',', count($jsonSchema->getErrors()), PHP_EOL;
?>
--EXPECT--
array map:1,1
array list:1,1
array list<map>:1,1,1,1
