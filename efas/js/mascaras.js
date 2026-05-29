// Input Masks
$('#telefone').mask('(99) 9 9999-9999');
$('#dateMask').mask('99/99/9999');
$('#phoneMask').mask('(99) 9 9999-9999');
$('#cep_endereco').mask("99.999-999");
$('#cpf').mask("999.999.999-99");
$('#cnpj').mask("99.999.999-9999-99");
$('#cpf2').mask("999.999.999-99");
$('#phoneMask2').mask('(99) 9 9999-9999');

$('.cpf').mask("999.999.999-99");
$('.cnpj').mask("99.999.999-9999-99");
$('.data').mask('99/99/9999');
$('.telefone').mask('(99) 9 9999-9999');

$('.moeda').maskMoney({thousands: '.', decimal: ','});
$('.moeda2').maskMoney({thousands: '', decimal: '.'});
