<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    include_once "../classes/cls_tbacessos.php";
    include_once '../classes/cls_competencias.php';
    $id_user = $_POST["id_user"];
    $tp_user = $_POST["tp_user"];

    if (! isset($_POST['page'])) {
        $page = 1;
    } else {
        $page = $_POST["page"];
    }
    if (! isset($_POST['num_rows'])) {
        $num_rows = 1;
    } else {
        $num_rows = $_POST["num_rows"];
    }

    if (! isset($_POST['v_filter'])) {
        $filter_fields = NULL;
    } else {
        $filter_fields = json_decode($_POST["v_filter"],true);
    }

    $id_muni = isset($filter_fields['id_muni']) ? $filter_fields['id_muni'] : NULL;
    $dt_compet = isset($filter_fields['dt_compet']) ? $filter_fields['dt_compet'] : NULL;
    $id_user_filter = isset($filter_fields['id_user']) ? $filter_fields['id_user'] : NULL;
    $tp_user_filter = isset($filter_fields['tp_user']) ? $filter_fields['tp_user'] : NULL;
    $cd_status = isset($filter_fields['cd_status']) ? $filter_fields['cd_status'] : NULL;

    if (! isset($_POST['v_order'])) {
        $sort_fields = NULL;
    } else {
        $sort_fields = json_decode($_POST["v_order"], true);
    }
    if (is_null($dt_compet)) {
        $oCompet = new cls_competencias($id_user, $tp_user);
        $dt_compet = $oCompet->getUltimo()['dt_compet'];
        $dt_compet = substr($dt_compet,3) . '-' . substr($dt_compet,0,2);
        $filter_fields['dt_compet'] = $dt_compet;
    }
    
    if (! is_null($sort_fields)) {
        foreach ($sort_fields as $field => $value) {
            if (empty($value) or is_null($value)) {
                unset($sort_fields[$field]);
                continue;
            }
        }
    }
    if (! is_null($id_user_filter)) {
        $id_user = $id_user_filter;
        $tp_user = $tp_user_filter;
    }

    $oMunicipios = new cls_tableAcessos($id_user, $tp_user);

    if (isset($_POST['num_rows'])) {
        $nrows = $_POST['num_rows'];
        $nrows = intval($nrows);
        $oMunicipios->setLimitPage($nrows);      
    }

    $vlistMuni = $oMunicipios->getRows($page, $filter_fields, $sort_fields);
    if (! empty($filter_fields)) {
        $ntotalrecords = $oMunicipios->getTotalFiltered($filter_fields);
    } else {
        $ntotalrecords = $oMunicipios->getTotalRecords();
    }

    $lines = array();
    
    foreach ($vlistMuni as $row) {
        $cline = '';
        $id_muni = isset($row['id_muni']) ? $row['id_muni'] : '0';
        $id_user = isset($row['id_user']) ? $row['id_user'] : '0';
        $tp_user = isset($row['tp_user']) ? $row['tp_user'] : '0';
        $id_obrigacaoacessoria = isset($row['id_obrigacaoacessoria'])? $row['id_obrigacaoacessoria'] : '0';
        $id_usermunicipio = isset($row['id_usermunicipio'])? $row['id_usermunicipio'] : '0';
        $id_divergencia = isset($row['id_divergencia'])? $row['id_divergencia'] : '0';
        $id_idsistema = isset($row['id_idsistema'])? $row['id_idsistema'] : '0';
        $cd_estado = isset($row['cd_estado'])? $row['cd_estado'] : 'X';
        $nm_estado = isset($row['nm_estado'])? $row['nm_estado'] : 'X';
        $nm_muni = isset($row['nm_muni'])? $row['nm_muni'] : 'X';
        $nm_responsavel = isset($row['responsavel'])? $row['responsavel'] : 'X';
        $cd_status = isset($row['cd_status'])? $row['cd_status'] : '3';
        $te_status = isset($row['te_status'])? $row['te_status'] : 'Aguardando Movimenta&ccedil;&atilde;o';
        $te_color = isset($row['te_color'])? $row['te_color'] : '#FFD000';
        
        try {
            $vl_total_iss = isset($row['total_ISS'])? $row['total_ISS']: 0.0;
            $vl_total_iss = floatval($vl_total_iss);
            $vl_total_iss = number_format($vl_total_iss, 2,',','.');
        } catch (\Exception $e) {
            $vl_total_iss = isset($row['total_ISS'])? $row['total_ISS']: 0.0;
        }
        $dt_obrigacao = isset($row['dt_obrigacaoacessoria'])? $row['dt_obrigacaoacessoria']: '__/__/_____';
        $hr_obrigacao = isset($row['hr_slaobrigacaoacessoria'])? $row['dt_slaobrigacaoacessoria']: '00:00';
        $dt_entregaguia = isset($row['dt_entregaguia'])? $row['dt_entregaguia']: '__/__/_____';
        $hr_slaentregaguia = isset($row['hr_slaentreguia'])? $row['hr_slaentreguia']: '00:00';
        $dt_vencrecolhimento = isset($row['dt_vencrecolhimento'])? $row['dt_vencrecolhimento']: '__/__/_____';
        $hr_slapagamentoguia = isset($row['hr_slapagamentoguia'])? $row['hr_slapagamentoguia']: '00:00';
        $cs_desif = isset($row['cs_desif'])? $row['cs_desif'] : 'N';
        $cs_ativo = isset($row['cs_ativo'])? $row['cs_ativo'] : 'S';
        $dt_modulo1 = isset($row['dt_modulo1'])? $row['dt_modulo1']: '__/__/_____';
        $dt_modulo2 = isset($row['dt_modulo2'])? $row['dt_modulo2']: '__/__/_____';
        $dt_modulo3 = isset($row['dt_modulo3'])? $row['dt_modulo3']: '__/__/_____';
        $dt_modulo4 = isset($row['dt_modulo4'])? $row['dt_modulo4']: '__/__/_____';

        $cline .= "<tr scope='row' class='mw-100 bg-white text-dark' data-idmuni='$id_muni' 
                        data-idresponsavel='$id_user' data-tpuser='$tp_user' 
                        data-idobrigacao='$id_obrigacaoacessoria' data-idusermunicipio='$id_usermunicipio'
                        data-iddivergencia='$id_divergencia' data-idsistema='$id_idsistema'>";
        $cline .= "<td scope='col' class='sm-auto'>$cd_estado</td>";
        $cline .= "<td scope='col'>$nm_muni</td>";
        $cline .= "<td scope='col'>$nm_responsavel</td>";
        $cline .= "<td scope='col' style='text-align:left;'><span style='background-color:$te_color !important;' class='status badge rounded-pill'>$te_status</span></td>";
        $cline .= "<td scope='col' style='text-align:right !important;font-sytle:bold; padding-right:1.5em;'>$vl_total_iss</td>";
        $cline .= "<td scope='col' style='text-align:center;'>$dt_obrigacao</td>";
        $cline .= "<td scope='col' style='text-align:center;'>$hr_obrigacao</td>";
        $cline .= "<td scope='col' style='text-align:center;'>$dt_entregaguia</td>";
        $cline .= "<td scope='col' style='text-align:center;'>$hr_slaentregaguia</td>";
        $cline .= "<td scope='col' style='text-align:center;'>$dt_vencrecolhimento</td>";
        $cline .= "<td scope='col' style='text-align:center;'>$hr_slapagamentoguia</td>";
        $cline .= "<td scope='col' style='text-align:center;'>$cs_desif</td>";
        $cline .= "<td class='text-center' scope='col' style='text-align:center;'>$dt_modulo1</td>";
        $cline .= "<td class='text-center' scope='col' style='text-align:center;'>$dt_modulo2</td>";
        $cline .= "<td class='text-center' scope='col' style='text-align:center;'>$dt_modulo3</td>";
        $cline .= "<td class='text-center' scope='col' style='text-align:center;'>$dt_modulo4</td>";
        $cline .= "<td scope='col' style='width:10%;'>&nbsp;</td>";
        $cline .= "</tr>";
        $cline =  @iconv('UTF-8', 'UTF-8//IGNORE', $cline);
        array_push($lines, $cline);
    }
    if (json_last_error() == JSON_ERROR_NONE) 
    {
        $data = json_encode($lines);
        $vresult = array("Error" => '0', "Message" => "Leu $ntotalrecords registros", "Data" => $data, "Total_Records" => $ntotalrecords);
    } else {
    $vresult = array("Error" => json_last_error(), "Message" => json_last_error_msg());
    }
    echo json_encode($vresult);
?>