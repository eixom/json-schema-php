<?php

/*
 * moxie (system128@gmail.Com) 2010-12-11
 *
 * Copyright &copy; 2010-2010 Zoeey.Org
 * Code license: GNU Lesser General Public License Version 3
 * http://www.gnu.org/licenses/lgpl-3.0.txt
 *
 * website:
 * http://code.google.com/p/json-schema-php/
 */

/**
 * JSON Schema generate/validate
 *
 * @author moxie(system128@gmail.com)
 */
class JsonSchema {

    /**
     * Last error
     *
     * @var array
     */
    private $errors;

    /**
     * Extend types
     *
     * @var map
     */
    private $complexTypes;

    /**
     *
     * @param string $json
     */
    function __construct() {
        $this->errors = array();
        $this->complexTypes = array();
    }

    /**
     * Generate JSON Schema
     *
     * @param mixed $value
     * @return array Schema
     */
    public function getSchema($value) {
        return $this->genByType($value);
    }

    /**
     * Generate JSON Schema by type
     *
     * @param mixed $value
     * @return object
     */
    private function genByType($value) {
        $type = gettype($value);
        $schema = array();
        switch ($type) {
            case 'boolean':
                $schema['type'] = 'boolean';
                $schema['default'] = false;
                break;
            case 'integer':
                $schema['type'] = 'integer';
                $schema['default'] = 0;
                $schema['minimum'] = 0;
                $schema['maximum'] = PHP_INT_MAX;
                $schema['exclusiveMinimum'] = 0;
                $schema['exclusiveMaximum'] = PHP_INT_MAX;
                break;
            case 'double':
                $schema['type'] = 'number';
                $schema['default'] = 0;
                $schema['minimum'] = 0;
                $schema['maximum'] = PHP_INT_MAX;
                $schema['exclusiveMinimum'] = 0;
                $schema['exclusiveMaximum'] = PHP_INT_MAX;
                break;
            case 'string':
                $schema['type'] = 'string';
                $schema['format'] = 'regex';
                $schema['pattern'] = '/^[a-z0-9]+$/i';
                $schema['minLength'] = 0;
                $schema['maxLength'] = PHP_INT_MAX;
                break;
            case 'array':
                $schema['type'] = 'array';
                $schema['minItems'] = 0;
                $schema['maxItems'] = 20;
                $items = array();
                foreach ($value as $value) {
                    $items = $this->genByType($value);
                    break;
                }
                $schema['items'] = $items;
                break;
            case 'object':
                $schema['type'] = 'object';
                $items = array();
                $value = get_object_vars($value);
                foreach ($value as $key => $value) {
                    $items[$key] = $this->genByType($value);
                }
                $schema['properties'] = $items;
                break;
            case 'null': // any in union types
                $schema['type'] = 'null';
                break;
            default:
                break;
        }
        return $schema;
    }

    /**
     * Set type schema
     * @param array $typeSchema
     */
    public function addType($typeSchema) {
        if (is_array($typeSchema) && isset($typeSchema['id'])) {
            $this->complexTypes[$typeSchema['id']] = $typeSchema;
        }
    }

    /**
     * Get type schema
     *
     * @param string ref
     * @return string schema
     */
    private function getType($ref) {
        if (isset($this->complexTypes[$ref])) {
            return $this->complexTypes[$ref];
        }
        return null;
    }

    /**
     * Validate JSON
     *
     * @param array $schema JSON Schema
     * @param mixed $value data
     * @return boolean
     */
    public function validate($schema, $value) {
        $isVali = false;
        do {
            if (!is_array($schema) || (!isset($schema['type']) && !isset($schema['$ref']))) {
                $this->addError('schema parse error. (PHP 5 >= 5.3.0) see json_last_error(void) .');
                break;
            }
            $isVali = $this->checkByType($schema, $value);
        } while (false);
        return $isVali;
    }

    /**
     *  date check (ISO 8601 : YYYY-MM-DD)
     * @param string $date
     */
    private function checkDate($date) {
        $isVali = false;
        do {
            $parts = explode('-', $date);
            $size = count($parts);
            if ($size != 3) {
                $this->addError(sprintf('value: "%s" is not a date format of "ISO 8601"  .', $date));
                break;
            }
            // Validate Gregorian date

            $isVali = checkdate($parts[1], $parts[2], $parts[0]);
            if (!$isVali) {
                $this->addError(sprintf('value: "%s" is not a validate Gregorian date  .', $date));
            }
        } while (false);
        return $isVali;
    }

