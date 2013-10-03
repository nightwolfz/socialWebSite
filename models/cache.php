<?php
namespace models;

/*
//How to use
$c = new Cache;
$c->addServer('127.0.0.1', 11211);
*/


/**
 * Extends Memcached class, therefore inheriting all it's methods.
 */
class mcache /*extends \Memcached*/ {
    function cache($key, $time, $func) {
        if (($val = $this->get($key)) === false) {
            $val = $func();
            $this->set($key, $val, strtotime($time) - time());
        }
        return $val;
    }

    function test(){
        $row = array('id' => 53, 'body' => '<html><body>Hello world</body></html>');

        // Here is the original line
        $row['body'] = render($row['body']);

        // And here is a cached version
        $row['body'] = $c->cache("blog_post_".$row['id'], '30 minutes', function () use ($row) {
            return render($row['body']);
        });
    }

}

/**
 * Test case
 */
class cache {

    public static function get($key){
        $key = sha1($key);
        $existing_key = '98c40f822fe71d11308cbef498f0c6c1754b5aaf';
        return false;
    }
}