--TEST--
Check for jsonschema vali bool
--SKIPIF--

<?php
if (!extension_loaded("jsonschema")) {
    print "skip";
}
?>
--FILE--
<?php
echo 'boolean:';
$value = true;
$jsonSchema = new JsonSchema();

echo assert($jsonSchema->validate(array('type' => 'boolean', 'default' => false), $value)), PHP_EOL;

echo 'integer or boolean:';
$value = true;
$jsonSchema = new JsonSchema();
echo assert($jsonSchema->validate(array('type' => array('boolean', 'integer'), 'default' => false), $value));
$value = 123;
$jsonSchema = new JsonSchema();
echo ',', assert($jsonSchema->validate(array('type' => array('boolean', 'integer'), 'default' => false), $value)), PHP_EOL;
?>
--EXPECT--
boolean:1
integer or boolean:1,1