    /**
     *  time check (hh:mm:ss)
     * @param string $time
     */
    private function checkTime($time) {
        $isVali = false;
        do {
            $parts = explode(':', $time);
            $size = count($parts);

            if ($size != 3) {
                $this->addError(sprintf('value: "%s" time format is incorrect (hh:mm:ss)  .', $time));
                break;
            }
            $part = $parts[0];

            if (!is_numeric($part) || $part < 1 || $part > 23) {
                $this->addError(sprintf('value: "%s" time format is incorrect,the "hour" is out of range  .', $time));
                break;
            }
            $part = $parts[1];
            if (!is_numeric($part) || $part < 1 || $part > 59) {
                $this->addError(sprintf('value: "%s" time format is incorrect,the "minute" is out of range  .', $time));
                break;
            }
            $part = $parts[2];
            if (!is_numeric($part) || $part < 1 || $part > 59) {
                $this->addError(sprintf('value: "%s" time format is incorrect,the "second" is out of range  .', $time));
                break;
            }
            $isVali = true;
        } while (false);
        return $isVali;
    }

    /**
     * check type: string
     * http://tools.ietf.org/html/draft-zyp-json-schema-03#section-5.1
     *
     * @param string $value
     * @param array $schema
     */
    private function checkString($schema, $value) {
        // string
        $isVali = false;
        do {
            /* noti: is_string -> is_scalar for utc-millisec */
            if (!is_scalar($value)) {
                $this->addError(sprintf('value: "%s" is not a string .', $value));
                break;
            }
            $len = strlen($value);
            if (isset($schema['minLength'])) {
                if ($schema['minLength'] > $len) {
                    $this->addError(sprintf('value: "%s" is too short .', $value));
                    break;
                }
            }
            if (isset($schema['maxLength'])) {
                if ($schema['maxLength'] < $len) {
                    $this->addError(sprintf('value: "%s" is too long .', $value));
                    break;
                }
            }
            if (isset($schema['format'])) {

                switch ($schema['format']) {

                    case 'date-time':
                        /**
                         * date-time  This SHOULD be a date in ISO 8601 format of
                         * YYYY-MM-DDThh:mm:ssZ in UTC time.  This is the recommended form of date/timestamp.
                         */
                        if ($len == 20
                                && $value{4} == '-' && $value{7} == '-'
                                && $value{10} == 'T'
                                && $value{13} == ':' && $value{16} == ':'
                                && $value{19} == 'Z'
                        ) {
                            if (!$this->checkDate(substr($value, 0, 10))) {
                                break;
                            }
                            if (!$this->checkTime(substr($value, 11, 8))) {
                                break;
                            }
                            $isVali = true;
                        } else {
                            $this->addError(sprintf('"%s" date-time format is incorrect .', $value));
                        }
                        break;
                    case 'date':
                        /**
                         * date  This SHOULD be a date in the format of YYYY-MM-DD.  It is
                         * recommended that you use the "date-time" format instead of "date"
                         * unless you need to transfer only the date part.
                         */
                        if ($len == 10 && $value{4} == '-' && $value{7} == '-') {
                            $isVali = $this->checkDate($value);
                            break;
                        }
                        $this->addError(sprintf('"%s" date format is incorrect .', $value));
                        break;
                    case 'time':
                        /**
                         * time  This SHOULD be a time in the format of hh:mm:ss.  It is
                         * recommended that you use the "date-time" format instead of "time"
                         * unless you need to transfer only the time part.
                         */
                        if ($len == 8 && $value{2} == ':' && $value{5} == ':') {
                            $isVali = $this->checkTime($value);
                            break;
                        }
                        $this->addError(sprintf('"%s" time format is incorrect .', $value));

                        break;
                    case 'utc-millisec':
                        /**
                         * utc-millisec  This SHOULD be the difference, measured in
                         * milliseconds, between the specified time and midnight, 00:00 of
                         * January 1, 1970 UTC.  The value SHOULD be a number (integer or
                         * float).
                         */
                        $isVali = is_numeric($value);

                        if (!$isVali) {
                            $this->addError(sprintf('"%s" utc-millisec format is incorrect .', $value));
                        }

                        break;
                    case 'regex':
                        /**
                         * regex  A regular expression, following the regular expression
                         * specification from ECMA 262/Perl 5.
                         */
                        if (!isset($schema['pattern'])) {
                            $this->addError('format-regex: pattern is undefined .');
                            break;
                        }

                        $pattern = $schema['pattern'];
                        if (preg_match($pattern, $value)) {
                            $isVali = true;
                            break;
                        }
                        $this->addError(sprintf('"%s" does not match "%s"', $value, $pattern));
                        break;
                    case 'color':
                        /**
                         * color  This is a CSS color (like "#FF0000" or "red"), based on CSS
                         * 2.1 [W3C.CR-CSS21-20070719].
                         * http://www.w3.org/TR/2007/CR-CSS21-20070719/syndata.html#color-units
                         */
                        if ($len < 1) {
                            $this->addError(sprintf('value: "%s" ,is a incorrect three-digit RGB notation.'
                                            , $value));
                            break;
                        }
                        $hex = '0123456789abcdef';
                        if ($value{0} == '#') {

                            // #rgb #f00
                            if ($len == 4) {
                                for ($i = 1; $i < 4; $i++) {
                                    if (stripos($hex, $value{$i}) === false) {
                                        $this->addError(sprintf('value: "%s" ,is a incorrect three-digit RGB notation.'
                                                        , $value));
                                        break 2;
                                    }
                                }
                                $isVali = true;
                            }

                            // #rrggbb #ff0000
                            if ($len == 7) {
                                for ($i = 1; $i < 7; $i++) {
                                    if (stripos($hex, $value{$i}) === false) {
                                        $this->addError(sprintf('value: "%s" ,is a incorrect six-digit RGB notation.'
                                                        , $value));
                                        break 2;
                                    }
                                }
                                $isVali = true;
                            }
                            $this->addError(sprintf('value: "%s" ,is not a three-digit RGB notation either six-digit .'
                                            , $value));
                            break;
                        }

                        // noti: user should 'trim' at first
                        if (strcmp(substr($value, 0, 3), 'rgb') === 0) {
                            // noti: rgb(fff,00f,022) rgb(fff%,00f%,022%) will be passed
                            if (preg_match('/^rgb\s*\(\s*[0-9a-f]+%?\s*,\s*[0-9a-f]+%?\s*,\s*[0-9a-f]+%?\s*\)$/i', $value)) {
                                $isVali = true;
                                break;
                            }

                            $this->addError(sprintf('value: "%s" ,is not a format rgb color .'
                                            , $value));
                            break;
                        }

                        $colors = array(
                            'maroon', 'red', 'orange', 'yellow', 'olive'
                            , 'purple', 'fuchsia', 'white', 'lime', 'green'
                            , 'navy', 'blue', 'aqua', 'teal'
                            , 'black', 'silver', 'gray'
                        );
                        if (in_array($value, $colors)) {
                            $isVali = true;
                            break;
                        }
                        $this->addError(sprintf('value: "%s" ,is not a CSS color .', $value));
                        break;
                    case 'style':
                        /**
                         * style  This is a CSS style definition (like "color: red; background-
                         * color:#FFF"), based on CSS 2.1 [W3C.CR-CSS21-20070719].
                         */
                        break;
                    case 'phone':
                        /**
                         * phone  This SHOULD be a phone number (format MAY follow E.123).
                         * http://en.wikipedia.org/wiki/E.123
                         */
                        if (preg_match("/^(\(0?[0-9]{2}\) \d{3,4}\s?\d{4}|\+\d{2} \d{2} \d{3,4}\s?\d{4})$/", $value)) {
                            $isVali = true;
                            break;
                        }
                        $this->addError(sprintf('value: "%s" is not a phone number .', $value));
                        break;
                    case 'uri':
                        /**
                         * uri  This value SHOULD be a URI..
                         */
                        if (filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED)) {
                            $isVali = true;
                            break;
                        }
                        $this->addError(sprintf('value: "%s" is not a URI .', $value));

                    case 'email':
                        /**
                         *  email  This SHOULD be an email address.
                         */
                        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $isVali = true;
                            break;
                        }
                        $this->addError(sprintf('value: "%s" is not an email .', $value));
                        break;
                    case 'ip-address':
                        /**
                         *  ip-address  This SHOULD be an ip version 4 address.
                         */
                        if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                            $isVali = true;
                            break;
                        }
                        $this->addError(sprintf('value: "%s" is not a ipv4 address .', $value));
                        break;
                    case 'ipv6':
                        /**
                         *  ipv6  This SHOULD be an ip version 6 address.
                         */
                        if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                            $isVali = true;
                            break;
                        }

