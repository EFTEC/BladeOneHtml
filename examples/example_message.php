<?php
/**
 * Copyright (c) 2016 Jorge Patricio Castro Castillo MIT License.
 */
include "../vendor/autoload.php";

use eftec\bladeone\BladeOne;

use eftec\bladeonehtml\BladeOneHtml;

$views = __DIR__ . '/views';
$compiledFolder = __DIR__ . '/compiled';

class myBlade extends  BladeOne {
    use BladeOneHtml;
}

$m=new \eftec\MessageContainer();
//$m->get('id1')->allError()

$blade=new myBlade($views,$compiledFolder);
$blade->setMode(BladeOne::MODE_DEBUG); // for debug, remove in productive.

$myvalue=@$_REQUEST['myform'];

$blade->message()->addItem('msg1','some error in {{_idlocker}}');
$blade->message()->addItem('msg1','more errors in {{_idlocker}}');
$blade->message()->addItem('msg1','another error in {{_idlocker}}');
$blade->message()->addItem('msg1','warning in {{_idlocker}}','warning');
$blade->message()->addItem('msg1','another warning in {{_idlocker}}','warning');
$blade->message()->addItem('msg1','one info message in {{_idlocker}}','info');

$blade->message()->addItem('msg2','some error in {{_idlocker}}');
$blade->message()->addItem('ms2','more errors in {{_idlocker}}');



echo $blade->run("examplemessage", ['myvalue'=>$myvalue]);
