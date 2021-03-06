--TEST--
Object test, __wakeup
--SKIPIF--
--FILE--
<?php
if(!extension_loaded('msgpack')) {
    dl('msgpack.' . PHP_SHLIB_SUFFIX);
}

function test($type, $variable, $test) {
    $serialized = msgpack_serialize($variable);
    $unserialized = msgpack_unserialize($serialized);

    echo $type, PHP_EOL;
    echo bin2hex($serialized), PHP_EOL;
    var_dump($unserialized);
    echo $test || $unserialized->b == 3 ? 'OK' : 'ERROR', PHP_EOL;
}

class Obj {
    var $a;
    var $b;

    function __construct($a, $b) {
        $this->a = $a;
        $this->b = $b;
    }

    function __wakeup() {
        $this->b = $this->a * 3;
    }
}

$o = new Obj(1, 2);


test('object', $o, false);
?>
--EXPECTF--
object
83c0a34f626aa16101a16202
object(Obj)#%d (2) {
  ["a"]=>
  int(1)
  ["b"]=>
  int(3)
}
OK
