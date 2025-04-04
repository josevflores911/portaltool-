<?php
    error_reporting(0);
    error_reporting(E_ALL);
    header('Content-Type: text/html; charset=utf-8');
    if (isset($_POST['id_muni'])) {
        $id_muni = $_POST['id_muni'];
        if (! is_null($id_muni)) {
            if (gettype($id_muni) !== 'integer') {
                if (is_numeric($id_muni)) {
                    $id_muni = intval($id_muni);
                } else {
                    $id_muni = NULL;
                }
            }
            if ($id_muni == 0) {
                $id_muni = NULL;
            }
        }
    } else {
        $id_muni = NULL;
    }

    if (isset($_POST['cd_estado'])) {
        $cd_estado = $_POST['cd_estado'];
        if (! is_null($cd_estado)) {
            if (! in_array($cd_estado, array('AC','AL','AM','AP','BA','CE','DF','ES','GO','MA','MG','MS','MT','PA','PB','PE','PI','PR','RJ','RN','RO','RR','RS','SC','SE','SP','TO'))) {
                $cd_estado=NULL;
            }
        }
    } else {
        $cd_estado = NULL;
    }
   

    if (isset($_POST['page'])) 
        $page = $_POST['page'];
        if (! is_null($page)) {
            if (gettype($page) !== 'integer') {
                if (is_numeric($page)) {
                    $page = intval($page);
                } else {
                    $page = 1;
                }
            }
            if ($page == 0) {
                $page = 1;
            }
        }
    else
        $page = 1;

    if (isset($_POST['nrows'])) 
        $nrows = $_POST['nrows'];
        if (! is_null($nrows)) {
            if (gettype($nrows) !== 'integer') {
                if (is_numeric($nrows)) {
                    $nrows = intval($nrows);
                } else {
                    $nrows = NULL;
                }
            }
            if ($nrows == 0) {
                $nrows = NULL;
            }
        }
    else
        $nrows = 10;

    if (isset($_POST['filter'])) {
        $filter = $_POST['filter'];
    } else {
        $filter = NULL;
    }
    include_once '../classes/cls_getallagenciasmuni.php';
    $oAgenciasMuni = new cls_agenciasMuni($id_muni, $cd_estado);
    $ntotalrecords = $oAgenciasMuni->getTotalRecords();
    $oAgenciasMuni->setLimitPage($nrows);    
    $vlist_agencias = $oAgenciasMuni->getRows($page, $filter);
    $data = $vlist_agencias['data'];
    if (count($data) > 0) {
        $lines = array();
        foreach ($data as $row) {
            $id_agenciaxmunicipio = $row['id_agenciaxmunicipio'];
            $id_agencia = $row['id_agencia'];
            $cd_agencia = $row['cd_codagencia'];
            $nm_agencia = (! is_null($row['nm_agencia'])) ? $row['nm_agencia'] : '';
            
            $nu_cnpj = $row['nu_cnpj'];
            $link_p = (! is_null($row['te_link'])) ? $row['te_link'] : '';
            $nm_sistema = (! is_null($row['nm_sistema'])) ? $row['nm_sistema'] : '';
            $user_p = (! is_null($row['nm_usuario'])) ? $row['nm_usuario'] : '';
            $pwd_p = (! is_null($row['te_senha'])) ? $row['te_senha'] : '';
            $link_t = ( ! is_null($row['te_linkt'])) ? $row['te_linkt'] : '';
            $user_t = (! is_null($row['nm_usuariot'])) ? $row['nm_usuariot'] : '';
            $pwd_t = ( ! is_null($row['te_senhat'])) ? $row['te_senhat'] : '';

            $nm_contato = (! is_null($row['nm_contato'])) ? $row['nm_contato'] : '';
            $te_email = (! is_null($row['te_email'])) ?  $row['te_email'] : '';
            $nu_telefone = (! is_null($row['nu_telefone'])) ? $row['nu_telefone'] : '';
            $nm_contato_suporte = (! is_null($row['nm_contato_suporte'])) ? $row['nm_contato_suporte'] : '';
            $te_email_suporte = (! is_null($row['te_email_suporte'])) ? $row['te_email_suporte'] : '';
            $nu_telefone_suporte = (! is_null($row['nu_telefone_suporte'])) ? $row['nu_telefone_suporte'] : '';
            
            if (strlen($nu_cnpj) < 14) {
                $nu_cnpj = str_pad($nu_cnpj, 14, '0', STR_PAD_LEFT);
            }
            $p1 = substr($nu_cnpj,0,2);
            $p2 = substr($nu_cnpj,2,3);
            $p3 = substr($nu_cnpj,5,3);
            $p4 = substr($nu_cnpj,8,4);
            $p5 = substr($nu_cnpj,12,2);
            $nu_cnpj = $p1.'.'.$p2.'.'.$p3.'/'.$p4.'-'.$p5;

            $nu_ccm = "-x-";
            $nu_ccusto = "-x-";
            $tp_endereco = $row['tp_endereco'];
            $endereco =  mb_convert_encoding($row['nm_endereco'], "UTF-8", "auto");
            $nu_endereco = $row['nu_endereco'];
            $te_complemento =  mb_convert_encoding($row['te_complemento'], "UTF-8", "auto");
            $nm_bairro = mb_convert_encoding($row['nm_bairro'], "UTF-8", "auto");
            $nm_cidade = mb_convert_encoding($row['nm_muni'], "UTF-8", "auto");
            $cd_estado = $row['cd_estado'];
            $cd_cep = $row['nu_cep'];
            if (! is_null($cd_cep)) {
                if (strlen($cd_cep) > 0) {
                    $cd_cep = str_pad($cd_cep, 8, '0', STR_PAD_LEFT);
                    $p1 = substr($cd_cep,0,5);
                    $p2 = substr($cd_cep,5,3);
                    $cd_cep = $p1.'-'.$p2;

                } else {
                    $cd_cep = '-x-';
                }
            } else {
                $cd_cep = '-x-';
            }
            if (! is_null($te_complemento)) {
                if (strlen($te_complemento) == 0) {
                    $te_complemento = '';
                }
            } else {
                $te_complemento = '';
            }
            if (! is_null($nm_bairro)) {
                if (strlen($nm_bairro) == 0) {
                    $nm_bairro = '-x-';
                }
            } else {
                $nm_bairro = '-x-';
            }
            
            if (! is_null($nm_cidade)) {
                if (strlen($nm_cidade) == 0) {
                    $nm_cidade = '-x-';
                }
            } else {
                $nm_cidade = '-x-';
            }

            $endereco = "$tp_endereco $endereco,$nu_endereco, $te_complemento, $nm_bairro, $nm_cidade,$cd_estado $cd_cep";
            $cline = "<tr data-idagenciaxmunicipio='$id_agenciaxmunicipio' data-cdestado='$cd_estado' data-nmmuni='$nm_cidade'>";
            $cline .= "<td scope='row' style='width:2.5rem;text-align:center;'><input type='radio' name='opt-agencia' id='opt-agencia-$id_agencia' value='$id_agencia' onClick='updateForm(event);'></td>";
            $cline.= "<td scope='col' class='col-md-1'>$cd_agencia</td>";
            $cline.= "<td scope='col' class='col-md-3'>$nu_cnpj</td>";
            $cline.= "<td scope='col' class='col-md-2'>$nu_ccm</td>";
            $cline.= "<td scope='col' class='col-md-3'>$nu_ccusto</td>";
            $cline.= "<td scope='col' class='col-md-5'>$endereco</td>";
            $cline.= "<td scope='col' class='col-md-4' style='display:none;'>$nm_sistema</td>";
            $cline.= "<td scope='col' class='col-md-5' style='display:none;'>$link_p</td>";
            $cline.= "<td scope='col' class='col-md-3' style='display:none;'>$user_p</td>";
            $cline.= "<td scope='col' class='col-md-3' style='display:none;'>$pwd_p</td>";
            $cline.= "<td scope='col' class='col-md-4' style='display:none;'>$link_t</td>";
            $cline.= "<td scope='col' class='col-md-3' style='display:none;'>$user_t</td>";
            $cline.= "<td scope='col' class='col-md-3' style='display:none;'>$pwd_t</td>";
            $cline.= "<td scope='col' class='col-md-4' style='display:none;'>$nm_contato</td>";
            $cline.= "<td scope='col' class='col-md-5' style='display:none;'>$te_email</td>";
            $cline.= "<td scope='col' class='col-md-3' style='display:none;'>$nu_telefone</td>";
            $cline.= "<td scope='col' class='col-md-4' style='display:none;'>$nm_contato_suporte</td>";
            $cline.= "<td scope='col' class='col-md-5' style='display:none;'>$te_email_suporte</td>";
            $cline.= "<td scope='col' class='col-md-3' style='display:none;'>$nu_telefone_suporte</td>";

            $cline.= "</tr>";
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
    } else {
        $vresult = array("Error" => '404', "Message" => "Nenhum registro encontrado");
    }
    echo json_encode($vresult);
?>