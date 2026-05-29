<?php
    function limitar_texto($texto, $limite){
        $texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
        return $texto;
    }
?>