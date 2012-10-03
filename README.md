Json Schema PHP
===============

[![Build Status](https://secure.travis-ci.org/zoeey/json-schema-php.png)](http://travis-ci.org/zoeey/json-schema-php)

PHP与PHP扩展实现的JSON结构验证工具。

JSON Schema 用于描述JSON数据的结构和类型，如同DTD与XML的关系。

本实现用于使用 PHP 调用 JSON Schema 对 JSON 数据进行验证。

建议PHP版本 PHP 5 >= 5.2.0、PECL json >= 1.2.0。 


## 下载（2012-10-03 更新）
压缩包：
[json-schema-php_latest.zip](https://github.com/downloads/zoeey/json-schema-php/json-schema-php_latest.zip)

版本库：
```
git clone git://github.com/zoeey/json-schema-php.git
```

dll：


* [php_jsonschema-2.0-x86-5.2-zts.dll](https://github.com/zoeey/json-schema-php/raw/master/dll/php_jsonschema-2.0-x86-5.2-zts.dll)
* [php_jsonschema-2.0-x86-5.2-nts.dll](https://github.com/zoeey/json-schema-php/raw/master/dll/php_jsonschema-2.0-x86-5.2-nts.dll)
* [php_jsonschema-2.0-x86-5.3-zts.dll](https://github.com/zoeey/json-schema-php/raw/master/dll/php_jsonschema-2.0-x86-5.3-zts.dll)
* [php_jsonschema-2.0-x86-5.3-nts.dll](https://github.com/zoeey/json-schema-php/raw/master/dll/php_jsonschema-2.0-x86-5.3-nts.dll)

## 手册下载（2012-10-03 更新）
<table>
    <tr>
        <td>格式 </td>
        <td>地址 </td>
        <td>checksum</td>
        <td>备注 </td>
    </tr>
    <tr>
        <td>chm</td>
        <td>
            <a href="https://github.com/zoeey/json-schema-php/raw/master/docs/build/json-schema-php.chm" rel="nofollow">chm</a>
        </td>
        <td>
            <a href="https://github.com/zoeey/json-schema-php/raw/master/docs/build/json-schema-php.chm.checksum" rel="nofollow">checksum</a>
        </td>
        <td>无法查看：文件属性，解除锁定 </td>
    </tr>
    <tr>
        <td> html </td>
        <td>
            <a href="https://github.com/zoeey/json-schema-php/raw/master/docs/build/json-schema-php-html.zip" rel="nofollow">zip</a>
        </td>
        <td>
            <a href="https://github.com/zoeey/json-schema-php/raw/master/docs/build/json-schema-php-html.zip.checksum" rel="nofollow">checksum</a>
        </td>
        <td>
        </td>
    </tr>
    <tr>
        <td> single  </td>
        <td>
            <a href="https://github.com/zoeey/json-schema-php/raw/master/docs/build/json-schema-php-single.zip" rel="nofollow"> zip</a>
        </td>
        <td>
            <a href="https://github.com/zoeey/json-schema-php/raw/master/docs/build/json-schema-php-single.zip.checksum" rel="nofollow">checksum</a>
        </td>
        <td>
        </td>
    </tr>
    <tr>
        <td>pdf  </td>
        <td>
            <a href="https://github.com/zoeey/json-schema-php/raw/master/docs/build/json-schema-php.pdf" rel="nofollow"> pdf</a>
        </td>
        <td>
            <a href="https://github.com/zoeey/json-schema-php/raw/master/docs/build/json-schema-php.pdf.checksum" rel="nofollow">checksum</a>
        </td>
        <td>
        </td>
    </tr>
</table>

## 扩展版安装 

```
[    /]# cd /php-src/ext/jsonschema
[zoeey]# /path/of/php/bin/phpize
[zoeey]# ./configure --with-php-config=/path/of/php/bin/php-config
[zoeey]# make
[zoeey]# make install
[zoeey]# cp modules/jsonschema.so /path/of/php/lib/php_jsonschema.so (just in case:) (extension_dir=lib/)
[zoeey]# make clean
```

## 生成 JSON Schema
```
$value = new stdClass();
$value->name = 'a name';
$value->age = 23;
$value->height = 183.5;
$jsonSchema = new JsonSchema();
var_dump($jsonSchema->getSchema($value));
```
结果 
```
array(
    'type' => 'object',
    'properties' =>
    array(
        'name' =>
        array(
            'type' => 'string',
            'format' => 'regex',
            'pattern' => '/^[a-z0-9]+$/i',
            'minLength' => 0,
            'maxLength' => 2147483647,
        ),
        'age' =>
        array(
            'type' => 'integer',
            'default' => 0,
            'minimum' => 0,
            'maximum' => 2147483647,
            'exclusiveMinimum' => 0,
            'exclusiveMaximum' => 2147483647,
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
)
```
## 使用 JSON Schema 验证 JSON
```
$value = new stdClass();
$value->name = 'a name';
$value->age = 23;
$value->height = 183.5;

$schema = array(
    'type' => 'object',
    'properties' =>
    array(
        'name' =>
        array(
            'type' => 'string',
            'format' => 'regex',
            'pattern' => '/^[a-z0-9 ]+$/i',
            'minLength' => 0,
            'maxLength' => 2147483647,
        ),
        'age' =>
        array(
            'type' => 'integer',
            'default' => 0,
            'minimum' => 0,
            'maximum' => 2147483647,
            'exclusiveMinimum' => 0,
            'exclusiveMaximum' => 2147483647,
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
);

$jsonSchema = new JsonSchema();
var_dump($jsonSchema->validate($schema, $value)), PHP_EOL;
```

## 联系作者

email: system128 at gmail dot com

qq: 59.43.59.0

