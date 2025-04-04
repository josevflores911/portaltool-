<?php
    error_reporting(0);
    error_reporting(E_ALL);
    $id_agencia = $_POST['id_agencia'];
    $dt_compet = $_POST['dt_compet'];

    if (isset($_POST['nrows'])) {
        $nrows = $_POST['nrows'];
        if (is_numeric($nrows)) {
            $nrows = intval($nrows);
        } else {
            $nrows = 20;
        }
    } else {
        $nrows = 20;
    }
    if (isset($_POST['npage'])) {
        $npage = $_POST['npage'];
        if (is_numeric($npage)) {
            $npage = intval($npage);
        } else {
            $npage = 1;
        }
    } else {
        $npage = 1;
    }

    require_once("../classes/cls_notas.php");

    $oNotas = new cls_notas($id_agencia, $dt_compet);
    $oNotas->setLimitPage($nrows);
    $nTotalRecords = $oNotas->getTotalRecords();

    $bSemResultados = TRUE;

    if ($nTotalRecords > 0) {
        $vResult = $oNotas->getRows($npage);
        $data = $vResult['Data'];

        if (count($data) > 0) {
            $bSemResultados = FALSE;
        }
    }

    $lines = array();

    if (!$bSemResultados) {
        foreach ($data as $row) {
            $cd_estado = $row['UF'] ?? 'N/A';
            $endereco = $row['EnderecoFornecedor'] ?? 'N/A';
            $nm_bairro = $row['BairroFornecedor'] ?? '';
            $nu_cep = $row['CEPFornecedor'] ?? 'Não Informado';
            $cd_tipoimposto = $row['TpDoc'] ?? '';
            $nu_registrosap = $row['NumRegistro'] ?? '-*-';
            $cd_servico = $row['CdServ'] ?? '-*-';
            $nu_nota = $row['NumDoc'] ?? 'Não Informado';
            $dt_retencao = $row['DtRetencao'] ?? NULL;
            if ($dt_retencao) {
                $dt_retencao = date('d/m/Y', strtotime($dt_retencao));
            } else {
                $dt_retencao = '  /  /  ';
            }
            $dt_competencia = $row['DtCompet'] ?? NULL;
            if ($dt_competencia) {
                $dt_competencia = date('d/m/Y', strtotime($dt_competencia));
            } else {
                $dt_competencia = '  /  /  ';
            }
            $dt_vencimento = $row['DtVenc'] ?? NULL;
            if ($dt_vencimento) {
                $dt_vencimento = date('d/m/Y', strtotime($dt_vencimento));
            } else {
                $dt_vencimento = '  /  /  ';
            }
            $vl_total = $row['VlTotal'] ?? 0.0;
            $vl_total = number_format($vl_total, 2, '.', '');
            $vl_base = $row['VlBase'] ?? 0.0;
            $vl_base = number_format($vl_base, 2, '.', '');
            $vl_percISS = $row['Aliq'] ?? 0.0;
            $vl_iss = $row['ValorISS'] ?? 0.0;
            $vl_iss = number_format($vl_iss, 2, '.', '');
            $te_justificativa = $row['Just'] ?? '';
            $vl_diferencaPerc = $row['DivergAliq'] ?? 0.0;
            $vl_diferenca = $row['DivergVl']?? 0.0;
            $vl_diferenca = number_format($vl_diferenca, 2, '.', '');
            $sline = "<tr scope='row'>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$cd_estado}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$nm_bairro}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$endereco}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$nu_cep}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$cd_tipoimposto}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$nu_registrosap}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$cd_servico}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$nu_nota}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$dt_retencao}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$dt_competencia}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$dt_vencimento}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$vl_total}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$vl_base}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$vl_percISS}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$vl_iss}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$te_justificativa}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$vl_diferencaPerc}</label></td>";
            $sline.= "<td scope='col'><label class='form-control-label bg-transparent text-dark'>{$vl_diferenca}</label></td>";
            $sline.= "</tr>";
              
            $sline =  @iconv('UTF-8', 'UTF-8//IGNORE', $sline);
            array_push($lines, $sline);
        }
    } else {
        $sline = "<tr><td colspan='33'>Não encontrou registros de recolhimento</td></tr>";
        $sline = @iconv('UTF-8', 'UTF-8//IGNORE', $sline);
        array_push($lines, $sline);
    }

    if (json_last_error() === JSON_ERROR_NONE) {
        $dataJson = json_encode($lines);
        $vresult = array(
            "Error"         => '0', 
            "Message"       => "Leu {$nTotalRecords} registros", 
            "Data"          => $dataJson, 
            "Total_Records" => $nTotalRecords
        );
    } else {
        $vresult = array(
            "Error"   => json_last_error(), 
            "Message" => json_last_error_msg()
        );
    }
    
    echo json_encode($vresult);
?>