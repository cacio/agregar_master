<?php
	$dados 	  = urldecode($_REQUEST['dados']);
	$getdados = json_decode($dados);
	
	$tot_base_dentroestado    = ($getdados->vendars[0]->basers + $getdados->vendars2[0]->basers2);
	$tot_base_foraestado      = ($getdados->vendasdifrs[0]->basedifrs + $getdados->vendasdifrs2[0]->basedifrs2);
	$tot_base_foradentro      =  ($tot_base_dentroestado + $tot_base_foraestado);

	$tot_credito_dentroestado = ($getdados->vendars[0]->creditors + $getdados->vendars2[0]->creditors2);
	$tot_credito_foraestado   = ($getdados->vendasdifrs[0]->creditodifrs + $getdados->vendasdifrs2[0]->creditodifrs2);
	$tot_credito_dentrofora   = ($tot_credito_dentroestado + $tot_credito_foraestado);
	$actual_link = "http://$_SERVER[HTTP_HOST]";
	
	function formatar_cpf_cnpj($doc) {
 
        $doc = preg_replace("/[^0-9]/", "", $doc);
        $qtd = strlen($doc);
 
        if($qtd >= 11) {
 
            if($qtd === 11 ) {
 
                $docFormatado = substr($doc, 0, 3) . '.' .
                                substr($doc, 3, 3) . '.' .
                                substr($doc, 6, 3) . '.' .
                                substr($doc, 9, 2);
            } else {
                $docFormatado = substr($doc, 0, 2) . '.' .
                                substr($doc, 2, 3) . '.' .
                                substr($doc, 5, 3) . '/' .
                                substr($doc, 8, 4) . '-' .
                                substr($doc, -2);
            }
 
            return $docFormatado;
 
        } else {
            return 'Documento invalido';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
	.col-8 {
			-webkit-box-flex: 0;
			-webkit-flex: 0 0 66.666667%;
			-ms-flex: 0 0 66.666667%;
			flex: 0 0 66.666667%;
			max-width: 66.666667%;
		}
	.text-right{
		text-align: right!important;
	}	
        tr
	{mso-height-source:auto;}
    col
	{mso-width-source:auto;}
    br
	{mso-data-placement:same-cell;}
.style0
	{mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	white-space:nowrap;
	mso-rotate:0;
	mso-background-source:auto;
	mso-pattern:auto;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	border:none;
	mso-protection:locked visible;
	mso-style-name:Normal;
	mso-style-id:0;}
.style62
	{mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	white-space:nowrap;
	mso-rotate:0;
	mso-background-source:auto;
	mso-pattern:auto;
	color:black;
	font-size:10.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border:none;
	mso-protection:locked visible;
	mso-style-name:"Normal 2";}
td
	{mso-style-parent:style0;
	padding-top:1px;
	padding-right:1px;
	padding-left:1px;
	mso-ignore:padding;
	color:black;
	font-size:11.0pt;
	font-weight:400;
	font-style:normal;
	text-decoration:none;
	font-family:Calibri, sans-serif;
	mso-font-charset:0;
	mso-number-format:General;
	text-align:general;
	vertical-align:bottom;
	border:none;
	mso-background-source:auto;
	mso-pattern:auto;
	mso-protection:locked visible;
	white-space:nowrap;
	mso-rotate:0;}
.xl66
	{mso-style-parent:style62;
	color:black;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;}
.xl67
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";}
.xl68
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	vertical-align:middle;}
.xl69
	{mso-style-parent:style62;
	color:windowtext;
	font-size:6.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:right;}
.xl70
	{mso-style-parent:style62;
	color:windowtext;
	font-size:8.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:none;
	border-right:.5pt solid black;
	border-bottom:.5pt solid black;
	border-left:.5pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl71
	{mso-style-parent:style62;
	color:windowtext;
	font-size:8.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:none;
	border-right:1.0pt solid black;
	border-bottom:.5pt solid black;
	border-left:.5pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl72
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	vertical-align:middle;
	border:.5pt solid black;}
.xl73
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border:.5pt solid black;}
.xl74
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:1.0pt solid black;
	border-bottom:.5pt solid black;
	border-left:.5pt solid black;}
.xl75
	{mso-style-parent:style62;
	color:red;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:left;
	vertical-align:middle;
	border:.5pt solid black;}
.xl76
	{mso-style-parent:style62;
	color:red;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border:.5pt solid black;}
.xl77
	{mso-style-parent:style62;
	color:red;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:1.0pt solid black;
	border-bottom:.5pt solid black;
	border-left:.5pt solid black;}
