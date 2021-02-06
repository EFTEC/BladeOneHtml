<?php
/** @noinspection UnknownInspectionInspection */

/** @noinspection RequiredAttributes */
/** @noinspection HtmlRequiredAltAttribute */
/** @noinspection HtmlUnknownAttribute */
/** @noinspection PhpFullyQualifiedNameUsageInspection */

/** @noinspection PhpUnused */

namespace eftec\bladeonehtml;

/**
 * trait BladeOneHtml
 * Copyright (c) 2020 Jorge Patricio Castro Castillo MIT License. Don't delete this comment, its part of the license.
 * It adds the next tags
 * <code>
 * @form()
 * @input(type="text" name="myform" value=$myvalue)
 * @button(type="submit" value="Send")
 * @endform()
 *
 * </code>
 *
 * @package  BladeOneHtml
 * @version  1.7.1
 * @link     https://github.com/EFTEC/BladeOneHtml
 * @author   Jorge Patricio Castro Castillo <jcastro arroba eftec dot cl>
 */
trait BladeOneHtml
{
    /** @var string=['vanilla','bootstrap3','bootstrap4','material'][$i] It sets the current style  */
    public $style='vanilla';
    /** @var string[] It stores the list of patterns used by the code */
    public $pattern
        = [
            'input'           => '{{pre}}<input{{inner}} >{{between}}</input>{{post}}',
            'input_empty'     => '{{pre}}<input{{inner}} />{{post}}',
            'file'            => '{{pre}}<input type="file"{{inner}} >{{between}}</input>{{post}}',
            'select'          => '{{pre}}<select{{inner}} >{{between}}{{post}}',
            'select_item'     => '<option{{inner}} >{{between}}</option>',
            'select_end'      => '</select>',
            'checkbox'        => '{{pre}}<input type="checkbox" {{inner}} >{{between}}</input>{{post}}',
            'radio'           => '{{pre}}<input type="radio" {{inner}} >{{between}}</input>{{post}}',
            'textarea'        => '{{pre}}<textarea {{inner}} >{{between}}</textarea>{{post}}',
            'button'          => '{{pre}}<button{{inner}} >{{between}}</button>{{post}}',
            'link'            => '{{pre}}<a{{inner}} >{{between}}</a>{{post}}',
            'checkboxes'      => '{{pre}}<div{{inner}} >{{between}}{{post}}',
            'checkboxes_item' => '<input type="checkbox"{{inner}} >{{between}}</input>{{post}}',
            'checkboxes_end'  => '</div>',
            'radios'          => '{{pre}}<div{{inner}} >{{between}}{{post}}',
            'radios_item'     => '<input type="radio"{{inner}} >{{between}}</input>{{post}}',
            'radios_end'      => '</div>',
            'ul'              => '{{pre}}<ul{{inner}} >{{between}}{{post}}',
            'ul_item'         => '<li{{inner}} >{{between}}</li>{{post}}',
            'ul_end'          => '</ul>',
            'ol'              => '{{pre}}<ol{{inner}} >{{between}}{{post}}',
            'ol_item'         => '<li{{inner}} >{{between}}</li>{{post}}',
            'ol_end'          => '</ol>',
            'optgroup'        => '{{pre}}<optgroup{{inner}} >{{between}}{{post}}',
            'optgroup_end'    => '</optgroup>',
            'table'           => '{{pre}}<table{{inner}} >{{between}}{{post}}',
            'table_item'      => '<tr {{inner}} >{{between}}</tr>{{post}}',
            'table_end'       => '</table>',
            'tablebody'       => '<tbody {{inner}} >{{between}}{{post}}',
            'tablebody_end'   => '</tbody>',
            'tablehead'       => '<thead><tr {{inner}} >{{between}}{{post}}',
            'tablehead_end'   => '</tr></thead>',
            'tablefooter'     => '<tfoot><tr {{inner}} >{{between}}{{post}}',
            'tablefooter_end' => '</tr></tfoot>',
            'tablerows'       => '<tr {{inner}} >{{between}}{{post}}',
            'tablerows_end'   => '</tr>',
            'form'            => '{{pre}}<form {{inner}} >{{between}}{{post}}',
            'form_end'        => '</form>',
            'cell'            => '<td {{inner}} >{{between}}</td>{{post}}',
            'head'            => '<th {{inner}} >{{between}}</th>{{post}}',
            'label'           => '{{pre}}<label {{inner}} >{{between}}</label>{{post}}',
            'image'           => '{{pre}}<img {{inner}} >{{between}}</img>{{post}}',
            'alert'           => '{{pre}}<div {{inner}}>{{between}}</div>{{post}}',
            'pagination'      => '{{pre}}{{between}}{{post}}'
        ];
    /** @var string[] The class is added to the current element */
    public $defaultClass = [];
    /** @var array It adds a custom adds that it could be used together with $this->pattern */
    public $customAttr = [];
    public $counterId=0;
    protected $htmlCss = []; // indicates the type of the current tag. such as select/selectgroup/etc.
    protected $htmlJs = []; //indicates the id of the current tag.
    protected $htmlJsCode = [];
    protected $htmlItem = [];
    protected $htmlCurrentId = [];
    protected $insideForm = false;

