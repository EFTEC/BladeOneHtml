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

$blade=new myBlade($views,$compiledFolder);
$blade->useBootstrap3(true);
$blade->setMode(BladeOne::MODE_DEBUG); // for debug, remove in productive.

$myvalue=@$_REQUEST['myform'];

$products=[];
for($i=0;$i<1000;$i++) {
    $products[]='Cocacola #'.$i;
}



$current=isset($_GET['_page']) ? $_GET['_page'] : 1;



$items=array_slice($products,($current-1)*10,10);


echo $blade->run("examplepagination", 
    ['totalpages'=>count($products)
     ,'current'=>$current
     ,'pagesize'=>10
     ,'products'=>$items
    ]);
