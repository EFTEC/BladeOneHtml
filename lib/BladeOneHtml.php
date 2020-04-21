<?php /** @noinspection RequiredAttributes */
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
 * @version  1.1
 * @link     https://github.com/EFTEC/BladeOneHtml
 * @author   Jorge Patricio Castro Castillo <jcastro arroba eftec dot cl>
 */
trait BladeOneHtml
{
    protected $htmlItem = []; // indicates the type of the current tag. such as select/selectgroup/etc.
    protected $htmlCurrentId = []; //indicates the id of the current tag.
    protected $insideForm = false;


    public $pattern = [
        'input' => '{{pre}}<input{{inner}} >{{between}}</input>{{post}}',
        'input_empty' => '{{pre}}<input{{inner}} />{{post}}',
        'file' => '{{pre}}<input type="file"{{inner}} >{{between}}</input>{{post}}',
        'select' => '{{pre}}<select{{inner}} >{{between}}{{post}}',
        'select_item' => '<option{{inner}} >{{between}}</option>',
        'select_end' => '</select>',
        'checkbox' => '{{pre}}<input type="checkbox" {{inner}} >{{between}}</input>{{post}}',
        'radio' => '{{pre}}<input type="radio" {{inner}} >{{between}}</input>{{post}}',
        'textarea' => '{{pre}}<textarea {{inner}} >{{between}}</textarea>{{post}}',
        'button' => '{{pre}}<button{{inner}} >{{between}}</button>{{post}}',
        'link' => '{{pre}}<a{{inner}} >{{between}}</a>{{post}}',
        'checkboxes' => '{{pre}}<div{{inner}} >{{between}}{{post}}',
        'checkboxes_item' => '<input type="checkbox"{{inner}} >{{between}}</input>{{post}}',
        'checkboxes_end' => '</div>',
        'radios' => '{{pre}}<div{{inner}} >{{between}}{{post}}',
        'radios_item' => '<input type="radio"{{inner}} >{{between}}</input>{{post}}',
        'radios_end' => '</div>',
        'ul' => '{{pre}}<ul{{inner}} >{{between}}{{post}}',
        'ul_item' => '<li{{inner}} >{{between}}</li>{{post}}',
        'ul_end' => '</ul>',
        'ol' => '{{pre}}<ol{{inner}} >{{between}}{{post}}',
        'ol_item' => '<li{{inner}} >{{between}}</li>{{post}}',
        'ol_end' => '</ol>',
        'optgroup' => '{{pre}}<optgroup{{inner}} >{{between}}{{post}}',
        'optgroup_end' => '</optgroup>',
        'table' => '{{pre}}<table{{inner}} >{{between}}{{post}}',
        'table_item' => '<tr {{inner}} >{{between}}</tr>{{post}}',
        'table_end' => '</table>',
        'tablebody' => '<tbody {{inner}} >{{between}}{{post}}',
        'tablebody_end' => '</tbody>',
        'tablehead' => '<thead><tr {{inner}} >{{between}}{{post}}',
        'tablehead_end' => '</tr></thead>',
        'tablefooter' => '<tfoot><tr {{inner}} >{{between}}{{post}}',
        'tablefooter_end' => '</tr></tfoot>',
        'tablerows' => '<tr {{inner}} >{{between}}{{post}}',
        'tablerows_end' => '</tr>',
        'form' => '{{pre}}<form {{inner}} >{{between}}{{post}}',
        'form_end' => '</form>',
        'cell' => '<td {{inner}} >{{between}}</td>{{post}}',
        'head' => '<th {{inner}} >{{between}}</th>{{post}}',
        'label' => '<label {{inner}} >{{between}}</label>{{post}}',
        'image' => '<img {{inner}} >{{between}}</img>{{post}}'
    ];
    /** @var string[] The class is added to the current element */
    public $defaultClass = [];

