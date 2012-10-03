--TEST--
Check for jsonschema number error
--SKIPIF--

<?php
if (!extension_loaded("jsonschema"))
    print "skip";
?>
--FILE--
<?php
/* double */
echo 'double:';
$value = 123.321;
$jsonSchema = new JsonSchema( );

echo assert(!$jsonSchema->validate(array(
            'type' => 'number'
            , 'default' => 0
            , 'minimum' => 0
            , 'maximum' => 120
            , 'exclusiveMinimum' => 0
            , 'exclusiveMaximum' => 2147483647
                ), $value));
echo ',', count($jsonSchema->getErrors()), PHP_EOL;

/* integer */
echo 'integer:';
$value = 123;
$jsonSchema = new JsonSchema();
echo assert(!$jsonSchema->validate(array(
            'type' => 'integer'
            , 'default' => 0
            , 'minimum' => 321
            , 'maximum' => 2147483647
            , 'exclusiveMinimum' => 0
            , 'exclusiveMaximum' => 2147483647
                ), $value));
echo ',', count($jsonSchema->getErrors()), PHP_EOL;
?>
--EXPECT--
double:1,1
integer:1,1
