$(document).ready(function() {
    // rotina padrão para fixar head da tabela na tela
    function tableFixHead(evt) {
        const el = evt.currentTarget,
          sT = el.scrollTop;
        el.querySelectorAll("thead th").forEach(th =>
          th.style.transform = `translateY(${sT}px)`
        );
    }
      
    document.querySelectorAll(".tableFixHead").forEach(el =>
       el.addEventListener("scroll", tableFixHead)
    );
    
    let mudou_header = true;
    var tbody = $("#tbody_usuarios");
    var table = $("table.tb_usuarios");
    let thead = table.children('thead');
    var id_user = table.data('user');
    var tfoot = table.find("tfoot");
    let select_rows = tfoot.children().find("select#sel-linhas");
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
      
    var dict_fields = {
        'ID': false,
        'ACTIONS': true,
        'nome': true,
        'te_email': true,
        'te_tipo': true,
        'nm_area': true,
        'cs_admin': true,
        'cs_conferente1': true,
        'cs_online': true,
        9: false
    };
    var dict_sort = { "nome": "ASC"};
    var list_columns = getColumnVisible();
    MountHeader(list_columns);
    paginar(npage,nrows,null,dict_sort,dict_fields);
   /*
        retorna todos os campos ou vetor de campos que serão realizados a pesquisa 
        dicionario com a seguinte estrutura 
        chave nome do campo, conteudo = pesquisa
    */
    function getParms_Pesquisa() {
        var conteudo = $("#pesquisa").val();
        var vPesquisa;
        if (conteudo.length > 0) {
            var todos_img = $(".table.tb-select-types>tbody>tr>td:eq(0)").children("img");
            var check_todos = (todos_img.attr("src").indexOf("on") != -1);
            if (!check_todos) {
                
                vPesquisa = {}
                img_types.filter( (ix, elem) => {
                    if (ix > 0) {
                        var img = $(elem);
                        if (img.data("status") == 'on') {
                            var id = img.attr("id");
                            var field = id.replace("type-","");
                            vPesquisa[field] = conteudo;
                            return img;
                        }
                    }
                });
                if (vPesquisa.length == 0) {
                    vPesquisa=null;
                }
            } else {
                vPesquisa = conteudo;
            }
        } else {
            vPesquisa = null;
        }
        return vPesquisa;
    }
    /*
    retorna o vetor de ordenação de campos
    */
    function getParms_Ordena() {
        // configura ordenacao
       
        var tr_imgordena = thead.find("img#ordenar");
        
        var v_order = {};
        var keys = Object.keys(dict_fields);
        keys.shift();
        
        var tbfields = keys;
    
        tr_imgordena.each((ix, elem) => {
            var status = $(elem).data("turn");
            if (status == 'off')  {
                var field = tbfields[ix];
                var order = 'DESC';
                if ($(elem).hasClass("rotate-180")) {
                    order='ASC';
                }
                v_order[field] = order;
            }
        });

        if (v_order.length == 0) {
            v_order = null;
        }
        return v_order;
    }
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
    function paginar(npage, nrows, v_filter, v_order, v_columns) {
        if (v_filter) {
            if (getType(v_filter) !== 'string') {
                v_filter = JSON.stringify(v_filter);
            }
        }
        if (v_order) {
            v_order = JSON.stringify(v_order);
        }
        if (v_columns) {
            v_columns = JSON.stringify(v_columns);
        }
        waiting.css("display", "");
        waiting.css("display", "block");
        var totrec = $("#total_rec");
        var parms = { id_user: id_user, 
                  page: npage,
                  type: 1,
                  cfilter: v_filter,
                  vsorter: v_order,
                  num_rows: nrows,
                  columns: v_columns };
        if (mudou_header) {
            tbody.html("");
            mudou_header=false;
        }
        $.get("modules/table_usuarios.php", parms, function(data) {
            
            var resp = JSON.parse(data);
            var cresp= "";
            for (var i = 0; i < resp.length; i++) {
                if (i == 0) {
                    $ntotal = resp[i];
                    totrec.val($ntotal);
                } else {
                    cresp += resp[i];
                }
            }
            tbody.html();   
            tbody.html(cresp);

            tbody.children().on("click", function(e) {
                // rotinas de clicks na tabela
                var typeElement = e.target.tagName;
                var td_row = $(this).closest("tr");
                var td_user = td_row.find("td:eq(0)");
                var id_current = td_user.data("user");
                var obj = $(e.target);
                
                if (typeElement==='IMG') {

                    var image_id = $(e.target).attr("id");
                    
                    switch (image_id) {
                        case 'marcar': 
                            var status =obj.data("mark");
                            var src = obj.attr("src");
                            src = "";
                            if (status.indexOf("off") > 0) {
                                src = radio_on;
                                status="radio_on";
                            } else {
                                status="radio_off";
                                src = radio_off;
                            }
                            obj.attr("src", "");
                            obj.attr("src", src);
                            obj.data("mark", "");
                            obj.data("mark",status);
                            
                            // conta quantas estão marcadas 
                            var vimages = $('.tb_usuarios > tbody > tr').children().find("img#marcar").get();
                            var vchecks = $(vimages).filter((ix, elem) => {return $(elem).data("mark") == 'radio_on'; });
                            var btodos = vimages.length == vchecks.length;
                            var img_checktodos = $(".tb_usuarios > thead > tr").children().find("img#todos");
                            if (btodos) {
                                img_checktodos.data("mark", "radio_on");
                                img_checktodos.attr("src", "");
                                img_checktodos.attr("src", radio_on);
                            } else {
                                img_checktodos.data("mark", "radio_off");
                                img_checktodos.attr("src", "");
                                img_checktodos.attr("src", radio_off);
                            }
                            break;

                        case 'log':
                            generic.html("");
                            window.localStorage.setItem("id_user", id_current);
                            window.localStorage.setItem("module", "usuarios");
                            $.post("views/form_logs.html", function(data) {
                                generic.html(data);
                                var modal = generic.find('.modal');
                                modal.modal('show');
                                
                                modal.on("hidden.bs.modal", (evt) => {
                                    evt.preventDefault();
                                    close_modal(modal);
                                    window.localStorage.removeItem("id_user");
                                    window.localStorage.removeItem("module");
                                });
                            });
                            
                            break;
                        case "deletar":
                            generic.html("");
                            window.localStorage.setItem("id_user", id_current);
                            window.localStorage.setItem("module", "usuarios");
                            $.post("views/form_delete.html", function(data) {
                                generic.html(data);
                                var modal = generic.find(".modal");
                                modal.modal("show");
                                modal.on("hidden.bs.modal", (evt) => {
                                    evt.preventDefault();
                                    openmodal=true;
                                    window.localStorage.removeItem("id_user");
                                    window.localStorage.removeItem("module");
                                    close_modal(modal);
                                });
                            });
                        
                   
                    }
                } else {
                    var index = $(e.target).closest("td").index();
                    var row_index = $(this).index();
                    var coords = { row : row_index, column: index };
                    generic.html("");
                    switch (index) {
                        case 0: // altera dados do usuario
                            $.post('views/form_usuarios.php', {id_user: id_current, coords: JSON.stringify(coords)}, (data) => {
                                generic.html(data);
                                var modal = generic.find(".modal");
                                modal.modal('show');
                                modal.on("hidden.bs.moda", (evt) => {
                                    close_modal(modal);
                                });
                            });
                            
                            break;
                    }
                } 
            });
            tfoot.css("display", "block");
            waiting.css("display", "");
            waiting.css("display", "none");
          
            function close_modal(modal) {
                $('.modal-backdrop').remove();
                generic.remove(modal);
                generic.html("");
                generic.html("");
            }
        });
    }
    
  
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
            $("#aplicar").click();
        }
    });

    /*
        inverte a setinha da imagem para exibir o modal de selecao
        de campos
    */

    $("#show-seltypes").on("click", (evt) => {
        var obj = $(evt.target);
        if (obj.hasClass("rotate-90")) {
            obj.removeClass("rotate-90");
            obj.attr("class", "");
        } 
    });

    $("#show-selfields").on("click", (evt) => {
        var obj = $(evt.target);
        if (obj.hasClass("rotate-90")) {
            obj.removeClass("rotate-90");
            obj.attr("class", "");
        } 
    });

    
    /*
        fechar a janela de selecao de campos e nao executa nada
    */
    $(".modal.fade#show_fields").on ("hidden.bs.modal", (evt) => {
        $("#show-selfields").addClass("rotate-90");
    });
   
    /*
        fechar a janela de selecao de campos de tipo de pesquisa
    */
    $(".modal.fade#show_types").on ("hidden.bs.modal", (evt) => {
       $("#show-seltypes").addClass("rotate-90");
    });

    /*
        rotina de seleção de numero
        de linhas por página
    */

    select_rows.on("change", (e) => {
        $("#aplicar").click();
    });

    /*
        rotina de selação de campos
        na tela.
    */

    function getColumnVisible() {
       
        var columns = img_fields.filter((ix,elem) => {return $(elem).attr("id").indexOf("todos") == -1} );
        columns.each ((ix,elem) => {
            var id = $(elem).attr("id");
            var field = id.replace("field-", "");
            var visible = $(elem).data("turn") === "on" || $(elem).data("turn") === undefined;
            dict_fields[field] = visible;
        });
        return dict_fields;
    }

    function MountHeader (list_columns) {
        
        if (mudou_header) {
            var v_columns = JSON.stringify(list_columns);
            thead.html("");
            $.get("modules/load_theadusers.php", { columns: v_columns}, (data) => {
                var resp = JSON.parse(data);
                thead.html(resp.data);
            })
            mudou_header = false;
        }
       
    }

    $("#aplicar").on("click", (evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        
        var list_columns = getColumnVisible();
        MountHeader(list_columns);
      
        var page = getCurrentPage();
        var v_filter = getParms_Pesquisa();
        var v_order = getParms_Ordena();
        var nrows = getnRows();
        paginar(page,nrows,v_filter,v_order,list_columns);
    });

    $(".modal.fade#show_fields").children().on("click", "img", (evt) => {
        var id = $(evt.target).attr("id");
        var status = $(evt.target).data("turn");
        mudou_header=true;
        src = (status == 'off') ? check_on : check_off;
        status = (status == 'off') ? 'on' : 'off';

        if (id.indexOf("todos") > 0) {
            img_fields.each((ix,elem) => {
                $(elem).data("turn", "");
                $(elem).data("turn", status);
                $(elem).attr("src", "");
                $(elem).attr("src", src);
            });
        } else {
            $(evt.target).attr("src","");
            $(evt.target).attr("src",src);
            $(evt.target).data("turn","");
            $(evt.target).data("turn",status);

            var ncount = img_fields.filter((ix,elem) => {return ($(elem).attr("id").indexOf("todos") == -1 && status == 'on')}).length;
            if (ncount == 7 ) {
                status = 'on';
                src = check_on;
            } else {
                status = 'off';
                src = check_off;
            }
            $(".modal.fade#show_fields").children().find("img#field-todos").data("turn","");
            $(".modal.fade#show_fields").children().find("img#field-todos").data("turn",status);
            $(".modal.fade#show_fields").children().find("img#field-todos").attr("src","");
            $(".modal.fade#show_fields").children().find("img#field-todos").attr("src",src);
        }
    });

    $(".modal.fade#show_types").children().on("click", "img", (evt)=> {
        var id = $(evt.target).attr("id");
        var status = $(evt.target).data("turn");

        status = (status == "on") ? "off" : "on";
        var todos_img = $(".table.tb-select-types>tbody>tr>td:eq(0)").children("img");
        
    
        if (id === "type-todos") {
            img_types.each((ix, elem) => {
                $(elem).data("turn","");
                $(elem).data("turn",status);
                var src = (status == "on") ? check_on : check_off;
                $(elem).attr("src","");
                $(elem).attr("src",src);
            });   
        } else {
            $(evt.target).data("turn","");
            $(evt.target).data("turn",status);
            var src = (status== "on") ? check_on: check_off;
            $(evt.target).attr("src", "");
            $(evt.target).attr("src", src);
            var checked = img_types.filter( (ix, elem) => {
                if ($(elem).attr("id") !== 'type-todos') {
                    return ($(elem).data("turn") == "on");
                } else {
                    return false;
                }
            });
            src = check_off;

            if (checked.length == (img_types.length-1)) {
                src = check_on;
            } 
            todos_img.attr("src","");
            todos_img.attr("src",src);
        }
    });

    

 

   
    /*
        aplicar filtro na tabela
    */

    $("#aplicar_pesquisa").on("click",(evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        setCurrentPage(1);
        $("#aplicar").click();
        $("#limpar_pesquisa").css("display", "");
    });

    /*
        limpa o filtro de pesquisa e apaga a vassoura
    */

    $("#limpar_pesquisa").on("click", (evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        $("#pesquisa").val("");
        $("#limpar_pesquisa").css("display", "");
        $("#limpar_pesquisa").css("display", "none");
        setCurrentPage(1);
        $("#aplicar").click();
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
            var vimages = $('.tb_usuarios > tbody > tr').children   ().find("img#marcar").get();
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
        var keys = Object.keys(dict_fields);
        keys.shift();
        keys.pop();
        var list_fields = keys;
        var tr_imgordena = thead.find("img#ordenar");
        var chk_bdo = tr_imgordena.filter((ix, elem) => {
            return ($(elem).attr('src').indexOf("full") !== -1);
        });

        if (chk_bdo.length > 0) {
            var v_order ={};
            var npage = 1;
            var nrows = getnRows();
            var v_filter = getParms_Pesquisa();
            var v_columns = getColumnVisible();
            var v_order = getParms_Ordena();
            paginar (npage,nrows,v_filter,v_order,v_columns)
        } else {
            var vlist_page = tr_imgordena.filter ((ix, elem) => {
                var src = $(elem).attr("src");
                return (src.indexOf("page") != -1);
            });

            if (vlist_page.length > 0) {
                let list_rows = tbody.find("tr");
               
                var columns = {};
               
                vlist_page.each ((ix,elem) => {
                    var order = $(elem).hasClass("rotate-180") ? "ASC" : "DESC";
                    var pos = $(elem).prop("tabindex");
                    field = list_fields[pos];
                    var column_pos = Object.keys(dict_fields).indexOf(field);
                    columns[field] = {'column': column_pos, 'order': order}
                });
                var dict_sort = [];
                for (var i=0; i < list_rows.length; i++) {
                    var cvalue = "";
                    for (const [field, values] of Object.entries(columns)) { 
                        var column_pos = values['column'];
                        var method = values['order'];
                        var column_value = "";
                        switch (column_pos) {
                            case 2: // nome
                                column_value = $(list_rows[i]).find(`td:nth-child(${column_pos})`).text();
                                break;
                            case 3: // e-mail
                                column_value = $(list_rows[i]).find(`td:nth-child(${column_pos})`).text();
                                break;
                            case 4: // tipo
                                column_value = $(list_rows[i]).find(`td:nth-child(${column_pos})`).text();
                                break;
                            case 5: // cs_admin
                                column_value = $(list_rows[i]).find(`td:nth-child(${column_pos})`).text();
                                break;
                            case 6: // cs_conferente
                                column_value = $(list_rows[i]).find(`td:nth-child(${column_pos})`).text();
                                break;
                            case 7: // cs_online
                                column_value = $(list_rows[i]).find(`td:nth-child(${column_pos})`).text();
                                break;
                        }
                        if (column_value === undefined || column_value ==="") {
                                column_value="";
                        }
                        cvalue += `${column_value}|${method}\n`;
                    }
                    dict_sort.push ({'pos': i, 'value': cvalue});
                }
                
                var result = ordernar(dict_sort);
                var list_result = [];
                for (var i = 0; i < result.length; i++) {
                    var pos = result[i]['pos'];
                    var tr = list_rows[pos];
                    list_result.push(tr);
                }
                tbody.html("");
                for (var i = 0; i < list_result.length;i++) {
                    var obj_tr = $(list_result[i]);
                    tbody.append(obj_tr);
                }

                function ordernar(vetor) {
                    return quickSort(vetor, 0, vetor.length-1);
                }

                function quickSort(vetor, ini, max) {
                    var i = ini;
                    var j = max;
                    var p = parseInt(i + (j-i)/2);
                    while (i <= j) {
                        while (1) {
                            var lista_a = (vetor[i]['value']).split("\n");
                            var clinhacommand = ``;
                            var pivot = vetor[p]['value'];
                            var list_pivot = pivot.split("\n");

                            for (var x = 0; x < list_pivot.length-1; x++) {
                                pivot = list_pivot[x];
                                var aux = pivot.split("|");
                                var content_p = aux[0];

                                var aux = (lista_a[x]).split("|");
                                var metodo = aux[1];
                                var content_a = aux[0];
                                
                                if (metodo ==="DESC") {
                                    clinhacommand += `(${content_a} > ${content_p}) && `;
                                } else {
                                    clinhacommand += `(${content_a} < ${content_p}) && `;
                                }
                            }
                            clinhacommand = clinhacommand.substring(0,clinhacommand.lastIndexOf(')')+1); 
                            var resp = eval(clinhacommand);
                            if (resp === false) break;
                            i +=1;
                            if ( i <= j) {
                                continue;
                            } else {
                                break;
                            }
                        }
                        while (1) {
                            var lista_b = (vetor[j]['value']).split("\n");
                        
                            var clinhacommand = ``;
                            var pivot = vetor[p]['value'];
                            var list_pivot = pivot.split("\n");

                            for (var x = 0; x < list_pivot.length-1; x++) {
                                pivot = list_pivot[x];
                                var aux = pivot.split("|");
                                var content_p = aux[0];

                                var aux = (lista_b[x]).split("|");
                                var metodo = aux[1];

                                var content_b = aux[0];
                                if (metodo ==="DESC") {
                                    clinhacommand += `(${content_b} < ${content_p}) && `;
                                } else {
                                    clinhacommand += `(${content_b} > ${content_p}) && `;
                                }
                            }
                            clinhacommand = clinhacommand.substring(0,clinhacommand.lastIndexOf(')')+1); 
                            var resp = eval(clinhacommand);
                            if (resp === false) break;
                            j -=1;
                            if (j >=0) {
                                continue;
                            } else { 
                                break;
                            }
                        }
                        if (i <= j) {
                            vetor = ExchangeElements(vetor, i, j);
                            i += 1;
                            j -= 1;
                        }
                    }
                    if (ini < j) 
                        vetor = quickSort(vetor,ini, j);
                    if (i < max) {
                        vetor = quickSort(vetor,i,max);
                    }
                    return vetor;
                }
                function ExchangeElements (vetor, p1, p2) {
                    var temp = vetor[p1];
                    vetor[p1] = vetor[p2];
                    vetor[p2] = temp;
                    return vetor;
                }
            } else {
                var v_order ={};
                var npage = 1;
                var nrows = getnRows();
                var v_filter = getParms_Pesquisa();
                var v_columns = getColumnVisible();
                var v_order = null;
                paginar (npage,nrows,v_filter,v_order,v_columns)
            }
        }
    });
    
    /*
        selecionar colunas
    */
 
  
    $("#sel-page").on("keyup", (evt) => {
        if (evt) {
            evt.preventDefault();
            evt.stopPropagation();
            var charCode = evt.which || evt.keyCode;
            if (charCode == 13) {
                var npage = $(evt.target).val();
                var v_filter = getParms_Pesquisa();
                var v_order = getParms_Ordena();
                var npage = $(evt.target).val();
                var nrows = getnRows()
                var list_columns = getColumnVisible();
                MountHeader(list_columns);
                paginar(npage,nrows,v_filter,v_order,list_columns);
            } else if (charCode >= 48 && charCode <= 57) {
                return true;
            } else if (charCode == 8 || charCode == 9 || charCode == 35 || charCode==36 || charCode == 46 || charCode == 45) {
                return true;
            }
        }
        return false;
    });
    $("#sel-page").on("focus", (evt) => {
        var obj = $(evt.target);
        obj.val("");
        return true;
    });

});
