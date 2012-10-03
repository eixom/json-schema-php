--TEST--
Check for jsonschema bool error
--SKIPIF--

<?php
if (!extension_loaded("jsonschema"))
    print "skip";
?>
--FILE--
<?php
/* boolean */
$value = 12;
$jsonSchema = new JsonSchema();
echo 'boolean:';
echo assert(!$jsonSchema->validate(array(
            'type' => 'boolean',
            'default' => false,
                ), $value));
echo ',', count($jsonSchema->getErrors()), PHP_EOL;

/* integer or boolean */
echo 'integer or boolean:';
$value = "a string";
$jsonSchema = new JsonSchema();

echo assert(!$jsonSchema->validate(array(
            'type' => array('boolean', 'integer'),
            'default' => false,
                ), $value));
echo ',', count($jsonSchema->getErrors());

$value = 123.321;
$jsonSchema = new JsonSchema();
echo ',', assert(!$jsonSchema->validate(array(
            'type' => array('boolean', 'integer'),
            'default' => false,
                ), $value));
echo ',', count($jsonSchema->getErrors()), PHP_EOL;
?>
--EXPECT--
boolean:1,1
integer or boolean:1,2,1,2
