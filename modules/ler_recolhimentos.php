<?php
    error_reporting(0);
    error_reporting(E_ALL);

    $id_user= $_POST['id_user'];
    $tp_user= $_POST['tp_user'];
    if (isset($_POST['id_muni'])) {
        $id_muni= $_POST['id_muni'];
        $id_muni = intval($id_muni);
    } else {
        $id_muni = NULL;
    }
    if (isset($_POST['dt_compet'])) {
        $dt_compet= $_POST['dt_compet'];
    } else {
        $dt_compet = NULL;
    }
    
    if (isset($_POST['npage'])) {
        $npage= $_POST['npage'];
        if (is_numeric($npage)) {
            $npage = intval($npage);
        } else {
            $npage = 1;
        }
    } else {
        $npage =1;
    }
    if (isset($_POST['nrows'])) {
        $nrows= $_POST['nrows'];
        if (is_numeric($nrows)) {
            $nrows = intval($nrows);
        } else {
            $nrows = 8;
        }
    } else {
        $nrows = 8;
    }
    require_once ("../classes/cls_agencias_muni.php");
    require_once ("../classes/cls_esteiraatual.php");
    require_once ("../classes/cls_usuarios.php");
    require_once ("../classes/cls_divergencias.php");
    require_once ("../classes/cls_justificativas.php");
    require_once '../classes/cls_loadimg.php';
    require_once '../classes/cls_tipostributos.php';
    $oImg = new cls_loadimg();
    $anexo = $oImg->getImageUrl("anexo")["source"]; 
    
    $oEsteiras = new cls_esteiraatual();
    $vlist_esteiras = array();
    $vresult_esteiras = json_decode($oEsteiras->getRows());
    if ($vresult_esteiras->Error == '0') {
        $cursor = $vresult_esteiras->Dados;
        foreach ($cursor as $row) {
            $row = get_object_vars($row);
            array_push($vlist_esteiras, $row);
        }

    } else {
        $vlist_esteiras = array(['cd_status' => '11', 'te_status' => 'Não processado' ]);
    }
    $oTiposTributos = new cls_tipostributos();

    $vresult_tipos = $oTiposTributos->getCursor();
    $vlistTributos = $vresult_tipos['Data'];

    $oJustificativa = new cls_justificativas();
    $oUsers = new cls_usuarios();
    $oRecolhimentos = new cls_agencias_muni($id_muni, $dt_compet);
    $oRecolhimentos->setLimitPage($nrows);
    $ntotal_records = $oRecolhimentos->getTotalRecords();

    $bsemResultados = True;

    if ($ntotal_records > 0) {
        $vresult = $oRecolhimentos->getRows($npage);
        $data = $vresult['Data'];
       
        if (count($data) > 0) {
            $bsemResultados = False;
        } 
    } 
    $oDivergencia = new cls_divergencias();
    $result = $oDivergencia->getCursor();
    $erro = $result['Error'];
    $vlistDivergencias = array();
    if ($erro == '0') {
        $vlistDivergencias = $result['Data'];
    } 
    $lines = array();

    if (!$bsemResultados) {
        foreach ($data as $nix => $row) {
            $id_recolhimento = $row['id_recolhimento'] ?? 0;
            $id_recolhimento = intval($id_recolhimento);
            $id_sistema = $row['id_sistema'] ?? 0;
            $id_sistema = intval($id_sistema);
            $id_agencia = $row['id_agencia'] ?? 0;
            $id_agencia = intval($id_agencia); // Completing based on pattern
            
            $id_agenciaxmunicipio = $row['id_agenciaxmunicipio'] ?? 0;
            $id_agenciaxmunicipio = intval($id_agenciaxmunicipio); // Consistency
            
            $id_obrigacaoacessoria = $row['id_obrigacaoacessoria'] ?? 0;
            $id_obrigacaoacessoria = intval($id_obrigacaoacessoria);
            
            $id_tributo = $row['id_tributo'] ?? 0;
            $id_tributo = intval($id_tributo);
            
            $id_justificativa = $row['id_justificativa'] ?? 0;
            $id_justificativa = intval($id_justificativa);
            
            $id_tp_tribut = $row['id_tp_tribut'] ?? 0;
            $id_tp_tribut = intval($id_tp_tribut);
            
            $id_elaborador = $row['id_elaborador'] ?? 0;
            $id_elaborador = intval($id_elaborador);
            
            $id_aprovador = $row['id_aprovador'] ?? 0;
            $id_aprovador = intval($id_aprovador);
            $id_muni = $row['id_muni'] ?? 0;
            $id_muni = intval($id_muni);
            $id_divergencia = $row['id_divergencia'] ?? 0;
            $id_divergencia = intval($id_divergencia);

            $cd_tipo_recolhimento = $row['cd_tipo_recolhimento'] ?? '';
            $cd_tipoagencia = $row['cd_tipoagencia'] ?? '';
            $dt_compet = $row['dt_compet'] ?? '';
            $nu_guia = $row['nu_guia'] ?? '';
            $nu_protocolo = $row['nu_protocolo'] ?? '';
            $nu_contacontabil = $row['nu_contacontabil'] ?? '';
            $te_aprovacao = $row['te_aprovacao'] ?? '';
            $te_obs_outras_div = $row['te_obs_outras_div'] ?? '';
            $te_obs_divergencia = $row['te_obs_divergencia'] ?? '';
            $te_evidnaorecolhimento = $row['te_evidnaorecolhimento'] ?? '';
            $te_obs_just = $row['te_obs_just'] ?? '';
            $cd_status_atual = $row['cd_status_atual'] ?? '11';
            $cs_esteiraatual = $row['cs_esteiraatual'] ?? '11';
            $cd_statusvalidacaoplus = $row['cd_statusvalidacaoplus'] ?? '11';
            $cd_unificadoxseparado = $row['cd_unificadoxseparado'] ?? '';
            $dt_recolhimento = $row['dt_recolhimento'] ?? '';
            $dt_emissaoguia = $row['dt_emissaoguia'] ?? '';
            $dt_pagamentoguia = $row['dt_pagamentoguia'] ?? '';
            $cs_status_pgto = $row['cs_status_pgto'] ?? 'N';
            $vl_base_calculo = $row['vl_base_calculo'] ?? 0.0;
            $vl_iss_db = $row['vl_iss_db'] ?? 0.0;
            $vl_iss_user = $row['vl_iss_user'] ?? 0.0;
            $vl_descontos = $row['vl_descontos'] ?? 0.0;
            $vl_protocolo = $row['vl_protocolo'] ?? 0.0;
            $vl_guia = $row['vl_guia'] ?? 0.0;
            $vl_correcao_guia = $row['vl_correcao_guia'] ?? 0.0;
            $te_status = $row['te_status'] ??  '';
            $te_color = $row['te_color'] ?? '';
            $dt_obrigacaoacessoria = $row['dt_obrigacaoacessoria'] ?? '';
            $hr_slaobrigacaoacessoria = $row['hr_slaobrigacaoacessoria'] ?? '';
            $dt_vencrecolhimento = $row['dt_vencrecolhimento'] ?? '';
            $hr_slapagamentoguia = $row['hr_slapagamentoguia'] ?? '';
            $dt_entregaguia = $row['dt_entregaguia'] ?? '';
            $hr_slaentregaguia = $row['hr_slaentregaguia'] ?? '';
            $te_observacao = $row['te_observacao'] ?? '';
            $vl_juros = $row['vl_juros'] ?? 0.0;
            $vl_multa = $row['vl_multa'] ?? 0.0;
            $vl_arredondamento = $row['vl_arredondamento'] ?? 0.0;
            $vl_totalarecolher = $row['vl_totalarecolher'] ?? 0.0;
            $cd_codagencia = $row['cd_codagencia'] ?? '';
            $nm_agencia = $row['nm_agencia'] ?? '';
            $nu_cnpj = $row['nu_cnpj'] ?? '';
            $nu_ccm = $row['nu_ccm'] ?? 'Não informado';
            $cd_uniorg_sap = $row['cd_uniorg_sap'] ?? '';
            $cd_uniorg_iss = $row['cd_uniorg_iss'] ?? '';
            $nm_contato = $row['nm_contato'] ?? '';//--------------------
            $te_email = $row['te_email'] ?? '';//----------------------------
            $nu_ddd = $row['nu_ddd'] ?? '';//-----------------------------
            $nu_telefone = $row['nu_telefone'] ?? '';//--------------------------
            $nm_contato_suporte = $row['nm_contato_suporte'] ?? '';//--------------------
            $te_email_suporte = $row['te_email_suporte'] ?? '';//-------------------
            $nu_ddd_suporte = $row['nu_ddd_suporte'] ?? '';//-------------------
            $nu_telefone_suporte = $row['nu_telefone_suporte'] ?? '';//-------------------
            $cd_tributo = $row['cd_tributo'] ?? '';
            $te_tributo = $row['te_tributo'] ?? ' -*- ';
            $tp_tributo = $row['tp_tributo'] ?? '';
            $cd_contacontabil = $row['cd_contacontabil'] ?? '';
            $cd_justificativa = $row['cd_justificativa'] ?? '';
            $te_justificativa = $row['te_justificativa'] ?? '';
         
            $taxa_esc = 0.0;

            $sprime_line = "data-id_recolhimento='$id_recolhimento' data-id_sistema='$id_sistema' data-id_agencia='$id_agencia' data-id_agenciaxmunicipio='$id_agenciaxmunicipio' data-id_obrigacaoacessoria='$id_obrigacaoacessoria' data-id_tributo='$id_tributo' data-id_justificativa='$id_justificativa' data-id_tp_tribut='$id_tp_tribut' data-id_elaborador='$id_elaborador' data-id_aprovador='$id_aprovador' data-id_muni='$id_muni' data-id_divergencia='$id_divergencia'";
            // $sline = "<tr scope='row' class='d-inline-flex' $sprime_line>";
            $sline = "<tr scope='row' class='gy-0 headitem' $sprime_line>";
            $sline .= "<td scope='col'><input type='radio' class='item' name='sel_agencia' id='sel_agencia-$id_agencia' value='$id_agencia'></td>";//------------
            $sline .= "<td scope='col' class='align-items-left'>";
            $cmd = "<select name='sel_status_atual' id='sel_statusatual-$id_agencia' class='form-select bg-transparent text-dark'>";
            foreach ($vlist_esteiras as $row_esteira) {
                $cd_curresteira = $row_esteira['cd_status'];
                $te_descricao = $row_esteira['te_status'];
                if ($cd_curresteira == $cd_status_atual) {
                    $cmd.= "<option value='$cd_curresteira' selected>$te_descricao</option>";
                } else {
                    $cmd.= "<option value='$cd_curresteira'>$te_descricao</option>";
                }
            }
            $cmd.= "</select>";
            $sline.= $cmd;
            $sline.= "</td>";
            $sline .= "<td scope='col' class='text-center'><label class='form-control-label text-dark'>$cd_codagencia</label></td>";
            $sline .= "<td scope='col' class='text-start'><label class='form-control-label text-dark'>$nu_cnpj</label></td>";
            $sline .= "<td scope='col' class='text-start'><label class='form-control-label text-dark'>$nu_ccm</label></td>";
            $sline .= "<td scope='col' class='text-start' data-cd_tributo='$cd_tributo'><label class='form-control-label text-dark'>$te_tributo</label></td>";
            $sline .= "<td scope='col' class='text-start'>";
            $sline .= "<select class='form-select bg-transparent text-dark' id='sel_tptributo-$id_agencia'>";
            foreach ($vlistTributos as $nix=> $row) {
                $id_tipotributo = intval($row['id_tipotributo']);
                $cd_tipotributo = $row['tp_tributo'];
                $cd_cccontabil = $row['cd_contacontabil'];
                if ($nix == 0) {
                    if ($id_tp_tribut == 0) 
                        $sline .= "<option value='0' SELECTED> - * - </option>";
                    else
                        $sline .= "<option value='0'> - * - </option>";
                }
                 if ($cd_tipotributo == $id_tp_tribut) {
                    $sline .= "<option value='$id_tipotributo' data-contacontabil='$cd_cccontabil' selected>$cd_tipotributo</option>";
                    $cd_contacontabil = $cd_cccontabil;
                } else {
                    $sline .= "<option value='$id_tipotributo' data-contacontabil='$cd_cccontabil'>$cd_tipotributo</option>";
                }
            }
            $sline .="</select>";
            $sline .= "</td>";
            $sline .= "<td scope='col' class='text-end'><label class='form-control-label text-dark text-center'>" . number_format($vl_base_calculo,2,',','.') . "</label></td>";
            $sline .= "<td scope='col' class='text-end' data-cd_tipoagencia='$cd_tipoagencia'><label class='form-control-label text-dark text-center' id='vl_baseISS-$id_agencia'>" . number_format($vl_base_calculo, 2, ',', '.') . "</label></td>";
            $sline .= "<td scope='col' class='text-end'><input type='text' class='form-control-input input-sm text-end' value='" . number_format($vl_iss_db,2,',','.') . "' readonly></td>";
            $sline .= "<td scope='col' class='text-end col-sm-auto'><input type='text' class='form-control input-sm text-end' value='" . number_format($vl_juros,2,',','.') . "' id = 'vl_iss-$id_agencia'></td>";
            $sline .= "<td scope='col' class='text-end'><input type='text' class='form-control input-sm  text-end' value='" . number_format($vl_multa,2,',','.') . "' id = 'vl_multa-$id_agencia'></td>";
            $sline .= "<td scope='col' class='text-end'><input type='text' class='form-control input-sm  text-end' value='" . number_format($taxa_esc,2,',','.') . "' id = 'vl_taxa_esc-$id_agencia'></td>";
            $sline .= "<td scope='col' class='text-end'><input type='text' class='form-control input-sm  text-end' value='" . number_format($vl_arredondamento,2,',','.') . "' id='vl_arredondamento-$id_agencia'></td>";
            $sline .= "<td scope='col' class='text-end'><input type='text' class='form-control input-sm  text-end' value='" . number_format($vl_descontos,2,',','.') . "' id= 'vl_descontos-$id_agencia'></td>";
            $sline .= "<td scope='col' class='text-end'>";
            $sline .= "<select name='sel_divergencias' id='sel_divergencias-$id_agencia' class='form-select bg-transparent text-dark'>";
            $sline .= "<option value='0'> - * - </option>";
            foreach ($vlistDivergencias as $row_divergencia) {
                $id_currdivergencia = $row_divergencia['id_divergencia'];
                $te_divergencia = $row_divergencia['te_divergencia'];
                $cd_tipo = $row_divergencia['cd_divergencia'];
                if ($cd_tipo == $cd_tipoagencia) {
                    if ($id_currdivergencia == $id_divergencia) {
                        $sline.= "<option value='$id_currdivergencia' selected>$te_divergencia</option>";
                    } else {
                        $sline.= "<option value='$id_currdivergencia'>$te_divergencia</option>";
                    }
                }
            }
            $sline.= "</select></td>";
            $sline .= "<td scope='col' class='text-end'><input type='text' class='form-control input-sm text-end' value='" . number_format($vl_totalarecolher,2,',','.') . "' id='vl_totalarecolher-$id_agencia'></td>";

            if ($id_elaborador > 0) {
                $oElaborador = $oUsers->getCursor($id_elaborador);
                $nm_elaborador = $oElaborador['nm_user'];
            } else {
                $nm_elaborador = '-x-';
            }
            
            $sline .= "<td scope='col' class='text-start'><label class='form-control-label text-center text-dark'>$nm_elaborador</label></td>";
            
            $sline .= "<td scope='col' class='text-start style='white-space: nowrap !important;>";
            $sline .= "<input type='text' class='form-control input-sm text-start' size='10' id='nu_protocolo-$id_agencia' value='$nu_protocolo'></td>";
            $sline .= "<td scope='col' class='form-control-image'><image src='$anexo' width='16' alt='anexo' title='Anexar protocolo' style='cursor:pointer; filter:invert(100%)'/></td>";
            $sline .= "<td scope='col' class='text-end' ><input type='text' class='form-control input-sm text-end' value='" . number_format($vl_protocolo,2,',','.') . "' id='vl_protocolo-$id_agencia'></td>";
            $sline .= "<td scope='col' class='text-start' style='white-space: nowrap;'>";
            $sline .= "<input type='text' class='form-control input-sm' size='10' id='nu_guia-$id_agencia' value='$nu_guia'></td>";
            $sline .= "<td scope='col' class='form-control-image'><image src='$anexo' width='16' alt='anexo' title='Anexar guia' style='cursor:pointer;filter:invert(100%);'/></td>";
            $sline .= "<td scope='col' class='text-end'><input type='text' class='form-control input-sm text-end' value='" . number_format($vl_guia,2,',','.') . "' id='vl_guia-$id_agencia'></td>";
            $sline .= "<td scope='col' class='text-end'><input type='text' class='form-control input-sm text-end' value='" . number_format($vl_correcao_guia ,2,',','.') . "' id='vl_correcao_guia-$id_agencia'></td>";
            
            $sline .= "<td scope='col' class='text-start'>";
            $sline .= "<select name='sel_divergencias' id='sel_divergencias-$id_agencia' class='form-select bg-transparent text-dark'>";
            $sline .= "<option value='0'> - * - </option>";
            foreach ($vlistDivergencias as $row_divergencia) {
                $id_currdivergencia = $row_divergencia['id_divergencia'];
                $te_divergencia = $row_divergencia['te_divergencia'];
                if ($id_currdivergencia == $id_divergencia) {
                    $sline.= "<option value='$id_currdivergencia' selected>$te_divergencia</option>";
                } else {
                    $sline.= "<option value='$id_currdivergencia'>$te_divergencia</option>";
                }
            }
            $sline.= "</select></td>";
            
            if ($id_aprovador > 0) {
                $oAprovador = $oUsers->getCursor($id_aprovador);
                $nm_aprovador = $oAprovador['nm_user'];
            } else {
                $nm_aprovador = '-x-';
            }
            
            $sline .= "<td scope='col' class='text-center text-dark'>$nm_aprovador</td>";
          
            $sline .= "<td scope='col' class='text-center'>";
            $sline .= "<select name='sel_validacao' id='sel_validacao-$id_agencia' class='form-select bg-transparent text-dark'>";
            $sline .= "<option value='0' selected>- * -</option>";
            foreach ($vlist_esteiras as $row_esteira) {
                $cd_curresteira = $row_esteira['cd_status'];
                $te_descricao = $row_esteira['te_status'];
                if ($cd_curresteira == $cs_esteiraatual) {
                    $sline.= "<option value='$cd_curresteira' selected>$te_descricao</option>";
                } else {
                    $sline.= "<option value='$cd_curresteira'>$te_descricao</option>";
                }
            }
            $sline .= "</select></td>";
            
            $sline .= "<td scope='col' class='text-center'>";
            $sline .= "<select name='sel_validacaoplus' id='sel_validacaoplus-$id_agencia' class='form-select bg-transparent text-dark'>";
            $sline .= "<option value='0' selected>- * -</option>";
            foreach ($vlist_esteiras as $row_esteira) {
                $cd_curresteira = $row_esteira['cd_status'];
                $te_descricao = $row_esteira['te_status'];
                
                if ($cd_curresteira == $cd_statusvalidacaoplus) {
                    $sline .= "<option value='$cd_curresteira' selected>$te_descricao</option>";
                } else {
                    $sline.= "<option value='$cd_curresteira'>$te_descricao</option>";
                }
            }

            $sline .= "</select></td>";
            $sline .= "<td scope='col' class='text-center'>";
            $sline .= "<select name='sel_justificativa' id='sel_justificativa-$id_agencia' class='form-select bg-transparent text-dark'>";
            $sline .= "<option value='0' selected>- * -</option>";
            $vresult = $oJustificativa->getCursor($cd_tipoagencia);
            if ($vresult['Error'] == '0') {
                $vlistJustificativas = $vresult['Data'];
                foreach ($vlistJustificativas as $row_justificativa) {
                    $cd_justificativa = $row_justificativa['cd_justificativa'];
                    $te_justificativa = $row_justificativa['te_justificativa'];
                    if ($cd_justificativa == $cd_justificativa) {
                        $sline .= "<option value='$cd_justificativa' selected>$te_justificativa</option>";
                    } else {
                        $sline .= "<option value='$cd_justificativa'>$te_justificativa</option>";
                    }
                }
            } else {

            }
            $sline .= "</select>";
            $sline .= "</td>";
            
            $sline .= "<td scope='col' class='text-start'>";
            $sline .= "<textarea class='form-control input-sm bg-transparent' rows='2' cols='25' id='te_justificativa-$id_agencia'>$te_justificativa</textarea></td>";
            $sline .= "<td scope='col' class='text-center'>";
            $sline .= "<image src='$anexo' width='16' alt='anexo' title='Anexar comprovante pgto.' style='filter:invert(100%);' ></td>";
            $sline .= "<td scope='col' class='text-center'>";
            $sline .= "<div class='form-check'>";
            $lbstatus_pgto = "Não";
            if (empty($nu_protocolo)) {
                $sline .= "<input class='form-check-input text-dark' type='checkbox' id='chk_status_pgto-$id_agencia' name='chk_status_pgto' value='$cs_status_pgto' disabled>";
            } else {
                $lbstatus_pgto = "Sim";
                $sline .= "<input class='form-check-input text-dark' type='checkbox' id='chk_status_pgto-$id_agencia' name='chk_status_pgto' value='$cs_status_pgto' checked>";
            }
            
            $sline .= "<label class='form-check-label text-dark' for='chk_status_pgto-$id_agencia'>$lbstatus_pgto</label>";
            $sline .= "</div></td>";
            $sline .= "<td scope='col' class='text-center text-dark'><label class='form-control-label text-dark'>$cd_contacontabil</label></td>";
            $sline .= "<td scope='col'>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $sline .= "</tr>";
            
            $sline =  @iconv('UTF-8', 'UTF-8//IGNORE', $sline);
            array_push($lines, $sline);
        }

    } else {
        $sline = "<td scope='row' colspan='33'>Não encontrou registros de recolhimento</td></tr>";
        $sline =  @iconv('UTF-8', 'UTF-8//IGNORE', $sline);
        array_push($lines, $sline);
    }

    if (json_last_error() == JSON_ERROR_NONE) 
    {
        $data = json_encode($lines);
        $vresult = array("Error" => '0', "Message" => "Leu $ntotal_records registros", "Data" => $data, "Total_Records" => $ntotal_records);
    } else {
    $vresult = array("Error" => json_last_error(), "Message" => json_last_error_msg());
    }
    echo json_encode($vresult);

   
    ?>
   