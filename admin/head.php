<?php
list($sub_menu, $title) = $glam->getLocation();
$g5['title'] = $title;


function buildAttributes(array $attributes)
{
    $result = [];
    foreach ($attributes as $name => $value) {
        if ($value !== null) {
            $result[] = $name . '="' . $value . '"';
        }
    }
    $result = implode(' ', $result);

    return $result;
}

function formOpen(string $action = './post.php', $method = 'post')
{
    return <<<HTML
	<form action="{$action}" method="{$method}">
		<div class="tbl_head01 tbl_wrap">
HTML;
}

const formClose = '</div></form>';

const buttonsOpen = '<div class="btn_fixed_top btn_confirm">';
const buttonsClose = '</div>';

const descOpen = '<div class="local_desc02 local_desc">';
const descClose = '</div>';

function formSubmitOnly($value = '확인', $accessKey = 's')
{
    $submit = buttonSubmit($value, $accessKey);
    $div = buttonsOpen;

    return <<<HTML
		{$div}{$submit}</div>
HTML;
}

function buttonSubmit($value = '확인', $accessKey = 's')
{
    $value .= '(alt + ' . $accessKey . ')';

    return <<<HTML
		<input type="submit" value="{$value}" class="btn btn_submit" accesskey="{$accessKey}">
HTML;
}

function buttonLink(string $href, string $value)
{
    return <<<HTML
	<a href="{$href}" class="btn btn_02">{$value}</a>
HTML;

}

function h2(string $title)
{
    return <<<HTML
		<h2 class="h2_frm">{$title}</h2>
HTML;
}

const tableClose = <<<HTML
	</table>
</div>
HTML;

function tableOpen(array $heads = null)
{
    $html = [];
    $html[] = <<<HTML
<div class="tbl_frm01 tbl_wrap">
        <table>
HTML;
    if ($heads) {
        $html[] = '<thead>';
        $html[] = '<tr>';
        foreach ($heads as $head => $className) {
            if (\is_numeric($head)) {
                $html[] = '<th>' . $className . '</th>';
            } else {
                $html[] = '<th class="cell-' . $className . '">' . $head . '</th>';
            }
        }
        $html[] = '</tr>';
        $html[] = '</thead>';
    }

    return \implode(\PHP_EOL, $html);
}

function formTable(string $h2, array $inputs)
{
    $h2 = h2($h2);
    $inputs = trInputs($inputs);

    $tableOpen = tableOpen();
    $tableClose = tableClose;

    return <<<HTML
        {$h2}
        {$tableOpen}
            <tbody>
                {$inputs}    
            </tbody>    
        {$tableClose}
HTML;

}

function input(string $name, $value = '', array $attributes = [])
{
    if ($value === null) {
        global ${$name};
        $value = ${$name};
    }

    $attributes += [
        'type' => 'text',
        'name' => $name,
        'value' => $value,
        'class' => 'frm_input frm_input_full'
    ];


    $required = $attributes['required'] ?? false;
    if ($required) {
        $attributes['class'] .= ' required';
    }

    $attributes = buildAttributes($attributes);

    return <<<HTML
		<input {$attributes}>
HTML;
}

function select(string $name, array $options, $value = null)
{
    $html = [];
    $html[] = '<select name="' . $name . '">';
    foreach ($options as $value => $text) {
        $html[] = '<option value="' . $value . '">' . $text . '</option>';
    }
    $html[] = '</select>';

    $html = implode(PHP_EOL, $html);

    return $html;
}

function tdInput(string $name, $value = '', array $attributes = [])
{
    $input = input($name, $value, $attributes);
    $class = str_replace(['[', ']'], ['', ''], $name);
    return <<<HTML
	<td class="cell-${class}">
		{$input}
	</td>
HTML;
}

function tdText(string $name, $value = null, array $attributes = [])
{
    return tdInput($name, $value, $attributes);
}

function tdNumber(string $name, $value = null, array $attributes = [])
{
    return tdInput($name, $value, $attributes + ['type' => 'number']);
}

function trInput(string $name, string $label, $value = null, array $attributes = [])
{
    if (!$label) {
        $label = $name;
    }
    $tdInput = tdInput($name, $value, $attributes);

    return <<<HTML
		<tr>
			<th scope="row" class="cell-label">
				<label for="{$name}">{$label}</label>
			</th>
			{$tdInput}
        </tr>
HTML;
}

function trInputs(array $inputs)
{
    $html = [];
    foreach ($inputs as $input) {
        $html[] = trInput(...$input);
    }

    return implode(PHP_EOL, $html);
}

function checked($value)
{
    return $value ?
        'checked' :
        '';
}

function variables(array $variables)
{
    $html = [
        '<dl class="variables">'
    ];
    foreach ($variables as $key => $value) {
        $html[] = '<dt> $' . $key . '</dt>';
        $html[] = '<dd>' . $value . '</dd>';
    }
    $html[] = '</dl>';

    return implode(PHP_EOL, $html);
}

const CONTROL_REQUIRED = ['required' => true];

require __DIR__ . '../../../../adm/admin.head.php';