                        $this->addError(sprintf('value: "%s" is not a ipv6 address .', $value));
                        break;
                    case 'host':
                    case 'host-name':
                        /**
                         *  host-name  This SHOULD be a host-name.
                         *  http://tools.ietf.org/html/rfc810
                         */
                        $letter = 'abcdefghijklmnopqrstuvwxyz0123456789';
                        $chs = $letter . '.-';
                        if ($len < 1) {
                            $this->addError('value is a zero-length string .');
                            break;
                        }
                        // No distinction is made between upper and lower case
                        $value = strtolower($value);
                        $ch = $value{0};
                        if (stripos($letter, $ch) === false) {
                            $this->addError(sprintf('value: "%s" ,should start with letter .'
                                            , $value));
                            break;
                        }
                        // not end with minus sign or period
                        $ch = $value{$len - 1};
                        if ($ch == ' .' || $ch == '-') {
                            $this->addError(sprintf('value: "%s" ,cannot end with minus sign or period  .'
                                            , $value));
                            break;
                        }

                        $last = null;
                        for ($i = 1; $i < $len; $i++) {
                            $ch = $value{$i};
                            if ($ch == '.' || $ch == '-') {

                                if ($last == '.' || $last == '-') {
                                    $this->addError(sprintf('value: "%s" '
                                                    . ',minus sign and period cannot associated with .'
                                                    , $value));
                                    break 2;
                                }
                            }
                            if (stripos($chs, $ch) === false) {
                                $this->addError(sprintf('value: "%s" ,Host name drawn '
                                                . ' from the alphabet (A-Z), digits (0-9)'
                                                . ', and the minus sign (-) and period (.)'
                                                , $value));
                                break 2;
                            }
                            $last = $ch;
                        }
                        $isVali = true;
                        break;

