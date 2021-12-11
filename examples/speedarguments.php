<?php
include "../vendor/autoload.php";

use eftec\bladeone\BladeOne;
use eftec\bladeonehtml\BladeOneHtml;
use eftec\tests\myBlade;

class myBlade2 extends  BladeOne {
    use BladeOneHtml;
}


$views = __DIR__ . '/views';
$compiledFolder = __DIR__ . '/compiled';


$myBlade=new myBlade2();
$myBlade->setMode(BladeOne::MODE_DEBUG);

$t1=microtime(true);
for($i=0;$i<10000;$i++) {
    $r = 'a1=1 a2="hello world" a2b="hello=world" a3=\'hello world\' a4=$a5  ';
    $args = $myBlade->getArgs($r);
}
$t2=microtime(true);

// 0.064 seconds for 10'000

var_dump($t2-$t1);