--TEST--
Check for jsonschema string error
--SKIPIF--

<?php
if (!extension_loaded("jsonschema"))
    print "skip";
?>
--FILE--
<?php
echo 'string:';
$value = 'test s p a c e s string';
$schema = array(
    'type' => 'string',
    'format' => 'regex',
    'pattern' => '/^[a-z.]+$/i',
    'minLength' => 0,
    'maxLength' => 2147483647,
);
$jsonSchema = new JsonSchema();
echo assert(!$jsonSchema->validate($schema, $value));
echo ',', count($jsonSchema->getErrors()), PHP_EOL;
?>
--EXPECT--
string:1,1