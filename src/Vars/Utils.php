<?php

namespace App\Vars;

class Utils
{
    public static function avatar($username='')
    {
        if (isset($username) && !empty($username)) {
            return 'https://api.adorable.io/avatars/200/'. $username .'.png';
        }

        return 'https://api.adorable.io/avatars/200/';
    }

    public static function dump($value, $die=true)
    {
        echo '<pre>';
        var_dump($value);
        echo '</pre>';

        if ($die) die();
    }

    public static function responseAPI($rsp, $status, $message, $data=[], $forceEmpty=false)
    {
        $arr = [
            'status' => $status,
            'message' => $message
        ];
        if (count($data) > 0 || $forceEmpty) {
            $arr['data'] = $data;
        }

        return $rsp->withJson($arr, 200, JSON_NUMERIC_CHECK);
    }

    public static function redirect($rsp, $url, $status='', $message='')
    {
        if (!empty($status) && !empty($message))
        {
            $session = Session::getInstance();
            $session->status = $status;
            $session->message = $message;
        }

        return $rsp->withRedirect($url);
    }

    public static function now($useTime=true)
    {
        return $useTime ? self::datetime(time()) : self::date(time());
    }
    public static function datetime($time)
    {
        return date('Y-m-d H:i:s', $time);
    }
    public static function date($time)
    {
        return date('Y-m-d', $time);
    }


    /**
     * Change value of $field in $arr from Int to Bool
     * Change 1 to TRUE other than that FALSE
     * 
     * @param array $arr the array
     * @param string $field field to change the value
     * @param bool $useStrict only check 1 as int
     */
    public static function intToBool(array &$arr, $field, $useStrict=true)
    {
        if ($useStrict)
        {
            foreach ($arr as &$a) {
                if (!isset($a[$field])) continue;

                $a[$field] = $a[$field] === 1 ? TRUE : FALSE;
            }
            unset($a);
        }
        else
        {
            foreach ($arr as &$a) {
                if (!isset($a[$field])) continue;

                $a[$field] = $a[$field] == 1 ? TRUE : FALSE;
            }
            unset($a);
        }
    }

    
    /**
     * https://stackoverflow.com/a/31107425
     * Generate a random string, using a cryptographically secure 
     * pseudorandom number generator (random_int)
     * 
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     * 
     * @param int $length      How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     */
    public static function randomString($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[rand(0, $max)];
        }
        return implode('', $pieces);
    }


    /**
     * Change '-' in field to '_'
     * 
     * @param array $data   array params to filter
     * @param array $filter array of excluded field, if any
     * @return array
     */
    public static function filterParams(array $data, array $filter=[''])
    {
        $afv = [];
        foreach ($data as $f => $v) {
            if (!in_array($f, $filter)) {
                $f = str_replace('-', '_', $f);
                $afv[$f] = $v;
            }
        }

        return $afv;
    }


    /**
     * Remove all special characters and spaces (change them to '-')
     * 
     * @param string $str to filter
     * @param int $maxLength max slug length, default 0 (no max length)
     * @return string slug from $str
     */
    public static function slug($str, $maxLength=0) {
        $str = preg_replace('/[^A-Za-z 0-9\-]/', '', $str);
        $str = strtolower($str);
        $str = preg_replace('/ /', '-', $str);
        if ($maxLength > 0) {
            $str = substr($str, 0, $maxLength);
        }

        $str = rtrim($str, '-');
        return $str;
    }


    /**
     * Faster json_encode
     * http://php.net/manual/en/function.json-encode.php#100835
     */
    function json_encode( $data ) {           
        if( is_array($data) || is_object($data) ) {
            $islist = is_array($data) && ( empty($data) || array_keys($data) === range(0,count($data)-1) );
           
            if( $islist ) {
                $json = '[' . implode(',', array_map('self::json_encode', $data) ) . ']';
            } else {
                $items = Array();
                foreach( $data as $key => $value ) {
                    $items[] = self::json_encode("$key") . ':' . self::json_encode($value);
                }
                $json = '{' . implode(',', $items) . '}';
            }
        } elseif( is_string($data) ) {
            # Escape non-printable or Non-ASCII characters.
            # I also put the \\ character first, as suggested in comments on the 'addclashes' page.
            $string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
            $json    = '';
            $len    = strlen($string);
            # Convert UTF-8 to Hexadecimal Codepoints.
            for( $i = 0; $i < $len; $i++ ) {
               
                $char = $string[$i];
                $c1 = ord($char);
               
                # Single byte;
                if( $c1 <128 ) {
                    $json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);
                    continue;
                }
               
                # Double byte
                $c2 = ord($string[++$i]);
                if ( ($c1 & 32) === 0 ) {
                    $json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);
                    continue;
                }
               
                # Triple
                $c3 = ord($string[++$i]);
                if( ($c1 & 16) === 0 ) {
                    $json .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128));
                    continue;
                }
                   
                # Quadruple
                $c4 = ord($string[++$i]);
                if( ($c1 & 8 ) === 0 ) {
                    $u = (($c1 & 15) << 2) + (($c2>>4) & 3) - 1;
               
                    $w1 = (54<<10) + ($u<<6) + (($c2 & 15) << 2) + (($c3>>4) & 3);
                    $w2 = (55<<10) + (($c3 & 15)<<6) + ($c4-128);
                    $json .= sprintf("\\u%04x\\u%04x", $w1, $w2);
                }
            }
        } else {
            # int, floats, bools, null
            $json = strtolower(var_export( $data, true ));
        }
        return $json;
    } 

    /**
     * Again, faster json_encode
     * http://php.net/manual/en/function.json-encode.php#113219
     */
    function json_encode2($val)
    {
        if (is_string($val)) return '"'.addslashes($val).'"';
        if (is_numeric($val)) return $val;
        if ($val === null) return 'null';
        if ($val === true) return 'true';
        if ($val === false) return 'false';

        $assoc = false;
        $i = 0;
        foreach ($val as $k=>$v){
            if ($k !== $i++){
                $assoc = true;
                break;
            }
        }
        $res = array();
        foreach ($val as $k=>$v){
            $v = self::json_encode2($v);
            if ($assoc){
                $k = '"'.addslashes($k).'"';
                $v = $k.':'.$v;
            }
            $res[] = $v;
        }
        $res = implode(',', $res);
        return ($assoc)? '{'.$res.'}' : '['.$res.']';
    }

    public static function getURL() {
        return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}