    public function useBootstrap4() {
        $bs4 = [
            'button' => 'btn',
            'input' => 'form-control',
            'checkbox_item' => 'form-check-input',
            'select' => 'form-control',
            'file' => 'form-control-file',
            'range' => 'form-control-range',
            'radio' => 'form-check-input',
            'radio_item' => 'form-check-input',
            'ul' => 'list-group',
            'ul_item' => 'list-group-item',
            'ol' => 'list-group',
            'ol_item' => 'list-group-item',
            'table' => 'table'
        ];
        $this->defaultClass = array_merge($this->defaultClass, $bs4);
        $this->pattern['checkbox'] = "<div class=\"custom-control custom-checkbox\">
            <input type=\"checkbox\" class=\"custom-control-input\" {{inner}}>
            <label class=\"custom-control-label\" for={{id}} >{{between}}</label>
            </div>{{post}}";
        $this->pattern['radio'] = "<div class=\"custom-control custom-radio\">
            <input type=\"radio\" class=\"custom-control-input\" {{inner}}>
            <label class=\"custom-control-label\" for={{id}} >{{between}}</label>
            </div>{{post}}";

        $this->pattern['checkboxes_item'] = $this->pattern['checkbox'];
        $this->pattern['radios_item'] = $this->pattern['radio'];
    }

