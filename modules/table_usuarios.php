<?php
    include_once "../classes/cls_usuarios.php";
    $id_user = $_GET["id_user"];
    $ousers = new cls_usuarios($id_user);
    if (! isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET["page"];
    }

    if (! isset($_GET['cfilter'])) {
        $filter_fields = NULL;
    } else {
        $filter_fields = $_GET["cfilter"];
        if (strpos($filter_fields, "{") !== FALSE)
            $filter_fields = json_decode(stripslashes($filter_fields), true);
    }

    if (! isset($_GET['vsorter'])) {
        $sort_fields = NULL;
    } else {
        $sort_fields = json_decode($_GET["vsorter"], true);
    }
    

    if (isset($_GET['num_rows'])) {
        $nrows = $_GET['num_rows'];
        $ousers->setLimitPage($nrows);      
    }
    
    if (isset ($_GET['columns'])) {
        $vcolumns = json_decode($_GET['columns'], true);
    } else {
        $vcolumns = Array("te_tipo" => True, "nome" => True, "nm_area" => True, "te_email" => True, "cs_admin" => True,"cs_conferente1"=>True,"cs_online"=>True);
    }

    $list_users = $ousers->getRows($page, $filter_fields, $sort_fields);
    if ($filter_fields) {
        $ntotalrecords = $ousers->getTotalFiltered();
    } else {
        $ntotalrecords = $ousers->getTotalRecords();
    }
    $varray = $ousers->getNivel($id_user);
    $cs_admin = $varray["cs_admin"];
    $cs_system = $varray['sistema'];
    $cs_confe = $varray['conferente'];
    $id_area = $varray["id_area"];
    $lines = array();

    foreach ($list_users as $row) {
        $id_user = $row["id_user"];
        $id_area_usu = $row["id_area"];
        $nome    = $row["nome"];
        $cd_tipo = $row['cd_tipo'];
        $te_tipo = $row['te_tipo'];
        $nm_area = $row["nm_area"];
        $email = $row['email'];
        $cs_admin = $row['cs_admin'];
        $cs_system = $row["cs_system"];
        $cs_conferente = $row["cs_conferente1"];
        $cs_online = $row["cs_online"];
        if ($cs_admin == "N") continue;
        if ($cs_admin == 'S' and $id_area != $id_area_usu) continue;

        $cline = "";
        $cline .= "<tr scope='row'>";
        $cline .= "<td scope='col' data-user='$id_user' data-nivel='$nivel-$cs_admin'style='display:none'>";
        $cline .= "<td scope='col' style='white-space:nowrap;'>";
        $cline .= "<img src='assets/images/nochecked.png' width='19vw' alt='marcar' id='marcar' title='Marcar linha' style='cursor:pointer;'/>&nbsp;";
        if ($nivel == "BR" or ($nival == 'BRP1' and $cs_admin == 'S') or $cs_system=='S') {
            $cline .= "<img src='assets/images/log.png' width='19vw' alt='log' id='log' title='arquivo log' style='cursor:pointer;'/>&nbsp;";
        }
        $cline .= "<img src='assets/images/estimate.png' width='19vw' alt='dados usuários' id='form_user' title ='editar usuário' style='cursor:pointer;'/>&nbsp;";
        $cline .= "<img src='assets/images/lixeira.png' width='19vw' alt='deletar' data-record='$id' id='deletar'  title='Excluir registro' style='cursor:pointer;'/>"; 
        $cline .= "</td>";
        
        $cs_tipo  = $vcolumns["te_tipo"];
        $cs_nome  = $vcolumns["nome"];
        $cs_area  = $vcolumns["nm_area"];
        $cs_email = $vcolumns['te_email'];
        $cs_eadmin = $vcolumns['cs_admin'];
        $cs_econfe = $vcolumns['cs_conferente1'];
        $cs_eonline = $vcolumns['cs_online'];
        if ($cs_nome) {
            $cline .= "<td scope='col'><label class='text-white text-center'>$nome</label></td>";
            $nwidth += 8.3;
        } 
        if ($cs_email) {
            $cline .= "<td scope='col'><label class='text-white float-start'>$email</td>";
            $nwidth += 8.1;
        } 
        if ($cs_tipo) {
            $cline .= "<td scope='col' data-cdtipo='$cd_tipo'><label class='text-white text-center'>$te_tipo</label></td>";
            $nwidth += 8;
        } 

        if ($cs_area) {
            $cline .= "<td scope='col'><label class='text-white text-center'>$nm_area</label></td>";
            $nwidth += 8;
        } 
        
        if ($cs_eadmin) {
            $cline .= "<td scope='col'><label class='text-white float-start'>$cs_admin</td>";
            $nwidth += 16.5;
        } 
        if ($cs_econfe) {
            $cline .= "<td scope='col'><label class='text-white float-start'>$cs_confe</td>";
            $nwidth += 16.5;
        } 
        if ($cs_eonline) {
            $cline .= "<td scope='col'><label class='text-white float-start'>$cs_online</td>";
            $nwidth += 16.5;
        }
        $nwidth = 100-$nwidth;
        $cline .= "<td scope='col'>&nbsp;</td>"; 
        $cline .= "</tr>";
        array_push($lines, $cline);
    }

    array_unshift($lines,  $ntotalrecords);
    echo json_encode($lines);
?>