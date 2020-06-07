<?php /** @noinspection CheckTagEmptyBody */

namespace eftec\tests;

use eftec\bladeone\BladeOne;

use eftec\bladeonehtml\BladeOneHtml;
use PHPUnit\Framework\TestCase;

class myBlade extends  BladeOne {
    use BladeOneHtml;
}


/**
 * Class clientTest
 * @package eftec\tests
 * @copyright Jorge Castro Castillo
 * 2020-05-02
 */
class clientTest extends TestCase
{
	/**
	 * @var myBlade
	 */
	private $myBlade;
	
	function assertEqualsNR($expected,$v) {
	    $expected=str_replace(["\r\n","\n"],["",""],$expected);
        $v=str_replace(["\r\n","\n"],["",""],$v);
        $this->assertEquals($expected,$v);
    }


	function setUp()
	{
		$this->myBlade=new myBlade();
		$this->myBlade->setMode(BladeOne::MODE_DEBUG);

	}
	public function testBox() {
	    $this->myBlade->addJs('<script src="domain.dom/js.js">','alert');
        $this->myBlade->addJs('<script src="domain.dom/js.js">','alert');
        $this->myBlade->addJsCode('alert(2);');
        $this->myBlade->addCss('style123');
        $template='@jsbox
@jscodebox(ready)
@cssbox';
        
        $html='<script src="domain.dom/js.js">
<script>
alert(2);</script>

<link rel="stylesheet" href="style123">';
        $this->assertEqualsNR( $html,$this->myBlade->runString($template));
    }
    public function testBasic1() {
        $this->assertEquals('abc:<input type="text" idname="hi" id="hi" name="hi" />'
            ,$this->myBlade->runString('@input(type="text" idname="hi" pre="abc:")'));
		$this->assertEquals('<input type="text" />'
            ,$this->myBlade->runString('@input(type="text")'));
        $this->assertEquals('<input type="text" abc="123" cde=\'123\' efg hij="" />'
            ,$this->myBlade->runString('@input(type="text" abc="123" cde=\'123\' efg hij="")'));
    }
    public function testCheckbox() {
	    $template='@checkbox(id="idsimple" value="1" checked="1" post="it is a selection")';
	    $html='<input type="checkbox"  id="idsimple" value="1" checked ></input>it is a selection';
        $this->assertEquals($html,$this->myBlade->runString($template));

        $template='@checkboxes(id="checkbox1" value=$selection alias=$country)
@item(id="aa1" value=\'aaa\' text=\'hello world\' post="<br>")
@item(id="aa2" value=\'aaa\' text=\'hello world2\' post="<br>")
@items(values=$countries value=\'id\' text=\'name\' post="<br>")
@endcheckboxes';
        $html='<div id="checkbox1" value="" >
<input type="checkbox" id="aa1" value=\'aaa\' name idname >hello world</input><br>
<input type="checkbox" id="aa2" value=\'aaa\' name idname >hello world2</input><br>

</div>';
        $this->assertEquals($html,$this->myBlade->runString($template));
    }
    public function testRadio() {
        $template='@radio(id="idsimple" value="1" checked="1" post="it is a selection")';
        $html='<input type="radio"  id="idsimple" value="1" checked ></input>it is a selection';
        $this->assertEquals($html,$this->myBlade->runString($template));
        
        $template='@radios(id="radios1" name="aaa" value=$selection  alias=$country)
@item(value=\'aaa\' text=\'hello world\' post="<br>")
@item(value=\'aaa\' text=\'hello world2\' post="<br>")
@items(values=$countries value=\'id\' text=\'name\' post="<br>")
@endradios';
        $html='<div id="radios1" name="aaa" value=""  >
<input type="radio" value=\'aaa\' id="radios1" name="aaa" idname >hello world</input><br>
<input type="radio" value=\'aaa\' id="radios1" name="aaa" idname >hello world2</input><br>

</div>';
        $this->assertEquals($html,$this->myBlade->runString($template));
    }
    public function testMisc() {
        $template='@ul(id="aaa" value=$selection values=$countries alias=$country)
@item(value=\'aaa\' text=\'hello world\')
@items(value=$country->id text=$country->name)
@endul
@image(src="https://via.placeholder.com/350x150")
@label(for="id1" text="hello world:")
@hidden(name="id1" value="hello world$somevar" )
@alert(text="hi there" class="alert-danger" customtag="it is a custom tag")<br>';
        $html='<ul id="aaa" value="" >
<li value=\'aaa\' id="aaa" name idname >hello world</li>

</ul>
<img  src="https://via.placeholder.com/350x150" ></img>
<label  for="id1" >hello world:</label>
<input name="id1" value="hello world" type=hidden />
<div  class="alert-danger" customtag="it is a custom tag">hi there</div><br>';
        $this->assertEquals($html,$this->myBlade->runString($template));
    }
    public function testTextArea() {
        $template='@textarea(id="aaa" value="3333 3333 aaa3333 ")';
        $html='<textarea  id="aaa" >3333 3333 aaa3333 </textarea>';
        $this->assertEquals($html,$this->myBlade->runString($template));
    }
    public function testTable() {
        $template='@table(class="table" values=$countries alias=$country border="1")
@tablehead  
@cell(text="id")
@cell(text="cod")
@cell(text="name")
@endtablehead
@tablebody(id=\'hello world\'  )
@tablerows(style="background-color:azure")
@cell(text=$country->id style="background-color:orange")
@cell(text=$country->cod )
@cell(text=$country->name)
@endtablerows
@endtablebody
@tablefooter
@cell(text="id" colspan="3")
@endtablefooter
@endtable';
        $html='<table class="table" border="1" >
<thead><tr  >  
<th  >id</th>
<th  >cod</th>
<th  >name</th>
</tr></thead>
<tbody  id=\'hello world\'  ></tbody>
<tfoot><tr  >
<td  colspan="3" >id</td>
</tr></tfoot>
</table>';
        $this->assertEquals($html,$this->myBlade->runString($template));	    
    }
    public function testButton() {
        $template='<body>
@form()
@input(type="text" name="myform" value=$myvalue)
@button(type="submit" value="Send")
@link(href="https://www.google.cl" text="context")
@endform()
</body>';
        $html='<body>
<form  >
<input type="text" name="myform" value="" />
<button type="submit" >Send</button>
<a href="https://www.google.cl" >context</a>
</form>
</body>';
        $this->assertEquals($html,$this->myBlade->runString($template));
    }
    
    public function testNewVarSelect() {
        $this->assertEquals('<select id="aaa" value="" >
            <option value=\'aaa\' id="aaa" name idname >hello world</option>
<option value=\'aaa\' id="aaa" name idname >hello world</option>
<option value=\'aaa\' id="aaa" name idname >hello world</option>

</select>
'
            ,$this->myBlade->runString('@select(id="aaa" value=$selection values=$countries alias=$country)
            @item(value=\'aaa\' text=\'hello world\')
@item(value=\'aaa\' text=\'hello world\')
@item(value=\'aaa\' text=\'hello world\')
@items( id="chkx" value=$country->id text=$country->name)
@endselect
'));
    }    
    public function testNewVar3() {
	    $this->myBlade->useBootstrap3();
        $this->myBlade->useBootstrap3(true);
        $this->assertEquals('<input type="text" class="form-control" />'
            ,$this->myBlade->runString('@input(type="text")'));
        $this->assertEquals('<input type="text" abc="123" cde=\'123\' efg hij="" class="form-control" />'
            ,$this->myBlade->runString('@input(type="text" abc="123" cde=\'123\' efg hij="")'));
    }
    public function testNewVar4() {
        $this->myBlade->useBootstrap4();
        $this->myBlade->useBootstrap4(true);
        $this->assertEquals('<input type="text" class="form-control" />'
            ,$this->myBlade->runString('@input(type="text")'));
        $this->assertEquals('<input type="text" abc="123" cde=\'123\' efg hij="" class="form-control" />'
            ,$this->myBlade->runString('@input(type="text" abc="123" cde=\'123\' efg hij="")'));
    }
	
}
