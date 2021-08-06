<?php
// my custom functions :
function RandomLetters($len,$str){
    $chars = array_merge(range('a','z'),range('A','Z'),range('0','9'));
    $max = count($chars);
    for ($i = 0;$i < $len;$i++){
        $rand = mt_rand(0,$max);
        $str .= $chars[$rand];
    }
}
function CookieDelete($Name){
    setcookie($Name,"",time() - 3600,'/');
}
function USession($Name){
    unset($_SESSION[$Name]);
}
function alert($message){
    echo "<script>alert('$message');</script>";
}
function go($lOcAtiOn){
    header("location:".$lOcAtiOn);
}
function redirect($delay,$l0cAti0n){
    header("refresh:".$delay.";url=".$l0cAti0n);
}