    protected function compileInput($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'input', $result);
    }

    protected function render($args, $pattern, $result) {
        if (isset($this->defaultClass[$pattern])) {
            $args['class'] = '"' . $this->stripQuotes(@$args['class']) . ' ' . $this->defaultClass[$pattern] . '"';
        }
        $this->processArgs($args, $pattern, $result);
        $txt = ($result[1] === '' && isset($this->pattern[$pattern . '_empty'])) ? $this->pattern[$pattern . '_empty']
            : $this->pattern[$pattern];
        $result[4] = $this->wrapPHP(@$args['id']);

        return str_replace(['{{inner}}', '{{between}}', '{{pre}}', '{{post}}', '{{id}}'], $result, $txt);
    }

    protected function processArgs($args, $origin, &$result) {
        if (isset($args['idname'])) {
            $args['id'] = $args['idname'];
            $args['name'] = $args['idname'];
        }
        if (isset($args['between'])) {
            $result[1] .= $this->wrapPHP($this->stripQuotes($args['between']), '');
            unset($args['between']);
        }
        if (isset($args['pre'])) {
            $result[2] .= $this->wrapPHP($this->stripQuotes($args['pre']), '');
            unset($args['pre']);
        }
        if (isset($args['post'])) {
            $result[3] .= $this->wrapPHP($this->stripQuotes($args['post']), '', false);
            unset($args['post']);
        }
        if (isset($args['text'])) {
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
            }
        }
    }


    //<editor-fold desc="compile function">
    protected function compileSelect($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        \array_push($this->htmlItem, [
            'type' => 'select',
            'value' => @$args['value'],
            'values' => @$args['values'],
            'alias' => @$args['alias'],
            'id' => @$args['id'],
            'name' => null,
            'idname' => null
        ]);

        $result = ['', '', '', '']; // inner, between, pre, post
        unset($args['values']);
        unset($args['alias']);

        return $this->render($args, 'select', $result);
    }

    protected function compileEndSelect() {
        $r = @\array_pop($this->htmlItem);
        if (\is_null($r)) {
            $this->showError("@endselect", "Missing @select or so many @endselect", true);
        }
        return $this->pattern[$r['type'] . '_end'];
    }

    protected function compileItem($expression) {
        // we add a new attribute with the type of the current open tag
        $r = \end($this->htmlItem);
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        if (!isset($args['id'])) {
            $args['id'] = $r['id'];
        }
        if (!isset($args['name'])) {
            $args['name'] = $r['name'];
        }
        if (!isset($args['idname'])) {
            $args['idname'] = $r['idname'];
        }

        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, $r['type'] . '_item', $result);
    }

    protected function compileItems($expression) {
        // we add a new attribute with the type of the current open tag
        $parent = \end($this->htmlItem);

        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        if (!isset($args['id']) && isset($parent['id'])) {
            $args['id'] = @$parent['id'];
        }
        if (!isset($args['name'])) {
            $args['name'] = @$parent['name'];
        }
        if (!isset($args['idname']) && isset($parent['idname'])) {
            $args['idname'] = @$parent['idname'];
        }
        if (!isset($args['alias'])) {
            $args['alias'] = @$parent['alias'];
        }
        if (!isset($args['values'])) {
            $args['values'] = @$parent['values'];
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
        $nameOG = $args['values'] . 'Optgroup';
        $nameKey = $args['values'] . 'Key';
        $html =
            '<?php ' . $nameOG . '=\'\';  foreach(' . $name . ' as ' . $nameKey . '=>' . $args['alias'] . ') {' . "\n";
        if (isset($args['optgroup'])) {
            $html .= "if({$args['optgroup']}!=" . $nameOG . ") {
                echo \"<optgroup label='{$args['optgroup']}'>\";
                $nameOG={$args['optgroup']};
                }";
        }
        $html .= '?>';
        unset($args['values']);
        unset($args['alias']);


        $args['id'] = $this->addInsideQuote(@$args['id'], '_' . $nameKey);
        $html .= $this->render($args, $parent['type'] . '_item', $result);
        $html .= '<?php } // foreach  ?>';
        return $html;
    }



    protected function compileTextArea($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');

        $args['between'] = $this->stripQuotes(@$args['value']);
        unset($args['value']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'textarea', $result);
    }


    protected function compileCheckbox($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        $args['checked'] = (isset($args['checked']) && $this->stripQuotes($args['checked'])) ? 'checked' : '';
        return $this->render($args, 'checkbox', $result);
    }

    protected function compileRadio($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        $args['checked'] = (isset($args['checked']) && $this->stripQuotes($args['checked'])) ? 'checked' : '';
        return $this->render($args, 'radio', $result);
    }

    public function compileButton($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');

        $args['between'] = $this->stripQuotes(@$args['value']);
        unset($args['value']);

        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'button', $result);
    }

    protected function compileLink($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'link', $result);
    }

    protected function compileCheckboxes($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        \array_push($this->htmlItem, [
            'type' => 'checkboxes',
            'value' => @$args['value'],
            'values' => @$args['values'],
            'alias' => @$args['alias'],
            'id' => @$args['id'],
            'name' => @$args['name'],
            'idname' => @$args['idname']
        ]);
        unset($args['values']);
        unset($args['alias']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'checkboxes', $result);
    }

    protected function compileEndCheckboxes() {
        $r = @\array_pop($this->htmlItem);
        if (\is_null($r)) {
            $this->showError("@endcheckboxes", "Missing @checkboxes or so many @checkboxes", true);
        }
        return $this->pattern[$r['type'] . '_end'];
    }

    protected function compileRadios($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        \array_push($this->htmlItem, [
            'type' => 'radios',
            'value' => @$args['value'],
            'values' => @$args['values'],
            'alias' => @$args['alias'],
            'id' => @$args['id'],
            'name' => @$args['name'],
            'idname' => @$args['idname']
        ]);
        unset($args['values']);
        unset($args['alias']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'radios', $result);
    }

    protected function compileEndRadios() {
        $r = @\array_pop($this->htmlItem);
        if (\is_null($r)) {
            $this->showError("@endradios", "Missing @radios or so many @radios", true);
        }
        return $this->pattern[$r['type'] . '_end'];
    }

    protected function compileUl($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        \array_push($this->htmlItem, [
            'type' => 'ul',
            'value' => @$args['value'],
            'values' => @$args['values'],
            'alias' => @$args['alias'],
            'id' => @$args['id'],
            'name' => @$args['name'],
            'idname' => @$args['idname']
        ]);
        unset($args['values']);
        unset($args['alias']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'ul', $result);
    }

    protected function compileEndUl() {
        $r = @\array_pop($this->htmlItem);
        if (\is_null($r)) {
            $this->showError("@endul", "Missing @ul or so many @endul", true);
        }
        return $this->pattern[$r['type'] . '_end'];
    }

    protected function compileOl($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        \array_push($this->htmlItem, [
            'type' => 'ol',
            'value' => @$args['value'],
            'values' => @$args['values'],
            'alias' => @$args['alias'],
            'id' => @$args['id'],
            'name' => @$args['name'],
            'idname' => @$args['idname']
        ]);
        unset($args['values']);
        unset($args['alias']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'ol', $result);
    }

    protected function compileEndOl() {
        $r = @\array_pop($this->htmlItem);
        if (\is_null($r)) {
            $this->showError("@endol", "Missing @ol or so many @endol", true);
        }
        return $this->pattern[$r['type'] . '_end'];
    }

    protected function compileForm($expression) {
        $this->insideForm = true;
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'form', $result);
    }

    protected function compileEndForm() {
        if (!$this->insideForm) {
            $this->showError("@endform", "Missing @form or so many @endform", true);
        }
        return $this->pattern['form_end'];
    }

    protected function compileOptGroup($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'optgroup', $result);
    }

    protected function compileEndOptGroup() {
        return $this->pattern['optgroup_end'];
    }

    protected function compileFile($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        $post = @$args['post'];
        unset($args['post']);
        $html = $this->render($args, 'file', $result);
        $args['type'] = '"hidden"';
        if (isset($args['name'])) {
            $args['name'] = $this->addInsideQuote($args['name'], '_file');
        }
        $args['post'] = $post;
        unset($args['pre']);
        unset($args['between']);
        $html .= $this->render($args, 'input', $result);
        return $html;
    }

    protected function compileTable($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        \array_push($this->htmlItem, [
            'type' => 'table',
            'value' => @$args['values'],
            'id' => null,
            'name' => null,
            'idname' => null,
            'alias' => @$args['alias']
        ]);
        unset($args['values']);
        unset($args['alias']);

        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'table', $result);
    }

    protected function compileEndTable($expression) {
        $r = @\array_pop($this->htmlItem);
        if (\is_null($r)) {
            $this->showError("@endselect", "Missing @select or so many @endselect", true);
        }
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'table_end', $result);
    }

    protected function compileTableBody($expression) {
        $r = \end($this->htmlItem);
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        \array_push($this->htmlItem, ['type' => 'tablebody']);
        $result = ['', '', '', '']; // inner, between, pre, post

        $html = $this->render($args, 'tablebody', $result);
        $html .= '<?php foreach(' . $r['value'] . ' as ' . $r['alias'] . ') { ?>';
        return $html;
    }

    protected function compileEndTableBody($expression) {
        $r = @\array_pop($this->htmlItem);
        if (\is_null($r)) {
            $this->showError("@endtablebody", "Missing @tablebody or so many @endtablebody", true);
        }
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        return ' <?php } ?>' . $this->render($args, 'tablebody_end', $result);
    }

    protected function compileTableHead($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        \array_push($this->htmlItem, ['type' => 'tablehead']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'tablehead', $result);
    }

    protected function compileEndTableHead($expression) {
        $r = @\array_pop($this->htmlItem);
        if (\is_null($r)) {
            $this->showError("@endtablehead", "Missing @tablehead or so many @endtablehead", true);
        }
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'tablehead_end', $result);
    }

    protected function compileTableFooter($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        \array_push($this->htmlItem, ['type' => 'tablefooter']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'tablefooter', $result);
    }

    protected function compileEndTableFooter($expression) {
        $r = @\array_pop($this->htmlItem);
        if (\is_null($r)) {
            $this->showError("@endtablehead", "Missing @tablehead or so many @endtablehead", true);
        }
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'tablefooter_end', $result);
    }

    protected function compileTableRows($expression) {
        $r = \end($this->htmlItem);
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        \array_push($this->htmlItem, ['type' => 'tablerows']);
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'tablerows', $result);
    }

    protected function compileEndTableRows($expression) {
        $r = @\array_pop($this->htmlItem);
        if (\is_null($r)) {
            $this->showError("@endtablerows", "Missing @tablerows or so many @endtablerows", true);
        }
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'tablerows_end', $result);
    }

    protected function compileCell($expression) {
        $r = \end($this->htmlItem);
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post

        if ($r['type'] === 'tablehead') {
            return $this->render($args, 'head', $result);
        } else {
            return $this->render($args, 'cell', $result);
        }
    }

    protected function compileLabel($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'label', $result);
    }

    protected function compileImage($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'image', $result);
    }

    protected function compileHidden($expression) {
        $args = $this->parseArgs($this->stripParentheses($expression), ' ');
        $args['type'] = 'hidden';
        $result = ['', '', '', '']; // inner, between, pre, post
        return $this->render($args, 'input', $result);
    }


    protected function compileTrio($expression) {
        // we add a new attribute with the type of the current open tag
        $r = \end($this->htmlItem);
        $x = \trim($expression);
        $x = "('{$r}'," . \substr($x, 1);
        return $this->phpTag . "echo \$this->trio{$x}; ?>";
    }

    protected function compileTrios($expression) {
        // we add a new attribute with the type of the current open tag
        $r = \end($this->htmlItem);
        $x = \trim($expression);
        $x = "('{$r}'," . \substr($x, 1);
        return $this->phpTag . "echo \$this->trios{$x}; ?>";
    }


    //</editor-fold>
}