.xl78
	{mso-style-parent:style62;
	color:red;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:left;
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:.5pt solid black;
	border-bottom:none;
	border-left:.5pt solid black;}
.xl79
	{mso-style-parent:style62;
	color:red;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:.5pt solid black;
	border-bottom:none;
	border-left:.5pt solid black;}
.xl80
	{mso-style-parent:style62;
	color:red;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:1.0pt solid black;
	border-bottom:none;
	border-left:.5pt solid black;}
.xl81
	{mso-style-parent:style62;
	color:windowtext;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:1.0pt solid black;
	border-left:none;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl82
	{mso-style-parent:style62;
	color:windowtext;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:1.0pt solid black;
	border-bottom:1.0pt solid black;
	border-left:none;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl83
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	white-space:normal;}
.xl84
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:1.0pt solid black;
	border-bottom:.5pt solid black;
	border-left:.5pt solid black;}
.xl85
	{mso-style-parent:style62;
	color:red;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	vertical-align:middle;
	border:.5pt solid black;}
.xl86
	{mso-style-parent:style62;
	color:windowtext;
	font-size:8.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border:.5pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl87
	{mso-style-parent:style62;
	color:windowtext;
	font-size:8.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:1.0pt solid black;
	border-bottom:.5pt solid black;
	border-left:.5pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl88
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:.5pt solid black;
	border-bottom:none;
	border-left:.5pt solid black;}
.xl89
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:.5pt solid black;
	border-bottom:none;
	border-left:.5pt solid black;}
.xl90
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:1.0pt solid black;
	border-bottom:none;
	border-left:.5pt solid black;}
.xl91
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:1.0pt solid black;
	border-bottom:.5pt solid black;
	border-left:.5pt solid black;}
.xl92
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	vertical-align:middle;
	white-space:normal;}
.xl93
	{mso-style-parent:style62;
	color:windowtext;
	font-size:8.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:right;}
.xl94
	{mso-style-parent:style62;
	color:windowtext;
	font-size:7.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:right;
	border-top:1.0pt solid black;
	border-right:1.0pt solid black;
	border-bottom:none;
	border-left:1.0pt solid black;
	background:#CCCCCC;
	mso-pattern:#CCCCCC none;}
.xl95
	{mso-style-parent:style62;
	color:windowtext;
	font-size:6.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:left;}
.xl96
	{mso-style-parent:style62;
	color:windowtext;
	font-size:12.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:none;
	border-left:1.0pt solid black;}
.xl97
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:none;
	border-left:none;}
.xl98
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:1.0pt solid black;
	border-right:1.0pt solid black;
	border-bottom:none;
	border-left:none;}
.xl99
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:none;
	border-bottom:1.0pt solid black;
	border-left:1.0pt solid black;}
.xl100
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:none;
	border-bottom:1.0pt solid black;
	border-left:none;}
.xl101
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:1.0pt solid black;
	border-bottom:1.0pt solid black;
	border-left:none;}
.xl102
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:none;
	border-left:none;}
.xl103
	{mso-style-parent:style62;
	color:windowtext;
	font-size:12.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;}
.xl104
	{mso-style-parent:style62;
	color:windowtext;
	font-size:9.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:1.0pt solid black;
	border-left:1.0pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl105
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:1.0pt solid black;
	border-right:.5pt solid black;
	border-bottom:1.0pt solid black;
	border-left:none;}
.xl106
	{mso-style-parent:style62;
	color:windowtext;
	font-size:9.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:1.0pt solid black;
	border-left:.5pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl107
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:1.0pt solid black;
	border-right:1.0pt solid black;
	border-bottom:1.0pt solid black;
	border-left:none;}
.xl108
	{mso-style-parent:style62;
	color:windowtext;
	font-size:7.0pt;
	font-style:italic;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:left;
	vertical-align:top;
	white-space:normal;}
.xl109
	{mso-style-parent:style62;
	color:windowtext;
	font-size:8.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid black;
	border-left:1.0pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl110
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:.5pt solid black;
	border-bottom:.5pt solid black;
	border-left:none;}
.xl111
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:.5pt solid black;
	border-bottom:1px solid black;
	border-left:1.0pt solid black;
	white-space:normal;}
.xl112
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:.5pt solid black;
	border-bottom:none;
	border-left:1.0pt solid black;}
.xl113
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:.5pt solid black;
	border-bottom:.5pt solid black;
	border-left:1.0pt solid black;}
