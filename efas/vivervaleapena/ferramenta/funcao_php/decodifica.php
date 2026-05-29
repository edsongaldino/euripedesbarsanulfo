<?php
function decodifica($valor,$int = false) {
	$valor = base64_decode(strrev(base64_decode($valor)));
	$valor = addslashes(trim($valor));

	if($int) {
		$valor = (int) $valor;
	}
		
	return $valor;
}

function js_str($s)
{
    return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}

function js_array($array)
{
    $temp = array_map('js_str', $array);
    return '[' . implode(',', $temp) . ']';
}
