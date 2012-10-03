--TEST--
Check for jsonschema vali string
--SKIPIF--

<?php
if (!extension_loaded("jsonschema")) {
    print "skip";
}
?>
--FILE--
<?php
echo 'string:';
$value = 'teststring';
$schema = array(
    'type' => 'string',
    'format' => 'regex',
    'pattern' => '/^[a-z]+$/i',
    'minLength' => 0,
    'maxLength' => 2147483647,
);

$jsonSchema = new JsonSchema();
echo assert($jsonSchema->validate($schema, $value));
?>
--EXPECT--
string:1