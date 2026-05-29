<?php

function descricao_nivel_acesso($id) 
{
	switch ($id) {
		case '1':
			return 'Administrador';
		break;
		case '2':
			return 'Gestor da construtora';
		break;
		case '3':
			return 'Consultor da construtora';
		break;
		case '4':
			return 'Gestor da imobiliária';
		break;
		case '5':
			return 'Consultor da imobiliária';
		break;
		
		default:
			return '';
		break;
	}
}