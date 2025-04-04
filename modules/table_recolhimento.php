<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    include_once "../classes/cls_agencias_muni.php";

    $id_muni = isset($_POST["id_muni"]) ? $_POST["id_muni"] : null;
    $dt_compet = isset($_POST["dt_compet"]) ? $_POST["dt_compet"] : null;

    if (! isset($_POST['page'])) {
        $page = 1;
    } else {
        $page = $_POST["page"];
        if (gettype($page) != 'integer') {
            if (! is_numeric($page)) {
                $page = 1;
            } else {
                $page = intval($page);
            }
        } else {
            $page = 1;
        }
    }

    if (! isset($_POST['num_rows'])) {
        $num_rows = 15;
    } else {
        $num_rows = $_POST["num_rows"];
        if (gettype($num_rows)!= 'integer') {
            if (! is_numeric($num_rows)) {
                $page = 1;
            } else {
                $num_rows = intval($num_rows);
            }
        } else {
            $num_rows = 15;
        }
    }

    if ( is_null($id_muni) || is_null($dt_compet)) {
        echo json_encode(['Error' => '422', 'Message' => 'Dados não podem ser processados']);
    } else {
        $berros = FALSE;
    }

    if ($berros == FALSE) {
        $oRecolhimento = new cls_agencias_muni($id_muni, $dt_compet);

        if (isset($_POST['num_rows'])) {
            $nrows = $_POST['num_rows'];
            $nrows = intval($nrows);
            $oRecolhimento->setLimitPage($nrows);
        }

        $vlistRecolhimento = $oRecolhimento->getRows($page);
        $lines = array();

        foreach ($vlistRecolhimento as $row) {
            $cline = '';
            $cd_agencia = isset($row['cd_codagencia']);
            $nu_cnpj = isset($row['nu_cnpj']);
            $nu_ccm = isset($row['nu_ccm']) ? $row['nu_ccm'] : '';
            $tp_tributo = isset($row['cd_tipo_recolhimento']);
            $vl_base_calculo = isset($row['vl_base']);
            $vl_iss_db = isset($row['vl_iss_db']);
            $vl_juros = isset($row['vl_juros']) ? $row['vl_juros'] : '';
            $vl_multa = isset($row['vl_multa']) ? $row['vl_multa'] : '';
            $vl_taxa_excedente = isset($row['vl_tx_exc']) ? $row['vl_tx_exc'] : '';
            $vl_arredondamento = isset($row['vl_arredondamento']) ? $row['vl_arredondamento'] : '';
            $vl_desconto = isset($row['vl_descontos']) ? $row['vl_descontos'] : '';
            $vl_totalarecolher = isset($row['vl_totalarecolher']) ? $row['vl_totalarecolher'] : '';
            $elaboracao = isset($row['id_elaborador']) ? $row['id_elaborador'] : '';
            $protocolo = isset($row['protocolo']) ? $row['protocolo'] : '';
            $vl_protocolo = isset($row['vl_protocolo']) ? $row['vl_protocolo'] : '';
            $guia = isset($row['nu_guia']) ? $row['nu_guia'] : '';
            $vl_guia = isset($row['vl_guia']) ? $row['vl_guia'] : '';
            $correcao = isset($row['vl_correcao']) ? $row['vl_correcao'] : '';
            $aprovador = isset($row['id_aprovador']) ? $row['id_aprovador'] : '';
            $status_validacao = isset($row['cd_status_atual']) ? $row['cd_status_atual'] : '';
            $status_validacaoplus = isset($row['cd_statusvalidacaoplus']) ? $row['cd_statusvalidacaoplus'] : '';
            $status_pagamento = isset($row['cd_status_pagamento']) ? $row['cd_status_pagamento'] : '';
        }
    }
?>