<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

function reference(&$foo){
    $foo++;
}

class Test extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    } 

    public function index($username, $password){
        $usernameDB = "foo";
        $passwordDB = "foo";
        //SELECT username, password FROM users WHERE username = "foo" AND password = "foo";
        $sql = 'SELECT username,password FROM USER where username = "'.$username.'"'.' AND password = "'.$password.'"';
        echo $sql;
    }
}
?>