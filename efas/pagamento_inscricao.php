<?php
// Documentação disponível em: 
// https://dev.pagseguro.uol.com.br/documentacao/pagamentos/pagamento-padrao
ini_set('display_errors', 1);
// URL DE SANDBOX
$url = 'https://ws.pagseguro.uol.com.br/v2/checkout';

$data['email'] = 'aosamapostolo@gmail.com';
$data['token'] = '1a82b6b2-366e-4b0a-928e-1ee0c2cd2c623fcd8cd64eed975a9a8469b97e40075eba49-536c-4f38-8d58-7ea376940b76';
$data['currency'] = 'BRL';

$data['itemId1'] = "1";
$data['itemDescription1'] = "Inscrição Infantil";
$data['itemAmount1'] = '12.50';
$data['itemQuantity1'] = '1';
$data['itemWeight1'] = '0';

$data['itemId2'] = "1";
$data['itemDescription2'] = "Inscrição Adulto";
$data['itemAmount2'] = '25.00';
$data['itemQuantity2'] = '1';
$data['itemWeight2'] = '0';

$data['reference'] = "EFAS2023"; //aqui vai o código que será usado para receber os retornos das notificações
$data['senderName'] = "EDSON GALDINO";
// $data['senderAreaCode'] = "";
// $data['senderPhone'] = "";
$data['senderEmail'] = "edsongaldino@outlook.com";
// $data['shippingType'] = "";
// $data['shippingAddressStreet'] = "";
// $data['shippingAddressNumber'] = "";
// $data['shippingAddressComplement'] = "";
// $data['shippingAddressDistrict'] = "";
// $data['shippingAddressPostalCode'] = "";
// $data['shippingAddressCity'] = "";
// $data['shippingAddressState'] = "";
// $data['shippingAddressCountry'] = "";

$data['redirectURL'] = 'https://secretaria.efas.euripedesbarsanulfo.org.br/pedido-finalizado';

$data = http_build_query($data);

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
$xml= curl_exec($curl);

if($xml == 'Unauthorized'){
  echo "Unauthorized";
  exit();
}

curl_close($curl);

$xml = simplexml_load_string($xml);

var_dump($xml);

if(count($xml->error) > 0){
  echo "XML ERRO";
}

// Utilize sua lógica para atualizar o pedido com o código da transação, para ser atualizado depois
//$db->query("UPDATE pedido SET token = '{$xml->code}' WHERE id = $pedido_id"); 

// Redireciona o comprador para a página de pagamento
header('Location: https://ws.pagseguro.uol.com.br/v2/checkout/payment.html?code='.$xml->code);