.xl114
	{mso-style-parent:style62;
	color:windowtext;
	font-size:8.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:none;
	border-bottom:.5pt solid black;
	border-left:1.0pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl115
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:.5pt solid black;
	border-right:.5pt solid black;
	border-bottom:.5pt solid black;
	border-left:none;}
.xl116
	{mso-style-parent:style62;
	color:windowtext;
	font-size:9.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:right;
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:none;
	border-left:1.0pt solid black;}
.xl117
	{mso-style-parent:style62;
	color:windowtext;
	font-size:9.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:none;
	border-left:none;}
.xl118
	{mso-style-parent:style62;
	color:windowtext;
	font-size:9.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:1.0pt solid black;
	border-bottom:none;
	border-left:none;}
.xl119
	{mso-style-parent:style62;
	color:windowtext;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:none;
	border-right:none;
	border-bottom:none;
	border-left:1.0pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl120
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:.5pt solid black;
	border-bottom:none;
	border-left:none;}
.xl121
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:.5pt solid black;
	border-bottom:1.0pt solid black;
	border-left:none;}
.xl122
	{mso-style-parent:style62;
	color:windowtext;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:none;
	border-right:.5pt solid black;
	border-bottom:none;
	border-left:.5pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl123
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:.5pt solid black;
	border-bottom:1.0pt solid black;
	border-left:.5pt solid black;}
.xl124
	{mso-style-parent:style62;
	color:windowtext;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:none;
	border-right:1.0pt solid black;
	border-bottom:none;
	border-left:.5pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl125
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:1.0pt solid black;
	border-bottom:1.0pt solid black;
	border-left:.5pt solid black;}
.xl126
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:none;
	border-bottom:none;
	border-left:1.0pt solid black;}
.xl127
	{mso-style-parent:style62;
	color:windowtext;
	font-size:12.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	mso-number-format:"\[$R$ -416\]\#\,\#\#0\.00";
	vertical-align:middle;
	border-top:none;
	border-right:1.0pt solid black;
	border-bottom:none;
	border-left:1.0pt solid black;
	background:#CCCCCC;
	mso-pattern:#CCCCCC none;}
.xl128
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:1.0pt solid black;
	border-bottom:1.0pt solid black;
	border-left:1.0pt solid black;}
.xl129
	{mso-style-parent:style62;
	color:black;
	font-size:12.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:none;
	border-left:none;}
.xl130
	{mso-style-parent:style62;
	color:windowtext;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:1.0pt solid black;
	border-left:1.0pt solid black;}
.xl131
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:1.0pt solid black;
	border-left:none;}
.xl132
	{mso-style-parent:style62;
	color:windowtext;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:1.0pt solid black;
	/*border-left:1.0pt solid black;*/
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl133
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid black;
	border-left:1.0pt solid black;}
.xl134
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:.5pt solid black;
	border-right:none;
	border-bottom:.5pt solid black;
	border-left:1.0pt solid black;}
.xl135
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:.5pt solid black;
	border-right:none;
	border-bottom:1.0pt solid black;
	border-left:1.0pt solid black;}
.xl136
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:.5pt solid black;
	border-right:.5pt solid black;
	border-bottom:1.0pt solid black;
	border-left:none;}
.xl137
	{mso-style-parent:style62;
	color:windowtext;
	font-size:12.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:none;
	border-left:1.0pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl138
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:1.0pt solid black;
	border-right:.5pt solid black;
	border-bottom:none;
	border-left:none;}
.xl139
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:none;
	border-bottom:.5pt solid black;
	border-left:.5pt solid black;}
.xl140
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:.5pt solid black;
	border-right:1.0pt solid black;
	border-bottom:.5pt solid black;
	border-left:none;}
.xl141
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:.5pt solid black;
	border-right:none;
	border-bottom:1.0pt solid black;
	border-left:.5pt solid black;}
.xl142
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:.5pt solid black;
	border-right:1.0pt solid black;
	border-bottom:1.0pt solid black;
	border-left:none;}
.xl143
	{mso-style-parent:style62;
	color:windowtext;
	font-size:12.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:1.0pt solid black;
	border-right:none;
	border-bottom:none;
	border-left:.5pt solid black;
	background:#D9D9D9;
	mso-pattern:#D9D9D9 none;}
.xl144
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:none;
	border-bottom:1.0pt solid black;
	border-left:.5pt solid black;}
.xl145
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-weight:700;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	text-align:center;
	vertical-align:middle;
	border-top:none;
	border-right:none;
	border-bottom:.5pt solid black;
	border-left:.5pt solid black;}