    private $translationControl=['pagination'=>['first'=>'First','prev'=>'Previous','next'=>'Next','last'=>'Last']];

    /**
     * It is the automatic constructor. It is loaded by BladeOne.
     */
    public function BladeOneHtml()
    {
        //echo 'loading this';
    }

    //<editor-fold desc="definitions function">
    
    public function useBootstrap3($useCDN = false)
    {
        // Amazing but it still highly used and it works fine.
        $this->style='bootstrap3';
        $bs3 = [
            'button'   => 'btn',
            'input'    => 'form-control',
            'textarea' => 'form-control',
            'select'   => 'form-control',
            'file'     => 'form-control-file',
            'range'    => 'form-control-range',
            'ul'       => 'list-group',
            'ul_item'  => 'list-group-item',
            'ol'       => 'list-group',
            'ol_item'  => 'list-group-item',
            'table'    => 'table',
            'alert'    => 'alert'
        ];
        $this->defaultClass = array_merge($this->defaultClass, $bs3);
        if ($useCDN) {
            $this->addCss('<link rel="stylesheet" '
                . 'href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" '
                . 'integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" '
                . 'crossorigin="anonymous">', 'bootstrap');
            $this->addJs('<script src="https://code.jquery.com/jquery-3.5.0.min.js" '
                . 'integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" '
                . 'crossorigin="anonymous"></script>', 'jquery');
            $this->addJs('<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" '
                . 'integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" '
                . 'crossorigin="anonymous"></script>', 'bootstrap');
        }
    }
    
