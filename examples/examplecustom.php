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

    public function runTimeAlfa1($args=[]) {
        return (new myBlade())->run("custom.component",$args);
    }
}



$blade=new myBlade($views,$compiledFolder);
$blade->setMode(BladeOne::MODE_DEBUG); // for debug, remove in productive.

$myvalue=@$_REQUEST['myform'];

echo $blade->run("custom.web", ['a1'=>123]);
