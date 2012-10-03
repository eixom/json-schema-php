PHP_ARG_ENABLE(jsonschema, whether to enable jsonschema support,
[  --enable-jsonschema     Enable jsonschema support])

if test "$PHP_JSONSCHEMA" != "no"; then
  PHP_NEW_EXTENSION(jsonschema, jsonschema.c, $ext_shared)
fi