                    default:
                        $this->addError(sprintf('format: "%s" is undefined .', $schema['format']));
                        break;
                }
                break;
            }

            $isVali = true;
        } while (false);
        return $isVali;
    }

    /**
     * check type: integer/double
     *
     * @param number $value
     * @param array $schema
     * @return boolean
     */
    private function checkNumber($schema, $value) {
        // number
        $isVali = false;
        do {

            if (!is_numeric($value)) {
                $this->addError($value . ' is not a number .');
                break;
            }
            if (isset($schema['minimum'])) {
                if ($schema['minimum'] > $value) {
                    $this->addError(sprintf('%s is less than %s .', $value, $schema['minimum']));
                    break;
                }
            }
            if (isset($schema['maximum'])) {
                if ($schema['maximum'] < $value) {
                    $this->addError(sprintf('%s is bigger than %s .', $value, $schema['maximum']));
                    break;
                }
            }
            if (isset($schema['exclusiveMinimum'])) {
                if ($schema['exclusiveMinimum'] >= $value) {
                    $this->addError(sprintf('%s is less or than %s or equal .', $value, $schema['exclusiveMinimum']));
                    break;
                }
            }
            if (isset($schema['exclusiveMaximum'])) {
                if ($schema['exclusiveMaximum'] <= $value) {
                    $this->addError(sprintf('%s is bigger than %s or equal .', $value, $schema['exclusiveMaximum']));
                    break;
                }
            }
            $isVali = true;
        } while (false);

        return $isVali;
    }

    /**
     * check type: integer
     *
     * @param integer $value
     * @param array $schema
     * @return boolean
     */
    private function checkInteger($schema, $value) {
        // integer
        if (!is_integer($value)) {
            $this->addError(sprintf('value:"%s" is not a integer .', $value));
            return false;
        }
        return $this->checkNumber($schema, $value);
    }

    /**
     * check type: boolean
     *
     * @param boolean $value
     * @param array $schema
     * @return boolean
     */
    private function checkBoolean($value) {
        // boolean
        if (!is_bool($value)) {
            $this->addError(sprintf('value: "%s" is not a boolean .', $value));
            return false;
        }
        return true;
    }

    /**
     * check type: object
     *
     * @param object $valueProp
     * @param array $schema
     * @return boolean
     */
    private function checkObject($schema, $value) {
        // object
        $isVali = false;
        do {
            if (!is_object($value)) {
                $this->addError(sprintf('value: "%s" is not an object .', $value));
                break;
            }
            if (isset($schema['properties'])
                    && !empty($schema['properties'])
            ) {
                $schemaProp = $schema['properties'];
                $valueProp = get_object_vars($value);
                $valueKeys = array_keys($valueProp);
                $schemaKeys = array_keys($schemaProp);
                /*
                 * fixed: diffKeys is empty when valueKeys is subset of schemaKeys
                 */
                $diffKeys = array_diff($schemaKeys, $valueKeys);
                if (!empty($diffKeys)) {
                    foreach ($diffKeys as $key) {
                        // property not defined / not required
                        if (!isset($schemaProp[$key])
                                || (
                                isset($schemaProp[$key]['required'])
                                &&
                                $schemaProp[$key]['required']
                                )
                        ) {
                            $this->addError(sprintf('key: "%s" is not exist,And it\'s not a optional property .', get_class($value)));
                            break 2;
                        }
                    }
                }
                foreach ($schemaProp as $key => $sch) {
                    if (!isset($valueProp[$key])) {
                        continue;
                    }
                    if (!$this->checkByType($sch, $valueProp[$key])) {
                        break 2;
                    }
                }
            }
            $isVali = true;
        } while (false);
        return $isVali;
    }

    /**
     * check type: array
     *
     * @param array $value
     * @param array $schema
     * @return boolean
     */
    private function checkArray($schema, $value) {
        $isVali = false;
        do {
            if (!is_array($value)) {
                $this->addError(sprintf('value: "%s" is not an array .', $value));
                break;
            }

            if (!isset($schema['items'])) {
                $this->addError('schema: items schema is undefined .');
                break;
            }
            $size = count($value);
            if (isset($schema['minItems'])) {
                if ($schema['minItems'] > $size) {
                    $this->addError(sprintf('array size: %s  is less than %s .', $size, $schema['minItems']));
                    break;
                }
            }
            if (isset($schema['maxItems'])) {
                if ($schema['maxItems'] < $size) {
                    $this->addError(sprintf('array size: %s is bigger than %s .', $size, $schema['maxItems']));
                    break;
                }
            }

            foreach ($value as $val) {
                if (!$this->checkByType($schema['items'], $val)) {
                    break 2;
                }
            }


            $isVali = true;
        } while (false);
        return $isVali;
    }

    /**
     * check value based on type
     *
     * @param mixed $value
     * @param array $schema
     * @return boolean
     */
    private function checkByType($schema, $value) {
        $isVali = false;
        if ($schema && isset($schema['type'])) {
            // union types
            if (is_array($schema['type'])) {
                $types = $schema['type'];
                foreach ($types as $type) {
                    $schema['type'] = $type;
                    $isVali = $this->checkByType($schema, $value);
                    if ($isVali) {
                        break;
                    }
                }
            } else {
                $type = $schema['type'];
                switch ($type) {
                    case 'boolean':
                        $isVali = $this->checkBoolean($value);
                        break;
                    case 'integer':
                        $isVali = $this->checkInteger($schema, $value);
                        break;
                    case 'number':
                        $isVali = $this->checkNumber($schema, $value);
                        break;
                    case 'string':
                        $isVali = $this->checkString($schema, $value);
                        break;
                    case 'array':
                        $isVali = $this->checkArray($schema, $value);
                        break;
                    case 'object':
                        $isVali = $this->checkObject($schema, $value);
                        break;
                    case 'enum':
                        $isVali = is_null($value);
                        break;
                    case 'null':
                        $isVali = is_null($value);
                        break;
                    case 'any':
                        $isVali = true;
                        break;
                    default:
                        $this->addError(sprintf('type_schema: "%s" is undefined .', $value));
                        break;
                }
            }
        }

        if (isset($schema['$ref'])) {
            $isVali = $this->checkByType($this->getType($schema['$ref']), $value);
        }
        return $isVali;
    }

    /**
     *  Get errors
     *
     * @return array errors
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * add error message
     * @param string $msg
     */
    protected function addError($msg) {
        $this->errors[] = htmlentities($msg);
    }

}

?>