    public function useBootstrap4($useCDN = false)
    {
        $this->style='bootstrap4';
        $bs4 = [
            'button'        => 'btn',
            'input'         => 'form-control',
            'textarea'      => 'form-control',
            'checkbox_item' => 'form-check-input',
            'select'        => 'form-control',
            'file'          => 'form-control-file',
            'range'         => 'form-control-range',
            'radio'         => 'form-check-input',
            'radio_item'    => 'form-check-input',
            'ul'            => 'list-group',
            'ul_item'       => 'list-group-item',
            'ol'            => 'list-group',
            'ol_item'       => 'list-group-item',
            'table'         => 'table',
            'alert'         => 'alert'
        ];
        $this->defaultClass = array_merge($this->defaultClass, $bs4);
        $this->pattern['checkbox'] = '<!--suppress XmlInvalidId -->
<div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" {{inner}}>
            <label class="custom-control-label" for={{id}} >{{between}}</label>
            </div>{{post}}';
        $this->pattern['radio'] = '<!--suppress XmlInvalidId -->
<div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" {{inner}}>
            <label class="custom-control-label" for={{id}} >{{between}}</label>
            </div>{{post}}';

        $this->pattern['checkboxes_item'] = $this->pattern['checkbox'];
        $this->pattern['radios_item'] = $this->pattern['radio'];

        if ($useCDN) {
            $this->addCss('<link rel="stylesheet" 
                    href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" 
                    integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" 
                    crossorigin="anonymous">', 'bootstrap');
            $this->addJs('<script
                  src="https://code.jquery.com/jquery-3.5.1.min.js"
                  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
                  crossorigin="anonymous"></script>', 'jquery');
            $this->addJs('<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" 
                    integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" 
                    crossorigin="anonymous"></script>', 'popper');
            $this->addJs('<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" 
                        integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" 
                        crossorigin="anonymous"></script>', 'bootstrap');
        }
    }
    
    //</editor-fold>

    /**
     * Used for pagination
     * @param $newArg
     *
     * @return string
     */
    public function addArgUrl($newArg) {
        $get=array_merge($_GET,$newArg);
        return $this->getCurrentUrl(true).'?'.http_build_query($get);
    }   
    


    /**
     * @return array
     */
    public function getTranslationControl()
    {
        return $this->translationControl;
    }

    /**
     * It merge a new translation<br>
     * Currently, only the tag <b>@ pagination</b> supports translation.<br>
     * <b>Example:</b><br>
     * <pre>
     * $this->setTranslation(['pagination'=>['first'=>'First','prev'=>'Previous','next'=>'Next','last'=>'Last']]);
     * </pre>
     * 
     * @param array $translationControl
     *
     * @return $this
     */
    public function setTranslationControl($translationControl)
    {
        foreach($translationControl as $k=>$v) {
            $this->translationControl[$k]=$v; // add or replace    
        }         
        return $this;
    }
 
    
    /**
     * It adds a css to the css box. It could be added the link tag, the full url or the relative url.<br>
     * The name is used to avoid to repeat the same style. If a style exists, then it is not added<br>
     * <b>example:</b><br>
     * <pre>
     * $this->addCss('<link href='...'>','bootstrap'); // <link href='...'>
     * $this->addCss('<link href='...'>','bootstrap'); // it is not added (bootstrap already exists)
     * $this->addCss('https://domain.dom/css.css'); // <link href='https://domain.dom/css.css'>
     * $this->addCss('css/css.css'); // <link href='https://domain.dom/css/css.css'> (it uses $baseurl)
     * </pre>        
     *
     * @param string $css  It could be a url or a link tag.
     * @param string $name if name is empty then it is added. The name avoid to add duplicates
     */
    public function addCss($css, $name = '')
    {
        if (strpos($css, '<link') === false) {
            if(strpos($css,'//')===false) {
                $css='<?php echo $this->baseUrl.\'/'.$css.'\'; ?>';
            }
            $css = '<link rel="stylesheet" href="' . $css . '">';
        }
        if ($name && !isset($this->htmlCss[$name])) {
            $this->htmlCss[$name] = $css;
        } else {
            $this->htmlCss[] = $css;
        }
    }

    /**
     * It adds a js to the js box. It could be added the script tag, the full url or the relative url.<br>
     * The name is used to avoid to repeat the same script. If a script exists, then it is not added<br>
     * <b>example:</b><br>
     * <pre>
     * $this->addJs('<script src='... ','jquery'); // <script src='...'>
     * $this->addJs('<script src='... ','jquery'); // it is not added (jquery already exists)
     * $this->addJs('https://domain.dom/js.js'); // <script src='https://domain.dom/js.js'>
     * $this->addJs('js/js.js'); // <script src='https://domain.dom/js/js.js'> (it uses $baseurl)
     * </pre>
     *
     * @param string $js   It must be a link to a javscript
     * @param string $name if name is empty then it is added. The name avoid to add duplicates
     */
    public function addJs($js, $name = '')
    {
        if (strpos($js, '<script') === false) {
            if(strpos($js,'//')===false) {
                $js='<?php echo $this->baseUrl.\'/'.$js.'\'; ?>';
            }
            $js = '<script type="application/javascript" src="' . $js . '"></script>';
        }
        
        if ($name) {
            if (!isset($this->htmlJs[$name])) {
                $this->htmlJs[$name] = $js;    
            }
        } else {
            $this->htmlJs[] = $js;
        }
    }


    /**
     * It adds a js to js script box.
     *
     * @param string $js   It must be a script (without the tag < script >)
     * @param string $name if name is empty then it is added. The name avoid to add duplicates
     */
    public function addJsCode($js, $name = '')
    {
        if ($name && !isset($this->htmlJsCode[$name])) {
            $this->htmlJsCode[$name] = $js;
        } else {
            $this->htmlJsCode[] = $js;
        }
    }

    /**
     * Its used internally to render an object.
     * 
     * @param array  $args          An associative array with the arguments of the function.
     * @param string $pattern       The name of the pattern. This must be already defined in $this->pattern<br>
     *                        It could also be used to define the default class ($this->defaultClass)
     * @param array  $wrapper       =['inner','between','pre','post','text']
     * @param bool   $escapeBetween if true (default), 'between' is escape. If false, then 'between' is not escaped
     *
     * @return string|string[]
     */
    protected function render($args, $pattern, $wrapper, $escapeBetween = true)
    {
        if (isset($this->defaultClass[$pattern])) {
            $args['class'] = '"' . trim($this->stripQuotes(@$args['class']) . ' ' . $this->defaultClass[$pattern]) . '"';
        }
        $customArgs = [];
        foreach ($this->customAttr as $key => $attr) {
            if (isset($args[$key])) {
                $customArgs[$key] = $this->wrapPHP($this->stripQuotes($args[$key]), '') . '!';
                unset($args[$key]);
            }
        }
        $this->processArgs($args, $wrapper, $escapeBetween);
        $isPatternEmpty = isset($this->pattern[$pattern . '_empty']);
        $txt = ($wrapper[1] === '' && $isPatternEmpty) ? $this->pattern[$pattern . '_empty'] : $this->pattern[$pattern];
        $wrapper[4] = $this->wrapPHP(@$args['id']);
        $wrapper[5] = $this->wrapPHP(@$args['name']);
        $end = str_replace(['{{inner}}', '{{between}}', '{{pre}}', '{{post}}', '{{id}}', '{{name}}'], $wrapper, $txt);

        foreach ($this->customAttr as $key => $attr) {
            $end = str_replace('{{' . $key . '}}', isset($customArgs[$key]) ? $customArgs[$key] : $attr, $end);
        }
        return $end;
    }

    /**
     * It process the arguments ($args) by converting (into PHP code) and returns an array with 5 values ($result).
     *
     * @param array $args          An associative array with the arguments not converted into PHP code.
     * @param array $result        =['inner','between','pre','post','text'] (it is the result array).
     * @param bool  $escapeBetween (default is true), if true, then 'between' is escaped (_e(..)), otherwise it's not.
     */
    protected function processArgs($args, &$result, $escapeBetween = true)
    {
        if (isset($args['idname'])) {
            $args['id'] = $args['idname'];
            $args['name'] = $args['idname'];
        }
        if (array_key_exists('between',$args)) {
            $result[1] .= $this->wrapPHP($this->stripQuotes($args['between']), '', $escapeBetween);
            unset($args['between']);
        }
        if (array_key_exists('pre',$args)) {
            $result[2] .= $this->wrapPHP($this->stripQuotes($args['pre']), '', false);
            unset($args['pre']);
        }
        if (array_key_exists('post',$args)) {
            $result[3] .= $this->wrapPHP($this->stripQuotes($args['post']), '', false);
            unset($args['post']);
        }
        if (array_key_exists('text',$args)) {
            $result[1] .= $this->wrapPHP($this->stripQuotes($args['text']), '');
            unset($args['text']);
        }
        foreach ($args as $key => $arg) {
            if ($arg !== null) {
                if ($key === 'selected' || $key === 'checked') {
                    $result[0] .= ' ' . $this->wrapPHP($arg, '');
                } else {
                    $result[0] .= ' ' . $key . '=' . $this->wrapPHP($arg);
                }
            } else {
                $result[0] .= ' ' . $key;
            }
        }
    }


    //<editor-fold desc="compile function">

    /**
     * This controls only works for type=bootstrap4<br>
     * <b>Example:</b><br>
     * <pre>
     * @pagination(numpages=999 current=50  pagesize=5 urlparam='_page')
     * </pre>
     * @param $expression
     *
     * @return string|string[]
     */
    protected function compilePagination($expression) {
        if($this->style!=='bootstrap4' && $this->style!=='bootstrap3') {
            $this->showError('@pagination', '@pagination: it only works with bootstrap3 or 4 ('.$this->style.'). You must 
            use useBootstrap3() or useBootstrap4()', true);
            return '';
        }
        $args = $this->getArgs($expression);
        if(!isset($args['numpages'], $args['current'])) {
            $this->showError('@pagination', '@pagination: Missing numpages or current arguments', true);
            return '';
        }
        $_urlparam = isset($args['urlparam'])? $args['urlparam'] :  "'_page'"; // if not urlparam the we use _page as default
        //unset($args['urlparam'])
        $_numpages = $args['numpages'];
        unset($args['numpages']);
        $_current=$args['current'];
        unset($args['current']);
        $_pagesize =$args['pagesize'];
        $_pagesize= isset($_pagesize) ? $_pagesize : 5;
        unset($args['pagesize']);
        
        $r='<?php // pagination starts ici *********************************************
        $_half=floor(('.$_pagesize.'-1)/2); $_p0='.$_current.'-$_half; $_p1='.$_current.'+$_half;
        if($_p0<1) { $_p1 +=1-$_p0; $_p0=1; }
        if($_p1>'.$_numpages.') { $_p1='.$_numpages.'; }
        echo \'<ul class="pagination">\';
        $_url=$this->addArgUrl(['.$_urlparam.'=>1]);
        echo \'<li class="page-item"><a class="page-link" href="\'.$_url.\'" tabindex="-1">'
        .$this->translationControl['pagination']['first'].'</a></li>\';
        if('.$_current.' >1) {
            $_url=$this->addArgUrl(['.$_urlparam.'=>'.$_current.'-1]);
            echo \'<li class="page-item"><a class="page-link" href="\'.$_url.\'" tabindex="-1">'
            .$this->translationControl['pagination']['prev'].'</a></li>\';
        } else {
            echo \'<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">'
            .$this->translationControl['pagination']['prev'].'</a></li>\';
        }
        for($_pag=$_p0;$_pag<=$_p1;$_pag++) {
            $_url=$this->addArgUrl(['.$_urlparam.'=>$_pag]);
            if($_pag == '.$_current.') {
                echo \'<li class="page-item active"><span class="page-link">\'.$_pag.\'</span></li>\';
            } else {
                echo \'<li class="page-item"><a class="page-link" href="\'.$_url.\'">\'.$_pag.\'</a></li>\';    
            }                
        } 
        if('.$_current.' <'.$_numpages.') {
            $_url=$this->addArgUrl(['.$_urlparam.'=>'.$_current.'+1]);
            echo \'<li class="page-item"><a class="page-link" href="\'.$_url.\'">'
            .$this->translationControl['pagination']['next'].'</a></li>\';
        } else {
            echo \'<li class="page-item disabled"><a class="page-link" href="#">'
            .$this->translationControl['pagination']['next'].'</a></li>\';
        }
        $_url=$this->addArgUrl(['.$_urlparam.'=>$_p1]);
        echo \'<li class="page-item"><a class="page-link" href="\'.$_url.\'" tabindex="-1">'
            .$this->translationControl['pagination']['last'].'</a></li>\';
        echo \'</ul>\';
        // pagination ends *********************************************
        ?>';
        
        $result = ['', $r, '', '']; // inner, between, pre, post
        return $this->render($args, 'pagination', $result);
    }
    
    protected function compileCssBox()
    {
        return implode("\n", $this->htmlCss);
    }

    protected function compileJsBox()
    {
        return implode("\n", $this->htmlJs);
    }

    protected function compilejsCodeBox($expression)
    {
        $args = $this->getArgs($expression);

        $js = "<script>\n";
        if (isset($args['ready'])) {
            $js .= "document.addEventListener(\"DOMContentLoaded\", function(event) { \n";
        }
        $js .= implode("\n", $this->htmlJsCode);
        if (isset($args['ready'])) {
            $js .= "\n}) // function()\n";
        }
        $js .= "</script>\n";
        return $js;
    }

    protected function compileInput($expression)
    {
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'input', $result);
    }
    
    
    protected function compileSelect($expression)
    {
        $args = $this->getArgs($expression);
        $this->htmlItem[] = [
            'type'   => 'select',
            'value'  => @$args['value'],
            'values' => @$args['values'],
            'alias'  => @$args['alias'],
            'id'     => @$args['id'],
            'name'   => null,
            'idname' => null
        ];

        $result = ['', '', '', '']; // inner, between, pre, post
        unset($args['values'], $args['alias']);

        return $this->render($args, 'select', $result);
    }

    protected function compileEndSelect()
    {
        $parent = @\array_pop($this->htmlItem);
        if ($parent === null) {
            $this->showError('@endselect', 'Missing @select or so many @endselect', true);
        }
        return $this->pattern[$parent['type'] . '_end'];
    }

    protected function compileItem($expression)
    {
        // we add a new attribute with the type of the current open tag
        $parent = \end($this->htmlItem);
        $args = $this->getArgs($expression);
        if (!isset($args['id'])) {
            $args['id'] = $parent['id'];
        }
        if (!isset($args['name'])) {
            $args['name'] = $parent['name'];
        }
        if (!isset($args['idname'])) {
            $this->counterId++;
            if (isset($args['id'])) {
                $args['idname'] =$args['id'];
            } else {
                $args['idname'] = isset($parent['idname'])?$parent['idname']:null; //'_idname'.$this->counterId;
            }

        }
        $checkedname = ($parent['type'] === 'select') ? 'selected' : 'checked';

        $args['checked'] = '{{checked}}'; //<?php if(1==1)?"checked":""; >';

        $result = ['', '', '', '']; // inner, between, pre, post
        $htmlItem= $this->render($args, $parent['type'] . '_item', $result);
        $htmlItem = str_replace('{{checked}}',
            '<?php echo (' . @$args['value'] . "=={$parent['value']})?'$checkedname':''; ?>", $htmlItem);
        return $htmlItem;
    }

    protected function compileItems($expression)
    {
        // we add a new attribute with the type of the current open tag
        $parent = \end($this->htmlItem);

        $args = $this->getArgs($expression);
        if (!isset($args['id']) && isset($parent['id'])) {
            $args['id'] = @$parent['id'];
        }
        if (!isset($args['name'])) {
            $args['name'] = @$parent['name'];
        }
        if (!isset($args['idname']) && isset($parent['idname'])) {
            $args['idname'] = isset($parent['idname'])?$parent['idname']:null;
        }
        if (!isset($args['alias'])) {
            $args['alias'] = @$parent['alias'];
        }
        if (!isset($args['values'])) {
            $args['values'] = @$parent['values'];
        }
        if ($args['value'] === null) {
            $this->showError('@items with missing tag value', '@items' . $expression, true);
        }
        if ($args['values'] === null) {
            $this->showError('@items with missing tag values', '@items' . $expression, true);
        }
        if ($args['alias'] === null) {
            if ($this->isVariablePHP($args['values'])) {
                $args['alias'] = $args['values'] . 'Row';
            } else {
                $this->showError('@items with missing tag alias', '@items' . $expression, true);
            }
        }
        $result = ['', '', '', '']; // inner, between, pre, post

        $name = $args['values'];
        $nameOG = $args['alias'] . 'Optgroup';
        $nameKey = $args['alias'] . 'Key';
        $html
            = '<?php ' . $nameOG . '=\'\';  foreach(' . $name . ' as ' . $nameKey . '=>' . $args['alias'] . ') {'
            . "\n";
        if (isset($args['optgroup'])) {
            $html .= "if({$args['optgroup']}!=" . $nameOG . ") {
                echo \"<optgroup label='{$args['optgroup']}'>\";
                $nameOG={$args['optgroup']};
                }";
        }
        $html .= "?>\n";
        unset($args['values'], $args['alias']);

        $checkedname = ($parent['type'] === 'select') ? 'selected' : 'checked';

        $args['checked'] = '{{checked}}'; //<?php if(1==1)?"checked":""; >';

        $args['id'] = $this->addInsideQuote(@$args['id'], '_' . $nameKey);
        $htmlItem = $this->render($args, $parent['type'] . '_item', $result);
        $htmlItem = str_replace('{{checked}}',
            '<?php echo (' . @$args['value'] . "=={$parent['value']})?'$checkedname':''; ?>", $htmlItem);
        $html .= $htmlItem;
        $html .= "<?php } // foreach  ?>\n";
        return $html;
    }


    protected function compileTextArea($expression)
    {
        $args = $this->getArgs($expression);

        $args['between'] = $this->stripQuotes(@$args['value']);
        unset($args['value']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'textarea', $result);
    }


    protected function compileCheckbox($expression)
    {
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        $args['checked'] = (isset($args['checked']) && $this->stripQuotes($args['checked'])) ? 'checked' : '';
        return $this->render($args, 'checkbox', $result);
    }

    protected function compileRadio($expression)
    {
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        $args['checked'] = (isset($args['checked']) && $this->stripQuotes($args['checked'])) ? 'checked' : '';
        return $this->render($args, 'radio', $result);
    }

    protected function compileButton($expression)
    {
        $args = $this->getArgs($expression);

        $args['between'] = $this->stripQuotes(@$args['text']);
        unset($args['text']);

        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'button', $result);
    }

    protected function compileLink($expression)
    {
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'link', $result);
    }

    protected function compileCheckboxes($expression)
    {
        $args = $this->getArgs($expression);
        $this->htmlItem[] = [
            'type'   => 'checkboxes',
            'value'  => @$args['value'],
            'values' => @$args['values'],
            'alias'  => @$args['alias'],
            'id'     => @$args['id'],
            'name'   => @$args['name'],
            'idname' => @$args['idname']
        ];
        unset($args['values'], $args['alias']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'checkboxes', $result);
    }

    protected function compileEndCheckboxes()
    {
        $parent = @\array_pop($this->htmlItem);
        if ($parent === null) {
            $this->showError('@endcheckboxes', 'Missing @checkboxes or so many @checkboxes', true);
        }
        return $this->pattern[$parent['type'] . '_end'];
    }

    protected function compileRadios($expression)
    {
        $args = $this->getArgs($expression);
        $this->htmlItem[] = [
            'type'   => 'radios',
            'value'  => @$args['value'],
            'values' => @$args['values'],
            'alias'  => @$args['alias'],
            'id'     => @$args['id'],
            'name'   => @$args['name'],
            'idname' => @$args['idname']
        ];
        unset($args['values'], $args['alias']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'radios', $result);
    }

    protected function compileEndRadios()
    {
        $parent = @\array_pop($this->htmlItem);
        if ($parent === null) {
            $this->showError('@endradios', 'Missing @radios or so many @radios', true);
        }
        return $this->pattern[$parent['type'] . '_end'];
    }

    protected function compileUl($expression)
    {
        $args = $this->getArgs($expression);
        $this->htmlItem[] = [
            'type'   => 'ul',
            'value'  => @$args['value'],
            'values' => @$args['values'],
            'alias'  => @$args['alias'],
            'id'     => @$args['id'],
            'name'   => @$args['name'],
            'idname' => @$args['idname']
        ];
        unset($args['values'], $args['alias']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'ul', $result);
    }

    protected function compileEndUl()
    {
        $parent = @\array_pop($this->htmlItem);
        if ($parent === null) {
            $this->showError('@endul', 'Missing @ul or so many @endul', true);
        }
        return $this->pattern[$parent['type'] . '_end'];
    }

    protected function compileOl($expression)
    {
        $args = $this->getArgs($expression);
        $this->htmlItem[] = [
            'type'   => 'ol',
            'value'  => @$args['value'],
            'values' => @$args['values'],
            'alias'  => @$args['alias'],
            'id'     => @$args['id'],
            'name'   => @$args['name'],
            'idname' => @$args['idname']
        ];
        unset($args['values'], $args['alias']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'ol', $result);
    }

    protected function compileEndOl()
    {
        $parent = @\array_pop($this->htmlItem);
        if ($parent === null) {
            $this->showError('@endol', 'Missing @ol or so many @endol', true);
        }
        return $this->pattern[$parent['type'] . '_end'];
    }

    protected function compileForm($expression)
    {
        $this->insideForm = true;
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'form', $result);
    }

