/*
  +----------------------------------------------------------------------+
  | PHP Version 5                                                        |
  +----------------------------------------------------------------------+
  | Copyright (c) Moxie & Laruence                                       |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.php.net/license/3_01.txt                                  |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Author: Laruence    <laruence@gmail.com>                             |
  |         Moxie       <system128@gmail.com>                            |
  +----------------------------------------------------------------------+
  $Id$
 */

#ifndef PHP_JSONSCHEMA_H
#define PHP_JSONSCHEMA_H

extern zend_module_entry jsonschema_module_entry;
#define phpext_jsonschema_ptr &jsonschema_module_entry

#ifdef PHP_WIN32
#define PHP_JSONSCHEMA_API __declspec(dllexport)
#else
#define PHP_JSONSCHEMA_API
#endif

#ifdef ZTS
#include "TSRM.h"
#endif

PHP_MINIT_FUNCTION(jsonschema);
PHP_MSHUTDOWN_FUNCTION(jsonschema);
PHP_RINIT_FUNCTION(jsonschema);
PHP_RSHUTDOWN_FUNCTION(jsonschema);
PHP_MINFO_FUNCTION(jsonschema);


#ifdef ZTS
#define JSONSCHEMA_G(v) TSRMG(jsonschema_globals_id, zend_jsonschema_globals *, v)
#else
#define JSONSCHEMA_G(v) (jsonschema_globals.v)
#endif


PHP_JSONSCHEMA_API zval * php_jsonschema_get_schema(zval * value TSRMLS_DC);
PHP_JSONSCHEMA_API zend_bool php_jsonschema_check_by_type(HashTable * complex_schemas_table, HashTable * schema_table, HashTable * errors_table, zval * value TSRMLS_DC);
PHP_JSONSCHEMA_API void php_jsonschema_add_type(HashTable * complex_schemas_table, HashTable * type_schema_table TSRMLS_DC);

#endif	/* PHP_JSONSCHEMA_H */

/* vim: set tabstop=4 shiftwidth=4 softtabstop=4 expandtab: */