.xl146
	{mso-style-parent:style62;
	color:windowtext;
	font-size:10.0pt;
	font-family:Arial;
	mso-generic-font-family:auto;
	mso-font-charset:0;
	border-top:none;
	border-right:1.0pt solid black;
	border-bottom:.5pt solid black;
	border-left:none;}
    </style>
</head>
<body>
	<?php if($getdados->tipolayout == '2'){ ?>

    <table border="0" cellpadding="0" cellspacing="0" style='border-collapse:collapse;table-layout:fixed;'>

    <col width="85" style='mso-width-source:userset;mso-width-alt:3108;width:64pt'>
    <col width="353" style='mso-width-source:userset;mso-width-alt:12909;width:265pt'>
    <col width="103" style='mso-width-source:userset;mso-width-alt:3766;width:77pt'>
    <col width="156" style='mso-width-source:userset;mso-width-alt:5705;width:117pt'>

    <tr height="21" style='height:15.75pt'>
        <td colspan="3" height="21" class="xl95" width="541" style='height:15.75pt; width:406pt'>Gerado em:<?= $actual_link;?></td>
        <td class="xl69" width="156" style='width:117pt'>Emitido em: <?= date('d/m/Y H:i:s');?></td>
    </tr>

    <tr height="20" style='height:15.0pt'>
    </tr>
        <td colspan="4" rowspan="2" height="41" class="xl96" style='border-right:1.0pt solid black;border-bottom:1.0pt solid black;height:30.75pt'>Apuração Mensal AGREGAR -  Mês/Ano: <?= $getdados->mesano; ?></td>

    <tr height="21" style='height:15.75pt'>
    </tr>

    <tr height="20" style='height:15.0pt'>
        <td colspan="4" height="20" class="xl102" style='height:15.0pt'><?= $getdados->empresa[0]->razao_social; ?> - <?= formatar_cpf_cnpj($getdados->empresa[0]->cnpj); ?></td>
    </tr>

    <tr height="20" style='height:15.0pt'>
        <td colspan="4" rowspan="2" height="41" class="xl103" style='height:30.75pt'>Nº DE ANIMAIS ABATIDOS</td>
    </tr>

    <tr height="21" style='height:15.75pt'>
    </tr>

    <tr height="21" style='height:15.75pt'>
        <td colspan="2" height="21" class="xl104" style='border-right:.5pt solid black;height:15.75pt'>Espécie</td>
        <td colspan="2" class="xl106" style='border-right:1.0pt solid black;border-left:none'>Nº de Cabeças</td>
    </tr>

    <tr height="20" style='height:15.0pt'>
        <td colspan="2" height="20" class="xl133" style='border-right:.5pt solid black;height:15.0pt'>BOVINOS</td>
        <td colspan="2" class="xl145" style='border-right:1.0pt solid black;border-left:none'><?= $getdados->animais[0]->bovinos; ?></td>
    </tr>
    <tr height="20" style='height:15.0pt'>
        <td colspan="2" height="20" class="xl134" style='border-right:.5pt solid black;height:15.0pt'>BUBALINOS</td>
        <td colspan="2" class="xl139" style='border-right:1.0pt solid black;border-left:none'><?= $getdados->animais[0]->bubalinos; ?></td>
    </tr>

    <tr height="21" style='height:15.75pt'>
        <td colspan='2' height="21" class="xl135" style='border-right:.5pt solid black;height:15.75pt'>OVINOS</td>
        <td colspan='2' class="xl141" style='border-right:1.0pt solid black;border-left:none'><?= $getdados->animais[0]->ovinos; ?></td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td colspan="2" rowspan="2" height="41" class="xl137" style='border-right:.5pt solid black;border-bottom:1.0pt solid black;height:30.75pt'>TOTAL DE CABEÇAS ABATIDAS</td>
        <td colspan="2" rowspan="2" class="xl143" style='border-right:1.0pt solid black;border-bottom:1.0pt solid black'><?= ($getdados->animais[0]->bovinos + $getdados->animais[0]->bubalinos + $getdados->animais[0]->ovinos); ?></td>
    </tr>

    <tr height=21 style='height:15.75pt'>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td colspan=4 rowspan=3 height=61 class=xl129 style='border-bottom:1.0pt solid black;height:45.75pt'>APURAÇÃO DE CRÉDITO</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
    </tr>

    <tr height=21 style='height:15.75pt'>
    </tr>

    <tr height=21 style='height:15.75pt'>
        <td colspan=4 height=21 class=xl130 style='border-right:1.0pt solid black;height:15.75pt'>COMPRAS</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td colspan=2 height=20 class=xl109 style='border-right:.5pt solid black; height:15.0pt'>Origem</td>
        <td class=xl70 style='border-left:none'>*Valor da Base</td>
        <td class=xl71 style='border-left:none'>Valor do Crédito</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td rowspan=4 height=81 class=xl111 width=85 style='height:60.75pt;border-top:none;width:64pt'>Crédito 3,6%</td>
        <td class=xl72 style='border-top:none;border-left:none'>NOTAS DE COMPRA DE ANIMAIS (PRODUTORES)</td>
        <td class=xl73 align=right style='border-top:none;border-left:none'>R$ <?= number_format($getdados->basecredito[0]->valorentrada,2,',','.'); ?></td>
        <td class=xl74 style='border-top:none;border-left:none'>&nbsp;</td>
    </tr>

   <!-- <tr height=20 style='height:15.0pt'>
        <td height=20 class=xl75 style='height:15.0pt;border-top:none;border-left:none;padding-left: 23px;'>
            <span style='mso-spacerun:yes'></span>( - ) Valor Glosado Exportação - (${element.nome})
        </td>
        <td class=xl76 align=right style='border-top:none;border-left:none'>
            - R$ ${element.valor}
        </td>
        <td class=xl77 style='border-top:none;border-left:none'>&nbsp;</td>
    </tr>-->

    <tr height=21 style='height:15.75pt'>
        <td height=21 class=xl78 style='height:15.75pt;border-top:none;border-left:	none; padding-left: 23px;'><span style='mso-spacerun:yes'></span>( - ) Devoluções de Compra</td>
        <td class=xl79 align=right style='border-top:none;border-left:none'>-R$	<?= number_format($getdados->basecredito[0]->devolucao,2,',','.'); ?></td>
        <td class=xl80 style='border-top:none;border-left:none'>&nbsp;</td>
    </tr>

    <tr height=21 style='height:15.75pt'>
        <td colspan=<?= count($getdados->exportacao); ?>${data.exportacao.length} height=21 class=xl132 style='height:15.75pt'>( = ) TOTAL DE COMPRAS</td>
        <td class=xl81 align=right>R$ <?= number_format($getdados->basecredito[0]->base,2,',','.'); ?></td>
        <td class=xl82 align=right>R$ <?= number_format($getdados->basecredito[0]->credito,2,',','.'); ?></td>
    </tr>

    <tr height=21 style='height:15.75pt'>
        <td height=21 class=xl83 width=85 style='height:15.75pt;width:64pt'></td>
        <td class=xl66></td>
        <td class=xl67></td>
        <td class=xl67></td>
    </tr>

    <tr height=21 style='height:15.75pt'>
        <td colspan=4 height=21 class=xl130 style='border-right:1.0pt solid black;height:15.75pt'>VENDAS</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td colspan=2 height=20 class=xl109 style='border-right:.5pt solid black;height:15.0pt'>Origem</td>
        <td class=xl70 style='border-left:none'>*Valor da Base</td>
        <td class=xl71 style='border-left:none'>Valor do Crédito</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td rowspan=3 height=60 class=xl111 width=85 style='border-bottom:.5pt solid black;	height:45.0pt;border-top:none;width:64pt'>Crédito 3%</td>
        <td class=xl72 style='border-top:none;border-left:none'>VENDAS DENTRO DO ESTADO (RS)</td>
        <td class=xl73 align=right style='border-top:none;border-left:none'>
				<?php
					if($getdados->tipo == 'xml'){
						$vendarssaida = ($getdados->vendars[0]->saida + $getdados->vendars[0]->devolucao2);
					}else{
						$vendarssaida = $getdados->vendars[0]->saida;
					}
				?>
			R$ <?php number_format($vendarssaida,2,',','.'); ?>
			</td>
        <td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td height=20 class=xl85 style='height:15.0pt;border-top:none;border-left:none;padding-left: 25px;'>( - ) Devoluções de Vendas DENTRO do (RS)</td>
        <td class=xl76 align=right style='border-top:none;border-left:none'>- <?= number_format($getdados->vendars[0]->devolucao,2,',','.'); ?></td>
        <td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td height=20 class=xl72 style='height:15.0pt;border-top:none;border-left:none'><span style='mso-spacerun:yes'></span>( = ) TOTAL FINAL VENDAS (RS) - Crédito (3%)</td>
        <td class=xl73 align=right style='border-top:none;border-left:none'>R$ <?= number_format($getdados->vendars[0]->basers,2,',','.'); ?></td>
        <td class=xl84 align=right style='border-top:none;border-left:none'>R$ <?= number_format($getdados->vendars[0]->creditors,2,',','.'); ?></td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td colspan=2 height=20 class=xl114 style='border-right:.5pt solid black;height:15.0pt'>Origem</td>
        <td class=xl86 style='border-top:none;border-left:none'>*Valor da Base</td>
        <td class=xl87 style='border-top:none;border-left:none'>Valor do Crédito</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td rowspan=3 height=61 class=xl111 width=85 style='border-bottom:.5pt solid black;	height:45.75pt;border-top:none;width:64pt'>Crédito 4%</td>
        <td class=xl72 style='border-top:none;border-left:none'>VENDAS DENTRO DO ESTADO (RS)</td>
        <td class=xl73 align=right style='border-top:none;border-left:none'>
				<?php 
					if($getdados->tipo == 'xml'){
						$vendars2saida = ($getdados->vendars2[0]->saida + $getdados->vendars2[0]->devolucao2);
					}else{
						$vendars2saida = $getdados->vendars2[0]->saida;
					}
				?>
			R$ <?= number_format($vendars2saida,2,',','.'); ?>
		</td>
        <td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td height=20 class=xl85 style='height:15.0pt;border-top:none;border-left:none; padding-left: 25px;'>( - ) Devoluções de Vendas DENTRO do (RS)</td>
        <td class=xl76 align=right style='border-top:none;border-left:none'>-R$	<?= number_format($getdados->vendars2[0]->devolucao,2,',','.'); ?></td>
        <td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
    </tr>

    <tr height=21 style='height:15.75pt'>
        <td height=21 class=xl88 style='height:15.75pt;border-top:none;border-left:none;padding-left: 25px;'>( = ) TOTAL FINAL VENDAS (RS) - Crédito (4%)</td>
        <td class=xl89 align=right style='border-top:none;border-left:none'>R$ <?= number_format($getdados->vendars2[0]->basers2,2,',','.'); ?></td>
        <td class=xl90 align=right style='border-top:none;border-left:none'>R$ <?= number_format($getdados->vendars2[0]->creditors2,2,',','.'); ?></td>
    </tr>
    <tr height=20 style='height:15.0pt'>
        <td colspan=2 rowspan=2 height=41 class=xl116 style='border-bottom:1.0pt solid black;height:30.75pt'>TOTAL DE VENDAS DENTRO DO ESTADO (RS)</td>
        <td rowspan=2 class=xl117 align=right style='border-bottom:1.0pt solid black'>R$ <?= number_format(($getdados->vendars[0]->basers + $getdados->vendars2[0]->basers2),2,',','.'); ?></td>
        <td rowspan=2 class=xl118 align=right style='border-bottom:1.0pt solid black'>R$ <?= number_format(($getdados->vendars[0]->creditors + $getdados->vendars2[0]->creditors2),2,',','.'); ?></td>
    </tr>

    <tr height=21 style='height:15.75pt'>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td colspan=2 height=20 class=xl109 style='border-right:.5pt solid black;height:15.0pt'>Origem</td>
        <td class=xl70 style='border-left:none'>*Valor da Base</td>
        <td class=xl71 style='border-left:none'>Valor do Crédito</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td rowspan=3 height=60 class=xl111 width=85 style='border-bottom:.5pt solid black;	height:45.0pt;border-top:none;width:64pt'>Crédito 3%</td>
        <td class=xl72 style='border-top:none;border-left:none'>VENDAS FORA DO ESTADO (RS)</td>
        <td class=xl73 align=right style='border-top:none;border-left:none'>
				<?php 
					if($getdados->tipo == 'xml'){
						$vendasdifrssaida = ($getdados->vendasdifrs[0]->saida + $getdados->vendasdifrs[0]->devolucao2);
					}else{
						$vendasdifrssaida = $getdados->vendasdifrs[0]->saida;
					}
				?>
			R$ <?= number_format($vendasdifrssaida,2,',','.'); ?>
		</td>
        <td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td height=20 class=xl85 style='height:15.0pt;border-top:none;border-left:none;padding-left: 25px;'><span style='mso-spacerun:yes'></span>( - ) Devoluções de Vendas FORA do (RS)</td>
        <td class=xl76 align=right style='border-top:none;border-left:none'>-R$ <?= number_format($getdados->vendasdifrs[0]->devolucao,2,',','.'); ?></td>
        <td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td height=20 class=xl72 style='height:15.0pt;border-top:none;border-left:none;padding-left: 25px;'><span style='mso-spacerun:yes'></span>( = ) TOTAL FINAL VENDAS FORA (RS) - Crédito 3%</td>
        <td class=xl73 align=right style='border-top:none;border-left:none'>R$ <?= number_format($getdados->vendasdifrs[0]->basedifrs,2,',','.'); ?></td>
        <td class=xl84 align=right style='border-top:none;border-left:none'>R$ <?= number_format($getdados->vendasdifrs[0]->creditodifrs,2,',','.'); ?></td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td colspan=2 height=20 class=xl114 style='border-right:.5pt solid black;height:15.0pt'>Origem</td>
        <td class=xl86 style='border-top:none;border-left:none'>*Valor da Base</td>
        <td class=xl87 style='border-top:none;border-left:none'>Valor do Crédito</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td rowspan=3 height=61 class=xl111 width=85 style='border-bottom:.5pt solid black;height:45.75pt;border-top:none;width:64pt'>Crédito 4%</td>
        <td class=xl72 style='border-top:none;border-left:none'>VENDAS FORA DO ESTADO (RS)</td>
        <td class=xl73 align=right style='border-top:none;border-left:none'>
				<?php 
					if($getdados->tipo == 'xml'){
						$vendasdifrs2saida = ($getdados->vendasdifrs2[0]->saida + $getdados->vendasdifrs2[0]->devolucao2);
					}else{
						$vendasdifrs2saida = $getdados->vendasdifrs2[0]->saida;
					}
				?>
			R$ <?= number_format($vendasdifrs2saida,2,',','.'); ?>
		</td>
        <td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td height=20 class=xl85 style='height:15.0pt;border-top:none;border-left:none;padding-left: 25px;'><span style='mso-spacerun:yes'></span>( - ) Devoluções de Vendas FORA do (RS)</td>
        <td class=xl76 align=right style='border-top:none;border-left:none'>-R$ <?= number_format($getdados->vendasdifrs2[0]->devolucao,2,',','.'); ?></td>
        <td class=xl91 style='border-top:none;border-left:none'>&nbsp;</td>
    </tr>

    <tr height=21 style='height:15.75pt'>
        <td height=21 class=xl88 style='height:15.75pt;border-top:none;border-left:	none;padding-left: 25px;'><span style='mso-spacerun:yes'></span>( = ) TOTAL FINAL VENDAS FORA (RS) - Crédito 4%</td>
        <td class=xl89 align=right style='border-top:none;border-left:none'>R$ <?= number_format($getdados->vendasdifrs2[0]->basedifrs2,2,',','.'); ?></td>
        <td class=xl90 align=right style='border-top:none;border-left:none'>R$ <?= number_format($getdados->vendasdifrs2[0]->creditodifrs2,2,',','.'); ?></td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td colspan=2 rowspan=2 height=41 class=xl116 style='border-bottom:1.0pt solid black;height:30.75pt'>TOTAL DE VENDAS FORA DO ESTADO (RS)</td>
        <td rowspan=2 class=xl117 align=right style='border-bottom:1.0pt solid black'>R$ <?= number_format(($getdados->vendasdifrs[0]->basedifrs + $getdados->vendasdifrs2[0]->basedifrs2),2,',','.'); ?></td>
        <td rowspan=2 class=xl118 align=right style='border-bottom:1.0pt solid black'>R$ <?= number_format(($getdados->vendasdifrs[0]->creditodifrs + $getdados->vendasdifrs2[0]->creditodifrs2),2,',','.'); ?></td>
    </tr>

    <tr height=21 style='height:15.75pt'>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td colspan=2 rowspan=2 height=41 class=xl119 style='border-right:.5pt solid black;border-bottom:1.0pt solid black;height:30.75pt'>( = ) TOTAL DE VENDAS</td>
        <td rowspan=2 class=xl122 align=right style='border-bottom:1.0pt solid black'>R$ <?= number_format($tot_base_foradentro,2,',','.') ?></td>
        <td rowspan=2 class=xl124 align=right style='border-bottom:1.0pt solid black'>R$ <?= number_format($tot_credito_dentrofora,2,',','.') ?></td>
    </tr>

    <tr height=21 style='height:15.75pt'>
    </tr>

    <tr height=21 style='height:15.75pt'>
    <td height=21 class=xl92 width=85 style='height:15.75pt;width:64pt'></td>
    <td class=xl68></td>
    <td class=xl68></td>
    <td class=xl93></td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td colspan=3 rowspan=3 height=61 class=xl96 style='border-bottom:1.0pt solid black;height:45.75pt'>VALOR FINAL DA APURAÇÃO <?= $getdados->mesano; ?></td>
        <td class=xl94>VALOR DO CRÉDITO</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td rowspan=2 height=41 class=xl127 align=right style='border-bottom:1.0pt solid black;	height:30.75pt'>R$ <?= number_format(($getdados->basecredito[0]->credito + $tot_credito_dentrofora),2,',','.'); ?></td>
    </tr>

    <tr height=21 style='height:15.75pt'>
    </tr>

    <tr height=20 style='height:15.0pt'>
        <td colspan=4 rowspan=2 height=40 class=xl108 width=697 style='height:30.0pt;width:523pt'>
        (*) Para o Valor da Base é considerado o cálculo da QUANTIDADE
        (x) VALOR UNITÁRIO do produto.<br>
        NÃO são considerados descontos ou acréscimos informados na nota (Ex.:
        ICMS-ST, FRETE, OUTRAS DESPESAS...).</td>
    </tr>

    <tr height=20 style='height:15.0pt'>
    </tr>

    </table>
	<?php }else{ ?>
		<div  align="center" class="col-12" style="width:60%;">
		MÊS/ANO-> <?= $getdados->mesano; ?><br/>
		----------------------------------<br/>
		<br/>
		ANIMAIS ABATIDOS<br/>
		---------------------------------<br/>
		<br/>
		<div style="text-align:center; display:inline-table;">
		<div style="display: inline-table; margin-right: 20px; text-align: left; width: 88px;">
			BOVINOS
		</div>
		<div style="display: inline-table; margin-left: -20px; margin-right: -5px; text-align: right; width: 38px; ">
			<?= $getdados->animais[0]->bovinos; ?>
		</div>
		<br/>
		<div style="display:inline-table; text-align:left; margin-right:20px; width:80px; ">
			BUBALINOS
		</div>
		<div style="display: inline-table; margin-left: -20px; margin-right: -5px; text-align: right; width: 38px; ">
			<?= $getdados->animais[0]->bubalinos; ?>
		</div>
		<br/>
		<div style="display:inline-table; text-align:left; margin-right:20px; width:87px; ">
			OVINOS
		</div>
		<div style="display: inline-table; margin-left: -20px; margin-right: -5px; text-align: right; width: 38px; ">
			<?= $getdados->animais[0]->ovinos; ?>
		</div>
		
	</div>
	<br/>
	<br/>
	APURACAO DO CREDITO<br/>
	---------------------------------------- <br/>
		 	
			<div class="col-8">
			<table class="table" style="width:100%;"> 
				<thead>
					<tr>
						<th>NOTAS</th>
						<th class="text-right">BASE</th>
						<th class="text-right">CREDITO</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>DE PRODUTOR..........................</td>
						<td class="text-right"><?= number_format($getdados->basecredito[0]->base,2,',','.'); ?></td>
						<td class="text-right"><?= number_format($getdados->basecredito[0]->credito,2,',','.'); ?></td>
					</tr>
					
					<tr>
						<td>VENDAS RIO GRANDE DO SUL 3%</td>
						<td class="text-right"><?= number_format($getdados->vendars[0]->basers,2,',','.'); ?></td>
						<td class="text-right"><?= number_format($getdados->vendars[0]->creditors,2,',','.'); ?></td>
					</tr>

					<tr>
						<td>VENDAS RIO GRANDE DO SUL 4%</td>
						<td class="text-right"><?= number_format($getdados->vendars2[0]->basers2,2,',','.'); ?></td>
						<td class="text-right"><?= number_format($getdados->vendars2[0]->creditors2,2,',','.'); ?></td>
					</tr>

					<tr>
						<td>VENDAS OUTROS ESTADOS 3%</td>
						<td class="text-right"><?= number_format($getdados->vendasdifrs[0]->basedifrs,2,',','.'); ?></td>
						<td class="text-right"><?= number_format($getdados->vendasdifrs[0]->creditodifrs,2,',','.'); ?></td>
					</tr>

					<tr>
						<td>VENDAS OUTROS ESTADOS 4%</td>
						<td class="text-right"><?= number_format($getdados->vendasdifrs2[0]->basedifrs2,2,',','.'); ?></td>
						<td class="text-right"><?= number_format($getdados->vendasdifrs2[0]->creditodifrs2,2,',','.'); ?></td>
					</tr>

				</tbody>
				<tfoot>
					<tr>
						<td>TOTAL GERAIS</td>
						<td class="text-right"><?= $getdados->total_geral_base; ?></td>
						<td class="text-right"><?= $getdados->total_geral_credito; ?></td>
					</tr>
				</tfoot>

			</table>
		</div>
		</div>
	<?php } ?>
</body>
</html>