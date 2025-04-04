$(document).ready(function() {

    // rotina padrão para fixar head da tabela na tela
    function tableFixHead(evt) {
        const el = evt.currentTarget,
          sT = el.scrollTop;
        el.querySelectorAll("thead th").forEach(th =>
          th.style.top=`0px`
        );
    }
      
    document.querySelectorAll(".tableFixHead").forEach(el =>
       el.addEventListener("scroll", tableFixHead)
    );
    
    let v_order = {
        'cd_estado': '',
        'nm_muni': 'ASC',
        'cd_status': '',
        'responsavel':'',
        'total_ISS': ''
    }
    
    let total_records = $("input#total_rec");
    var table = $("table.tb_municipios");
    var tbody = table.find("#tbody_municipios");
    let thead = table.children('thead');
    let id_user = $(".id_user").val();
    let tp_user = $(".tp_user").val();
    let btFiltrar = $("#bt_filter");

    var tfoot = table.find("tfoot");
    let select_rows = tfoot.children().find("select#sel-linhas").val();
    let curr_page = tfoot.children().find("input#sel-page").val();
    let btmenu_opt = $(document.querySelectorAll("img[id^='show-']"));
    
    var waiting = $("div.waiting");
    var generic = $("div.generic");

    let img_spin = waiting.find("img");
    var parms = {
        id_img: "spin"
    }
    $.post("modules/load_images.php", parms, (data) => {
        var resp = JSON.parse(data);
        var blob = resp.source;
        img_spin.attr("src", "");
        img_spin.attr("src", blob);
    });
    
    parms = {
        id_img: "bt_menu"
    }
    $.post("modules/load_images.php", parms, (data) => {
        var resp = JSON.parse(data);
        var blob = resp.source;
        btmenu_opt.each ((ix, elem) => {
            var obj = $(elem);
            obj.attr("src", "");
            obj.attr("src", blob);
        });
    });

    // create invisible images

    let img_types = $(document.querySelectorAll("img[id^='type-']"));
    let img_fields = $(document.querySelectorAll("img[id^='field-']"));

    parms = {
        id_img: "check_on"
    }
    $.post("modules/load_images.php", parms, (data) => {
        var resp = JSON.parse(data);
        var blob = resp.source;
        img_types.each ((ix, elem) => {
            var obj = $(elem);
            obj.attr("src", "");
            obj.attr("src", blob);
            obj.data("turn", "");
            obj.data("turn", "on");
        });
    
        img_fields.each ((ix, elem) => {
            var obj = $(elem);
            obj.attr("src", "");
            obj.attr("src", blob);
            obj.data("turn", "");
            obj.data("turn", "on");
        });
    });
    var img_check_on = $("#img_check_on");
    let check_on = img_check_on.attr('src') || img_check_on.prop('src');

    var img_check_off = $("#img_check_off");
    let check_off = img_check_off.attr('src') || img_check_off.prop('src');

    var img_radio_on = $("#img_radio_on");
    let radio_on = img_radio_on.attr('src') || img_radio_on.prop('src');

    var img_radio_off = $("#img_radio_off");
    let radio_off = img_radio_off.attr('src') || img_radio_off.prop('src');

    var img_noorder = $("#img_noorder");
    let noorder = img_noorder.attr('src') || img_noorder.prop('src');

    var img_fullorder = $("#img_fullorder");
    let fullorder = img_fullorder.attr('src') || img_fullorder.prop('src');

    var img_pageorder = $("#img_pageorder");
    let pageorder = img_pageorder.attr('src') || img_pageorder.prop('src');

    // paginacao
    let img_first = tfoot.children().find("img#first");
    let img_prev = tfoot.children().find("img#prev");
    let img_next = tfoot.children().find("img#next");
    let img_last = tfoot.children().find("img#last");
    parms = {
        id_img: "vassoura"
    }

    $.post("modules/load_images.php", parms, (data) => {
        var resp = JSON.parse(data);
        var blob = resp.source;
        $("#limpar_pesquisa").attr("src", "");
        $("#limpar_pesquisa").attr("src", blob);
    });
   
    parms = {
        id_img: "first"
    }
    $.post("modules/load_images.php", parms, (data) => {
        var resp = JSON.parse(data);
        var blob = resp.source;
        img_first.attr("src", "");
        img_first.attr("src", blob);
    });

    parms = {
        id_img: "prev"
    }
    $.post("modules/load_images.php", parms, (data) => {
        var resp = JSON.parse(data);
        var blob = resp.source;
        img_prev.attr("src", "");
        img_prev.attr("src", blob);
    });

    parms = {
        id_img: "next"
    }
    $.post("modules/load_images.php", parms, (data) => {
        var resp = JSON.parse(data);
        var blob = resp.source;
        img_next.attr("src", "");
        img_next.attr("src", blob);
    });

    parms = {
        id_img: "last"
    }
    $.post("modules/load_images.php", parms, (data) => {
        var resp = JSON.parse(data);
        var blob = resp.source;
        img_last.attr("src", "");
        img_last.attr("src", blob);
    });
    var npage = 1;
    var nrows = 20;
      
    /*
        pega a página corrente
    */
    function getCurrentPage() {
        var npage = parseInt($("#sel-page").val()); // pagina corrente
        // verifica número de linhas por página
        return npage;
    }

    /*
    seta pagina corrente
    */

    function setCurrentPage(npage) {
        if (npage !== undefined) {
            var cpage = npage.toString();
            $("#sel-page").val(cpage);       
        } else {
            $("#sel-page").val("1");       
        }
    }
    /*
        retorna o numero de linhas da tela
    */
    function getnRows() {
        let sel_linhas = tfoot.children().find("#sel-linhas > option:selected");
        nrows = sel_linhas.val();
        nrows = parseInt(nrows);
        return nrows;
    }


    function getType(pObject) {
        return typeof pObject;
    }
    /*
        realiza a paginação
        parametros: npage-número da página 
        nrows - quantidade de linhas por tela
        v_filter - vetor com filtros ou string para filtrar todos os campos
        v_order - dicionario de ordenação com a seguinte estrutura: chave - nome do campo, tipo (ASC ou DESC)
        v_columns - dicionario com as colunas que serão visiveis na tela
    */
    function paginar() {
        waiting.css("display", "");
        waiting.css("display", "block");
        let v_filter = getFiltros();
    
        $.ajax({
            url: "modules/table_acessos.php",
            type: "POST",
            data: {
                id_user: id_user,
                tp_user: tp_user,
                page: getCurrentPage(),
                num_rows: getnRows(),
                v_order: JSON.stringify(v_order),
                v_filter: JSON.stringify(v_filter)
            },
            success: function(data) {
                var resp = JSON.parse(data);
                var error = resp.Error;
                var message = resp.Message;
                var data = JSON.parse(resp.Data);
                var ntotal = parseInt(resp.Total_Records);
                waiting.css("display",'');
                waiting.css("display",'none');
                if (error == '0') {
                    total_records.val(ntotal);
                    if (ntotal > 0) {
                        tbody.empty();
                        tbody.html('');
                        data.forEach((line) => {
                            if (line) {
                                var obj_line = $(line);
                                tbody.append(obj_line);
                            }
                        });
                        
                    } else {
                        var smessage = "<tr><td colspan='16' class='text-center fn-bold'>Não encontrou registros no filtro</td></tr>";
                        tbody.html('');
                        tbody.html(smessage);
                    }
                } else {
                    var smessage = "<tr><td colspan='16' class='text-center fn-bold'>Erro ao carregar dados: " + message + "</td></tr>";
                    tbody.html('');
                    tbody.html(smessage);
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                waiting.css("display", "none");
                waiting.css("display", "block");
                console.error("Erro ao carregar dados: " + textStatus + ", " + errorThrown);
            }
        })
    }

    /*
        evento para aplicar filtro e ordenação
    */

    function getFiltros () {
        var div_filtros = $(".card-body.filtros");
        var dt_compet = div_filtros.children().find("#sel_competencias > option:selected").val();
        var id_municipio = div_filtros.children().find("#sel_municipios > option:selected").val();
        var cd_statusmuni = div_filtros.children().find("#sel_statusmuni > option:selected").val();
        var id_user = div_filtros.children().find("#sel_users > option:selected").val();
        var tp_user = div_filtros.children().find("#sel_users > option:selected").data("tp_user") || 
                      div_filtros.children().find("#sel_users > option:selected").prop("data-tp_user");

        var vLista={};
        var breturn = false;
        if (dt_compet !== undefined) {
            if (dt_compet.length > 0 || dt_compet !== null) {
                dt_compet = dt_compet.split('/').reverse().join('-');
                vLista['dt_compet'] = dt_compet;
                breturn = true;
            } 
        } 
        if (id_user !== undefined) {
            if (id_user.length > 0 || id_user !== null) {
                if (id_user !== '0') {
                    vLista['id_user'] = parseInt(id_user);
                    vLista['tp_user'] = tp_user;
                    breturn = true;
                }
            }
        }

        if (id_municipio !== undefined) {
            if (id_municipio.length > 0 || id_municipio !== null) {
                if (id_municipio !== '0') {
                    vLista['id_muni'] = parseInt(id_municipio);
                    breturn = true;
                }
            }
        }
        if (cd_statusmuni!== undefined) {
            if (cd_statusmuni.length > 0 || cd_statusmuni!== null) {
                if (cd_statusmuni !== '0') {
                    vLista['cd_status'] = cd_statusmuni;
                    breturn = true;
                }
            }
        }
        return breturn ? vLista : null;
    }

    btFiltrar.on("click",(evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        paginar();
    });
    
  
    tfoot.children().find('img').on("click",(evt) => {
        var id = $(evt.target).attr("id");
        var npage = getCurrentPage();
        if (id == "first" && npage != 1) {
            npage = 1
        } else if (id == "prev" && npage != 1) {
            npage -= 1;
        } else {
            var nrows = getnRows();
            var nrecords = parseInt($("#total_rec").val());
            var nlastpage = Math.ceil(nrecords/nrows);
            if (id == "next" && ((npage +1) <= nlastpage)) {
                npage += 1;
            } else if (id=="last" && npage != nlastpage) {
                npage = nlastpage;
            } else {
                npage == -1;
            }
        }

        if (npage != -1) {
            setCurrentPage(npage);
            paginar();
        }
    });

    /*
        um click verifica se a seleção, se for seleciona todos os registros da página
        ordenação: 
        1) verifica src se for ordena_tudo inverte a seleção e ordena_tudo e troca o icone para ordena pagina
        2) verifica src se for ordena_pagina ordena tudo na direção e troca o icone para ordena_tudo.
    */
    
    
    thead.on("click", "img", (evt) => {
        var obj = $(evt.target);
        var id = obj.attr('id');
        
        if (id == 'todos') {
            var field = obj.data("mark")
            var src = (field == 'radio_off') ? radio_on : radio_off;
            var pstatus = (field == 'radio_off') ? "radio_on" : "radio_off";
            var vimages = $('.tb_services > tbody > tr').children   ().find("img#marcar").get();
            obj.data("mark", "");
            obj.data("mark", pstatus);
            obj.attr("src","");
            obj.attr("src", src);
            $(vimages).each ((ix, elem) => {
                var obj_img = $(elem);
                obj_img.attr("src","");
                obj_img.attr("src", src);
                obj_img.data("mark", "");
                obj_img.data("mark", pstatus);
                obj.data("mark",pstatus);
                obj.data("mark", pstatus);
            });
            return false;
        } else {
            var status = obj.data("order");

            if (status == "fullorder") {
                if (obj.hasClass("rotate-180")) { //
                    obj.removeClass("rotate-180");
                    if (obj.data("inv") == "1") {
                        src = pageorder;
                        obj.attr("src", "");
                        obj.attr("src", src);
                        obj.data("inv", "");
                        obj.data("inv", "0");
                        obj.data("order", "");
                        obj.data("order", "pageorder");
                    }
                } else {
                    obj.data("inv","1");
                    obj.addClass("rotate-180");
                }
            } else {
                if (status == "pageorder") {
                    if (obj.hasClass("rotate-180")) { //
                        obj.removeClass("rotate-180");
                        if (obj.data("inv") == "1") {
                            src = noorder;
                            obj.attr("src", "");
                            obj.attr("src", src);
                            obj.data("inv", "");
                            obj.data("inv", "0");
                            obj.data("order", "");
                            obj.data("order", "noorder");
                        }
                    } else {
                        obj.data("inv","");
                        obj.data("inv","1");
                        obj.addClass("rotate-180");
                    }
                } else {
                    if (obj.data("inv") == "0" || obj.data("inv") == undefined) {
                        src = fullorder;
                        obj.data("order", "");
                        obj.data("order", "fullorder");
                        obj.attr("src","");
                        obj.attr("src", src);
                    } 
                }
            }
        }
    });
    paginar();
    // include dynamically a js copied from Microsoft Co-pilot
    function loadScriptOnce(url) {
        // Check if the script is already present
        if (!document.querySelector(`script[src="${url}"]`)) {
            var script = document.createElement('script');
            script.src = url;
            script.type = 'text/javascript';
            document.head.appendChild(script);
            script.onload = function() {};
        } else {
            $.getScript(url,()=> {});
        }
    }

   
    function isTbodyLoaded(tbodyId, expectedRowCount, callback) {
        var tbody = document.querySelector(`#${tbodyId}`);
        if (!tbody) {
            return;
        }
    
        var checkInterval = setInterval(function() {
            var rows = tbody.querySelectorAll('tr');
            if (rows.length >= expectedRowCount) { // Check if the number of rows matches the expected count
                clearInterval(checkInterval); // Stop checking
                callback(); // Execute the callback function (e.g., load script)
            }
        }, 100); // Check every 100ms
    }
    
    // Example usage: Replace 'yourTbodyId' with your <tbody> ID and set the expected row count
    isTbodyLoaded('tbody_municipios', getnRows(), function() {
        loadScriptOnce('scripts/load_virecolhimentos.js');
    });


    //J
    $(document).on("change", "#tbody-recolhimentos", function () {
        //need to be state
        var table = $(this);
        
        var inputSelected = $(".item")
        var headTable_idagencia = $(".headitem").data('id_agencia')
        var headTable_idsistema = $(".headitem").data('id_sistema')      
       
        // console.log("alo", headTable_idagencia + '---' + headTable_idsistema)
        
        let payload = {
            id_agencia: headTable_idagencia, 
            id_sistem: headTable_idsistema
        }    
        
    
        //J
        $.ajax({
            url: 'modules/ler_agenxmuni.php',
            type: 'POST',
            data: payload,
            success: function (data) {
                var resp = JSON.parse(data);
                var error = resp.Error;
                var response = resp.Data;
    
                console.log("a",response)
                if (response.length > 0) { 

                    $('#municipio_link').attr('href', response[0].te_link );

                    console.log("b",response[0].nm_contato)
    
                    let nm_contato=response[0].nm_contato ?? 'por preencher'
                    let te_email=response[0].te_email ?? 'por preencher'
                    let nu_telefone=response[0].nu_ddd && response[0].nu_telefone ? response[0].nu_ddd+'-'+response[0].nu_telefone: 'por preencher'
                    
                    let nm_contato_suporte = response[0].nm_contato_suporte ?? 'por preencher'
                    let te_email_suporte=response[0].te_email_suporte ?? 'por preencher'
                    let nu_telefone_suporte= response[0].nu_ddd_suporte && response[0].nu_telefone_suporte ?response[0].nu_ddd_suporte+'-'+response[0].nu_telefone_suporte : 'por preencher'
    
                    $('#nm_contato').val(nm_contato);
                    $('#te_email').val(te_email);
                    $('#te_telefone').val(nu_telefone);
    
                    $('#nm_contato-sup').val(nm_contato_suporte);
                    $('#te_email-sup').val(te_email_suporte);
                    $('#te_telefone-sup').val(nu_telefone_suporte);
    
                } else {
                    console.error(error);
                }
    
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                console.error('Erro ao carregar o modal:', error);
            }
        });
        
    });
});


function showRecolhimento(event) {
    console.log(event.target)
    var div_filtros = $(document.querySelector(".card-body.filtros"));
    var dt_compet = div_filtros.children().find("#sel_competencias > option:selected").val();
    let id_user = $(document.querySelector(".id_user")).val();
    let tp_user = $(document.querySelector(".tp_user")).val();
    let row = $(event.target).closest('tr');
    let id_muni = row.prop('data-idmuni') || row.data('idmuni');
    let vi_generic = $('.generic');
    
    var nm_muni = row.children("td:eq('1')").text();

    let payload = {
        id_user: id_user, 
        tp_user: tp_user, 
        id_muni: id_muni,
        dt_compet: dt_compet,
        nm_muni: nm_muni
    }    
    
    //J
    $.ajax({
        url: 'views/vi_recolhimento.php',
        type: 'POST',
        data: payload,
        success: function (data) {

            //console.log("html", data)
            
            buscarUser(payload.id_muni).then((user) => {
                console.log("promise", user)
            })

            $(vi_generic).html('');
            let modalExistente = $('#modal-recolhimento');
            if(modalExistente.length) {
                modalExistente.remove();
            }
            $(vi_generic).html(data);
    
            let modalElement = document.getElementById('modal-recolhimento');
            if (modalElement) {
                let modalInstance = new bootstrap.Modal(modalElement);
                modalInstance.show();

                modalElement.addEventListener('hidden.bs.modal', function() {
                    /* modificação para limpeza da div */
                    $(".modal-backdrop").remove()
                    $("body").removeClass(".modal-page")
                    $("body").removeClass(".modal-open")
                    $("body").removeClass(".modal-show")
                    $("body").removeClass(".modal-dialog-scrollable")
                    $("body").removeClass(".modal-dialog-centered")
                    $("body").removeClass(".modal-static")
                    $("body").removeClass(".modal-dialog-centered")
                    $("body").css("padding", 0)
                    $(vi_generic).html('');
                });
            } else {
                console.error('Modal não encontrado após a injeção do HTML.');
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
            console.error('Erro ao carregar o modal:', error);
        }
    });

    //J
    function buscarUser(userId) {
        return new Promise((response, reject) => {
            $.ajax({
                url: 'modules/ler_userbyid.php',
                type: 'POST',
                data: { id_user: userId },
                success: function (data) {
                    var resp = JSON.parse(data);
                    var error = resp.Error;
                    var value = resp.Data[0];
                    
                    response(value);                 
                       
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    reject(error);
                }
            });
            
        })
    }
}