    protected function compileEndForm()
    {
        if (!$this->insideForm) {
            $this->showError('@endform', 'Missing @form or so many @endform', true);
        }
        return $this->pattern['form_end'];
    }

    protected function compileOptGroup($expression)
    {
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'optgroup', $result);
    }

    protected function compileEndOptGroup()
    {
        return $this->pattern['optgroup_end'];
    }

    protected function compileFile($expression)
    {
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        $post = @$args['post'];
        unset($args['post']);
        $html = $this->render($args, 'file', $result);
        $args['type'] = '"hidden"';
        if (isset($args['name'])) {
            $args['name'] = $this->addInsideQuote($args['name'], '_file');
        }
        $args['post'] = $post;
        unset($args['pre'], $args['between']);
        $html .= $this->render($args, 'input', $result);
        return $html;
    }

    protected function compileTable($expression)
    {
        $args = $this->getArgs($expression);
        $this->htmlItem[] = [
            'type'   => 'table',
            'value'  => @$args['values'],
            'id'     => null,
            'name'   => null,
            'idname' => null,
            'alias'  => @$args['alias']
        ];
        unset($args['values'], $args['alias']);

        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'table', $result);
    }

    protected function compileEndTable($expression)
    {
        $parent = @\array_pop($this->htmlItem);
        if ($parent === null) {
            $this->showError('@endselect', 'Missing @select or so many @endselect', true);
        }
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'table_end', $result);
    }

    protected function compileTableBody($expression)
    {
        $parent = \end($this->htmlItem);
        $args = $this->getArgs($expression);
        $this->htmlItem[] = ['type' => 'tablebody'];
        $result = ['', '', '', '']; // inner, between, pre, post

        $html = $this->render($args, 'tablebody', $result);
        $html .= '<?php foreach(' . $parent['value'] . ' as ' . $parent['alias'] . ') { ?>';
        return $html;
    }

    protected function compileEndTableBody($expression)
    {
        $parent = @\array_pop($this->htmlItem);
        if ($parent === null) {
            $this->showError('@endtablebody', 'Missing @tablebody or so many @endtablebody', true);
        }
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        return ' <?php } ?>' . $this->render($args, 'tablebody_end', $result);
    }

    protected function compileTableHead($expression)
    {
        $args = $this->getArgs($expression);
        $this->htmlItem[] = ['type' => 'tablehead'];
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'tablehead', $result);
    }

    protected function compileEndTableHead($expression)
    {
        $parent = @\array_pop($this->htmlItem);
        if ($parent === null) {
            $this->showError('@endtablehead', 'Missing @tablehead or so many @endtablehead', true);
        }
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'tablehead_end', $result);
    }

    protected function compileTableFooter($expression)
    {
        $args = $this->getArgs($expression);
        $this->htmlItem[] = ['type' => 'tablefooter'];
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'tablefooter', $result);
    }

    protected function compileEndTableFooter($expression)
    {
        $parent = @\array_pop($this->htmlItem);
        if ($parent === null) {
            $this->showError('@endtablehead', 'Missing @tablehead or so many @endtablehead', true);
        }
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'tablefooter_end', $result);
    }

    protected function compileTableRows($expression)
    {
        \end($this->htmlItem);
        $args = $this->getArgs($expression);
        $this->htmlItem[] = ['type' => 'tablerows'];
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'tablerows', $result);
    }

    protected function compileEndTableRows($expression)
    {
        $parent = @\array_pop($this->htmlItem);
        if ($parent === null) {
            $this->showError('@endtablerows', 'Missing @tablerows or so many @endtablerows', true);
        }
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'tablerows_end', $result);
    }

    protected function compileCell($expression)
    {
        $parent = \end($this->htmlItem);
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post

        if ($parent['type'] === 'tablehead') {
            return $this->render($args, 'head', $result);
        }

        return $this->render($args, 'cell', $result);
    }

    protected function compileLabel($expression)
    {
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'label', $result);
    }

    protected function compileImage($expression)
    {
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'image', $result);
    }

    protected function compileHidden($expression)
    {
        $args = $this->getArgs($expression);
        $args['type'] = 'hidden';
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'input', $result);
    }

    protected function compileAlert($expression)
    {
        $args = $this->getArgs($expression);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'alert', $result);
    }

    protected function compileTrio($expression)
    {
        // we add a new attribute with the type of the current open tag
        $parent = \end($this->htmlItem);
        $x = \trim($expression);
        $x = "('{$parent}'," . \substr($x, 1);
        return $this->phpTag . "echo \$this->trio{$x}; ?>";
    }

    protected function compileTrios($expression)
    {
        // we add a new attribute with the type of the current open tag
        $parent = \end($this->htmlItem);
        $x = \trim($expression);
        $x = "('{$parent}'," . \substr($x, 1);
        return $this->phpTag . "echo \$this->trios{$x}; ?>";
    }


    //</editor-fold>
}
