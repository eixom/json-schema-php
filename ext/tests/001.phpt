--TEST--
Check for jsonschema presence
--SKIPIF--
<?php if (!extension_loaded("jsonschema")) print "skip"; ?>
--FILE--
<?php
echo "jsonschema extension is available";
?>
--EXPECT--
jsonschema extension is available