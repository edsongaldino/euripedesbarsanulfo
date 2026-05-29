<?php
$template_email_realmat.= '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600" rel="stylesheet" type="text/css">
<head>
<title>kreative</title>
<style type="text/css">
div, p, a, li, td {
	-webkit-text-size-adjust:none;
}
.ReadMsgBody {
	width: 100%;
	background-color: #d1d1d1;
}
.ExternalClass {
	width: 100%;
	background-color: #d1d1d1;
	line-height:100%;
}
body {
	width: 100%;
	height: 100%;
	background-color: #d1d1d1;
	margin:0;
	padding:0;
	-webkit-font-smoothing: antialiased;
	-webkit-text-size-adjust:100%;
}
html {
	width: 100%;
}
img {
	-ms-interpolation-mode:bicubic;
}
table[class=full] {
	padding:0 !important;
	border:none !important;
}
table td img[class=imgresponsive] {
	width:100% !important;
	height:auto !important;
	display:block !important;
}
@media only screen and (max-width: 800px) {
body {
 width:auto!important;
}
table[class=full] {
 width:100%!important;
}
table[class=devicewidth] {
 width:100% !important;
 padding-left:20px !important;
 padding-right: 20px!important;
}
table td img.responsiveimg {
 width:100% !important;
 height:auto !important;
 display:block !important;
}
}
@media only screen and (max-width: 640px) {
table[class=devicewidth] {
 width:100% !important;
}
table[class=inner] {
 width:100%!important;
 text-align: center!important;
 clear: both;
}
table td a[class=top-button] {
 width:160px !important;
  font-size:14px !important;
 line-height:37px !important;
}
table td[class=readmore-button] {
 text-align:center !important;
}
table td[class=readmore-button] a {
 float:none !important;
 display:inline-block !important;
}
.hide {
 display:none !important;
}
table td[class=smallfont] {
 border:none !important;
 font-size:26px !important;
}
table td[class=sidespace] {
 width:10px !important;
}
}
 @media only screen and (max-width: 520px) {
}
@media only screen and (max-width: 480px) {

 table {
 border-collapse: collapse;
}
table td[class=template-img] img {
 width:100% !important;
 display:block !important;
}
}
@media only screen and (max-width: 320px) {
}

table tbody tr:nth-child(1) {
    background: #ccc;
}

