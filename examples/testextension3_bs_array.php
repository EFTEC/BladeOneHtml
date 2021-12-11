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
$blade->setCompiledExtension('.php');
$blade->setMode(BladeOne::MODE_DEBUG);
$blade->customAttr['customtag']='This attr is missing!';
$blade->useBootstrap4(true);
$blade->pattern['alert']='{{pre}}<div {{inner}}><h1>{{customtag}}</h1>{{between}}</div>{{post}}';

$blade->addJsCode('alert("hello");');
//<editor-fold desc="Example data">
$countries=array();
$countries[]=['id'=>1,'cod'=>'ar','name'=>'Argentina','Continent'=>'America'];;
$countries[]=['id'=>2,'cod'=>'ca','name'=>'Canada','Continent'=>'America'];;
$countries[]=['id'=>3,'cod'=>'us','name'=>'United States','Continent'=>'America'];;
$countries[]=['id'=>4,'cod'=>'jp','name'=>'Japan','Continent'=>'Asia'];
$countries[]=['id'=>5,'cod'=>'kr','name'=>'Korea','Continent'=>'Asia'];

$countrySelected=3;
$multipleSelect=[1,2];

function mifunction($country) {
    if($country['cod']==='us') {
        return 'background-color:orange';
    } else {
        return 'background-color:green';
    }
}



//</editor-fold>
try {
    echo $blade->run("TestExtension.helloextensions3_bs_array"
        , ['somevar'=>'somevar',
            "countries" => $countries,
            'selection'=>3
            , 'countrySelected' => $countrySelected
            , 'multipleSelect' => $multipleSelect]);
} catch (Exception $e) {
    echo "error found ".$e->getMessage()."<br>".$e->getTraceAsString();
}
