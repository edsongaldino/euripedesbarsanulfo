<?php

	$corpo_mensagem = '
		<html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>'.mb_convert_encoding("Confirmação de Inscrição - EFAS 2021", 'UTF-8').'</title>
        </head>

        <body style="margin:0">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" valign="top">
                <table width="600" border="0" cellspacing="0" cellpadding="0" style="border-collapse: separate;border-spacing: 0;border: 6px solid #D3D3D3; border-radius: 6px;-moz-border-radius: 6px;">
                <tr>
                    <td height="25" style="background:#E1E0E0">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" valign="top" bgcolor="#fff" style="background-color:#fff">
                    <img width="110" src="https://secretaria.efas.euripedesbarsanulfo.org.br/images/logo_autadesouza.jpg" style="display:inline-block; vertical-align:bottom">
                    </td>
                </tr>
                <tr>
                    <td height="60" align="center" valign="top" bgcolor="#e4e4e4" style="background-color:#e4e4e4; font-family:Arial, Helvetica, sans-serif; font-size:19px; color:#3b3b3b;">
                    <img src="https://secretaria.efas.euripedesbarsanulfo.org.br/images/border_red.jpg" style="padding-top:10px">
                    <h3 style="width:430px; text-align:left; font-weight:normal; font-size:18px"> 

                        Ol&aacute;, '.mb_convert_encoding($nome_participante, 'UTF-8').'! <br> <br>

                        Voc&ecirc; fez sua inscri&ccedil;&atilde;o para participar do <strong>ENCONTRO FRATERNO AUTA DE SOUZA</strong> que ser&aacute; realizado entre os dias 30 e 31 de Outubro de 2021 na Associação Wantuil de Freitas Cuiabá-MT<br/>
                        Caso ainda n&atilde;o tenha feito o pagamento, clique no link abaixo:
                        
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top" style="padding:15px; padding-top:5px; background-color:#F2F2F2;" bgcolor="#F2F2F2">
                    <table width="300" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center" valign="middle">
                            <img src="https://secretaria.efas.euripedesbarsanulfo.org.br/images/icon-person.png"> <br>
                            <h3 style="font-family:calibri;margin-top:0; margin-bottom:0; color:#525252; font-size:16px">'.utf8_decode($nome_participante).'</h3>
                            <h3 style="font-family:calibri;margin-top:0; margin-bottom:0; color:#525252; font-size:16px">'.$email_participante.'</h3>
                            <h3 style="font-family:calibri;margin-top:0; margin-bottom:0; color:#525252; font-size:25px; font-weight: bold;">'.$codigo_inscricao_evento.'</h3>
                            </td>
                        </tr>
                    </table>
                    </td>
                </tr>
                <tr bgcolor="#e4e4e4" style="background:e4e4e4">
                    <td align="center" valign="top">
                    <h3 style="margin:20px 0;font-family:calibri;color:#525252; font-size:16px"> Clique no link para acompanhar sua inscri&ccedil;&atilde;o </h3>
                    <a href="'.$link_redirect.'">
                        <img src="https://secretaria.efas.euripedesbarsanulfo.org.br/images/btn-confirmar.jpg" style="padding-bottom:10px">
                    </a>
                    </td>
                </tr>
                <tr bgcolor="#e4e4e4" style="background:e4e4e4">
                    <td align="center" valign="middle">
                    <img src="https://secretaria.efas.euripedesbarsanulfo.org.br/images/border_gray.jpg">
                    </td>
                </tr>
                <tr bgcolor="#e4e4e4" style="background:e4e4e4">
                    <td align="center" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#3b3b3b; padding-top:20px">
                    <h4> </h4>
                    </td>
                </tr>
                </table>
            </td>
            </tr>
            </table>
        </body>
        </html>';
?>