</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="full">
  <tr>
    <td height="54">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="full">
  <tr>
    <td align="center"><table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="devicewidth">
        <tr>
          <td><table width="100%" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" align="center" class="full" style="border-radius:5px 5px 0 0; background-color:#ffffff;">
              <tr>
                <td height="29">&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="0" cellpadding="0" align="left" class="inner" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                    <tr>
                      <td width="23" class="hide">&nbsp;</td>
                      <td height="75" class="inner" valign="middle"><a href="#"><img class="logo" src="src="https://www.realmat.com.br/images/topo/logo.png" width="200" height="58" alt="Logo"></a></td>
                    </tr>
                  </table>
                  <table width="150" border="0" cellspacing="0" cellpadding="0" align="right" class="inner" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                    <tr>
                      <td height="15">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="center"><a href="#" class="top-button" style="font:bold 13px/37px Arial, Helvetica, sans-serif; color:#ffffff; text-decoration:none; background:#F90; display:block; border-radius:24px; text-transform:uppercase;">ACESSAR SITE</a></td>
                      <td class="hide" width="20">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td style="border-bottom:1px solid #dbdbdb;">&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="full">
  <tr>
    <td align="center"><table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="devicewidth">
        <tr>
          <td><table width="100%" bgcolor="#f1f1f1" border="0" cellspacing="0" cellpadding="0" align="center" class="full">
            <tr>
              <td width="100%" align="center" class="smallfont" style="font:300 27px Open Sans, Arial, Helvetica, sans-serif; color:#252525;">&nbsp;</td>
            </tr>
            <tr>
              <td align="center" class="smallfont" style="font:300 27px Open Sans, Arial, Helvetica, sans-serif; color:#252525;">O cliente <span class="smallfont" style="font:700 27px Open Sans, Arial, Helvetica, sans-serif; color:#16c4a9;">'.$usuario["nome_site_usuario"].'</span> acabou de fazer uma solicitação de orçamento pelo site. Segue abaixo os dados:</td>
            </tr>
            <tr>
              <td align="center" class="smallfont" style="font:700 27px Open Sans, Arial, Helvetica, sans-serif; color:#16c4a9;">&nbsp;</td>
            </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="full">
                  <tr>
                    <td width="23" height="auto" bgcolor="#FFFFFF" class="sidespace">&nbsp;</td>
                    <td width="555" bgcolor="#FFFFFF">
                    <!-- Início do foreach -->

                    <table width="100%" border="0" cellpadding="5" cellspacing="0">
                      <tr class="ExternalClass" style="font:300 14px Open Sans, Arial, Helvetica, sans-serif; color:#FFF;">
                        <th colspan="4" bgcolor="#FFFFFF" scope="col">&nbsp;</th>
                      </tr>
                      <tr class="ExternalClass" style="font:300 14px Open Sans, Arial, Helvetica, sans-serif; color:#FFF;">
                        <th colspan="4" bgcolor="#FFFFFF" scope="col"><img src="https://www.realmat.com.br/sistema/ferramenta/templates_email/images/usuario.png" width="83" height="83" alt="picture" /></th>
                      </tr>
                      <tr class="ExternalClass" style="font:300 14px Open Sans, Arial, Helvetica, sans-serif; color:#FFF;">
                        <th colspan="4" bgcolor="#16C4A9" scope="col"><span style="font:600 18px Open Sans, Arial, Helvetica, sans-serif; color:#FFF;">'.$usuario["nome_site_usuario"].'</span></th>
                      </tr>
                      <tr class="ExternalClass" style="font:300 14px Open Sans, Arial, Helvetica, sans-serif; color:#FFF;">
                        <th colspan="4" bgcolor="#16C4A9" scope="col"><span style="font:600 14px Open Sans, Arial, Helvetica, sans-serif; color:#FFC;">'.$usuario["email_site_usuario"].'</span></th>
                      </tr>
                      <tr class="ExternalClass" style="font:300 14px Open Sans, Arial, Helvetica, sans-serif; color:#FFF;">
                        <th colspan="4" bgcolor="#16C4A9" scope="col"><span style="font:600 16px Open Sans, Arial, Helvetica, sans-serif; color:#FFF;">'.$usuario["telefone_site_usuario"].'</span></th>
                      </tr>
                      <tr class="ExternalClass" style="font:300 14px Open Sans, Arial, Helvetica, sans-serif; color:#FFF;">
                        <th colspan="4" bgcolor="#FFFFFF" scope="col">&nbsp;</th>
                        </tr>
                      <tr class="ExternalClass" style="font:300 14px Open Sans, Arial, Helvetica, sans-serif; color:#FFF;">
                        <th colspan="4" bgcolor="#FF6600" scope="col">ITENS DO PEDIDO</th>
                        </tr>
                      <tr class="ExternalClass" style="font:300 14px Open Sans, Arial, Helvetica, sans-serif; color:#FFF;">
                        <th width="98" bgcolor="#FF9900" scope="col">Código</th>
                        <th width="100" bgcolor="#FF9900" scope="col">&nbsp;</th>
                        <th width="261" bgcolor="#FF9900" scope="col">Nome</th>
                        <th width="114" bgcolor="#FF9900" scope="col">Quantidade</th>
                      </tr>

                      ';
                      
                      foreach($itens AS $item_pedido):
                      $template_email_realmat.='
                      <tr style="font:600 14px Open Sans, Arial, Helvetica, sans-serif; color:#333; border-bottom:1px solid #CCC;">
                        <td align="center" valign="middle">'.$item_pedido["referencia_produto"].'</td>
                        <td align="center" valign="middle"><img src="https://www.realmat.com.br/conteudos/produto/'.$item_pedido["codigo_produto"].'/'.$item_pedido["arquivo_foto"].'" alt="" width="80" height="80"></td>
                        <td align="center" valign="middle">'.$item_pedido["nome_produto"].'</td>
                        <td align="center" valign="middle" style="font:600 18px Open Sans, Arial, Helvetica, sans-serif; text-align:center; color:#FF8000; border-bottom:1px solid #CCC;">'.$item_pedido["quantidade_item_pedido"].'</td>
                      </tr>
                      ';
                      endforeach;

                      $template_email_realmat.='
                  </table>
                    <p>&nbsp;</p></td>
                    <td width="22" bgcolor="#FFFFFF">&nbsp;</td>
                    
                  </tr>
                </table>
                <p>&nbsp;</p></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="full">
  <tr>
    <td align="center"><table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="devicewidth">
        <tr>
          <td><table width="100%" bgcolor="#f1f1f1" border="0" cellspacing="0" cellpadding="0" align="center" class="full">
              <tr>
                <td><table width="100%" bgcolor="#F90" border="0" cellspacing="0" cellpadding="0" align="center" class="full" style=" background-image:url(https://www.realmat.com.br/sistema/ferramenta/templates_email/images/white-bg.gif); background-repeat:repeat-x; background-position:left top;">
                    <tr>
                      <td height="23">&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="center"><img src="https://www.realmat.com.br/sistema/ferramenta/templates_email/images/picture.png" width="83" height="atuo" alt="picture" /></td>
                    </tr>
                    <tr>
                      <td height="25">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                          <td width="23" class="sidespace">&nbsp;</td>
                          <td align="center" style="font:15px/19px Arial, Helvetica, sans-serif; color:#ffffff;">A REALMAT Atacadista é a maior e mais completa distribuidora de artigos de papelaria, escritório e material escolar. Com sede própria na Avenida Quinze de Novembro, no bairro do Porto em Cuiabá-MT, dispomos de ambiente amplo e climatizado, com estacionamento para clientes em compras</td>
                          <td width="23" class="sidespace">&nbsp;</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td height="25">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="full">
  <tr>
    <td align="center"><table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="devicewidth">
        <tr>
          <td><table width="100%" bgcolor="#ffffff" border="0" cellspacing="0" cellpadding="0" align="center" class="full" style="border-radius:0 0 7px 7px;">
              <tr>
                <td height="18">&nbsp;</td>
              </tr>
              <tr>
                <td><table class="inner" align="right" width="340" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align:center;">
                    <tr>
                      <td width="21">&nbsp;</td>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="full">
                                <tr>
                                  <td align="center" style="font:11px Helvetica,  Arial, sans-serif; color:#ffffff;"><a style="color:#000000; text-decoration:none;" href="#"> Ver no Browser</a></td>
                                  <td style="color:#000000;"> | </td>
                                  <td align="center" style="font:11px Helvetica,  Arial, sans-serif; color:#ffffff;"><a style="color:#000000; text-decoration:none;" href="#"> Descadastrar</a></td>
                                  <td style="color:#000000;"> | </td>
                                  <td align="center" style="font:11px Helvetica,  Arial, sans-serif; color:#ffffff;">&nbsp;</td>
                                  <td class="hide" width="40" align="right">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="18">&nbsp;</td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
                  <table class="inner" align="left" width="230" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align:center;">
                    <tr>
                      <td width="21">&nbsp;</td>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr>
                            <td align="center" style="font:11px Helvetica,  Arial, sans-serif; color:#000000;">Realmat Atacadista</td>
                          </tr>
                          <tr>
                            <td height="18">&nbsp;</td>
                          </tr>
                        </table></td>
                      <td width="21">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="20">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>'
;
?>