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

    var tbody = $("#tbody_aliquotas");
    var table = $("table.tb_aliquotas");
    let thead = $("table.tb_aliquotas > thead")
    var id_user = table.data('user');
    var tfoot = table.find("tfoot");
    
    let select_rows = tfoot.children().find("select#sel-linhas");
    
    let total_rec = $("#total_rec");
    var waiting = $("div.waiting");
    var generic = $("div.generic");
    var npage = 1;
    var nrows = 20;
    var dict_fields = {
        'id_servicoxgrupo': false,
        'ACTIONS': true,
        'Cd_UF': true,
        'Nm_Muni': true,
        'te_vigencia': true,
        'Cd_Grupo': true,
        'Te_Grupo': true,
        'cd_servicofederal': true,
        'te_servicofederal': true,
        'cd_servicomuni': true,
        'te_servicomuni': true,
        'vl_percIR': true,
        'vl_percPCC': true,
        'vl_percINSS': true,
        'vl_percISS': true,
        'vl_percISSBI': true,
        'cs_depara': true,
        17: true
    };
    
    paginar(npage,nrows,null,null,dict_fields);
   /*
        retorna todos os campos ou vetor de campos que serão realizados a pesquisa 
        dicionario com a seguinte estrutura 
        chave nome do campo, conteudo = pesquisa
    */

    function getlistFieldsPesquisa() {
        let modal_pesquisa = $(".modal.fade#show_types");
        let table_pesquisa = modal_pesquisa.children().find("table.tb-select-types > tbody");
        let list_img = table_pesquisa.children().find("img");
        return list_img;
    }

    function getlistFieldsColumn() {
        let modal_columns = $(".modal.fade#show_fields");
        let table_columns = modal_columns.children().find("table.tb-select-fields > tbody");
        let list_img = table_columns.children().find("img");

        return list_img;
    }

    function getParms_Pesquisa() {
        var conteudo = $("#pesquisa").val();
        var vPesquisa;
        if (conteudo.length > 0) {
            var check_todos = false;
            let list_img = getlistFieldsPesquisa();

            var filter_on = list_img.filter((ix, elem) => {
                return $(elem).attr("src").indexOf("on") > 0;
            });
            
            check_todos = (list_img.length === filter_on.length);
           
            if (!check_todos) {
                
                vPesquisa = {}
                list_img.filter( (ix, elem) => {
                    if (ix > 0) {
                        var img = $(elem);
                        if ((img.attr("src")).indexOf("on") != -1) {
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
        if (tr_imgordena.length == 0) {
            return null;
        }
        var v_order = {};
        const tbfields = new Array('Cd_UF',
                                   'Nm_Muni',
                                   'te_vigencia',
                                   'Cd_Grupo',
                                   'Te_Grupo',
                                   'cd_servicofederal',
                                   'te_servicofederal',
                                   'cd_servicomuni',
                                   'te_servicomuni',
                                   'vl_percIR',
                                   'vl_percPCC',
                                   'vl_percINSS',
                                   'vl_percISS',
                                   'vl_percISSBI',
                                   'cs_depara');

        tr_imgordena.each((ix, elem) => {
            var src = $(elem).attr("src");
            if (src.indexOf("no-") == -1)  {
                var field = tbfields[ix];
                var order = 'DESC';
                if ($(elem).hasClass("rotate-180")) {
                    order='ASC';
                }
                v_order[field] = order;
            }
        });

        if (isEmpty(v_order)) {
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
        nrows = parseInt(nrows, 10);
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
    function isEmpty(obj) {
            return Object.keys(obj).length === 0;
    }

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
        var parms = { id_user: id_user, 
                  page: npage,
                  cfilter: v_filter,
                  vsorter: v_order,
                  num_rows: nrows,
                  columns: v_columns };
        if (v_filter) {
            var parms_filter = { id_user: id_user,
                          cfilter: v_filter};
        } else {
            var parms_filter = { id_user: id_user}
        }
        $.get("modules/getTotalRecords.php", parms_filter, function(data) {
            var resp = JSON.parse(data);
            var ntotal_rec = resp.total;
            total_rec.val(ntotal_rec);
        });
        $.get("modules/table_ntaliquotas.php", parms, function(data) {
            try {
                var resp = decodeURIComponent(data.replace(/\\x/g,"%"))
            } catch {
                var resp = data;
            }

            tbody.html("");
            tbody.html(resp);
       

            tbody.children().on("click", function(e) {
                // rotinas de clicks na tabela
                var typeElement = e.target.tagName;
                var td_row = $(this).closest("tr");
                var td_nota = td_row.find("td:eq(0)");
                var id_servicoxgrupo = td_nota.data("id_servicoxgrupo");
                var id_user = td_nota.data("user");

                if (typeElement==='IMG') {
                    var image_id = $(e.target).attr("id");
                    
                    switch (image_id) {
                        case 'marcar': 
                            var src = $(e.target).attr("src");
                            if (src.indexOf('no') > 0) {
                                src = src.replace("no", "");
                            } else {
                                src = src.replace("check", 'nocheck');
                            }
                            $(e.target).attr("src", "");
                            $(e.target).attr("src", src);
                            // conta quantas estão marcadas 
                            var vimages = $('.tb_aliquotas > tbody > tr').children().find("img#marcar").get();
                            var vchecks = $(vimages).filter((ix, elem) => {return ($(elem).attr("src")).indexOf('no') ==-1});
                            var btodos = vimages.length == vchecks.length;
                            var img_checktodos = $(".tb_aliquotas > thead > tr").children().find("img#todos");
                            src = img_checktodos.attr("src");
                            if (btodos) {
                                src = src.replace('/nocheck', '/check');
                              
                            } else {
                                src = src.replace('/check', '/nocheck');
                            }
                            img_checktodos.attr("src", src);
                            break;
                        case 'edit_aliquotas':
                            generic.html("");
                            window.localStorage.setItem("id_user", id_user);
                            window.localStorage.setItem("id_servicoxgrupo", id_servicoxgrupo);
                            break;

                        case 'log':
                            generic.html("");
                            window.localStorage.setItem("id_user", id_user);
                            window.localStorage.setItem("id_servicoxgrupo", id_servicoxgrupo);
                            window.localStorage.setItem("module", "ntaliquotas");
                            $.post("views/form_logs.html", function(data) {
                                generic.html(data);
                                var modal = generic.find('.modal');
                                modal.modal('show');
                                
                                modal.on("hidden.bs.modal", (evt) => {
                                    evt.preventDefault();
                                    close_modal(modal);
                                    window.localStorage.removeItem("id_user");
                                    window.localStorage.removeItem("id_servicoxgrupo");
                                    window.localStorage.removeItem("module");
                                });
                            });
                            
                            break;
        
                        case "deletar":
                            generic.html("");
                            window.localStorage.setItem("id_user", id_user);
                            window.localStorage.setItem("id_servicoxgrupo", id_servicoxgrupo);
                            window.localStorage.setItem("module", "ntaliquotas");
                            $.post("views/form_delete.html", function(data) {
                                generic.html(data);
                                var modal = generic.find(".modal");
                                modal.modal("show");
                                modal.on("hidden.bs.modal", (evt) => {
                                    evt.preventDefault();
                                    openmodal=true;
                                    window.localStorage.removeItem("id_user");
                                    window.localStorage.removeItem("id_servicoxgrupo");
                                    window.localStorage.removeItem("module");
                                    close_modal(modal);
                                });
                            });
                    }
                } else {
                    var td_vigencia = td_row.find("td:eq(4)");
                    var dt_vigencia = td_vigencia.text();
                    var index = $(e.target).closest("td").index();
                    var row_index = $(this).index();
                    var coords = { row : row_index, column: index };
                    window.localStorage.setItem("id_user", id_user);
                    window.localStorage.setItem("id_servicoxgrupo", id_servicoxgrupo);
                    window.localStorage.setItem("coords", JSON.stringify(coords));
                    generic.html("");
                    var value = $(e.target).text();
                    switch(index) {
                        case 8: // desc federal
                            window.localStorage.setItem("te_descricao", td_row.find("td:eq(8)").text());
                            window.localStorage.setItem("tp_descricao", 1);
                            $.get("views/form_descricaoservico.html", function(data) {
                                generic.html(data);
                                var modal = generic.find(".modal");
                                modal.modal("show");
                                modal.on("hidden.bs.modal", (evt) => {
                                    evt.preventDefault();
                                    openmodal=true;
                                    window.localStorage.removeItem("id_servicoxgrupo");
                                    window.localStorage.removeItem("coords");
                                    window.localStorage.removeItem("te_descricao");
                                    window.localStorage.removeItem("tp_descricao");
                                    close_modal(modal);
                                }); 

                            });
                            break;
                        case 10: // desc municipal
                            window.localStorage.setItem("te_descricao", td_row.find("td:eq(10)").text()); 
                            window.localStorage.setItem("tp_descricao", 2);
                            $.get("views/form_descricaoservico.html", function(data) {
                                generic.html(data);
                                var modal = generic.find(".modal");
                                modal.modal("show");
                                modal.on("hidden.bs.modal", (evt) => {
                                    evt.preventDefault();
                                    openmodal=true;
                                    window.localStorage.removeItem("id_servicoxgrupo");
                                    window.localStorage.removeItem("coords");
                                    window.localStorage.removeItem("te_descricao");
                                    window.localStorage.removeItem("tp_descricao");
                                    close_modal(modal);
                                }); 
                            });
                            break;

                        case 11: // IR
                            window.localStorage.setItem("tp_aliquota", "IR");
                            $.get('views/form_alteraaliquota.html',function(data) {
                                generic.html(data);
                                var modal = generic.find(".modal");
                                modal.modal("show");
                                modal.on("hidden.bs.modal", (evt) => {
                                    evt.preventDefault();
                                    openmodal=true;
                                    window.localStorage.removeItem("id_servicoxgrupo");
                                    window.localStorage.removeItem("coords");
                                    window.localStorage.removeItem("tp_aliquotas");
                                    close_modal(modal);
                                }); 
                            });
                            break;
                        case 12: // PCC
                            window.localStorage.setItem("tp_aliquota", "PCC");
                            $.get('views/form_alteraaliquota.html',function(data) {
                                generic.html(data);
                                var modal = generic.find(".modal");
                                modal.modal("show");
                                modal.on("hidden.bs.modal", (evt) => {
                                    evt.preventDefault();
                                    openmodal=true;
                                    window.localStorage.removeItem("tp_aliquotas");
                                    window.localStorage.removeItem("id_servicoxgrupo");
                                    window.localStorage.removeItem("coords");
                                    close_modal(modal);
                                }); 
                            });
                            break;
                        case 13: // INSS
                            window.localStorage.setItem("tp_aliquota", "INSS");
                            $.get('views/form_alteraaliquota.html',function(data) {
                                generic.html(data);
                                var modal = generic.find(".modal");
                                modal.modal("show");
                                modal.on("hidden.bs.modal", (evt) => {
                                    evt.preventDefault();
                                    openmodal=true;
                                    window.localStorage.removeItem("tp_aliquotas");
                                    window.localStorage.removeItem("coords");                                    
                                    window.localStorage.removeItem("id_servicoxgrupo");
                                    close_modal(modal);
                                }); 
                            });
                            break;

                        case 14: // ISS
                            window.localStorage.setItem("tp_aliquota", "ISS");
                            $.get('views/form_alteraaliquota.html',function(data) {
                                generic.html(data);
                                var modal = generic.find(".modal");
                                modal.modal("show");
                                modal.on("hidden.bs.modal", (evt) => {
                                    evt.preventDefault();
                                    openmodal=true;
                                    window.localStorage.removeItem("tp_aliquotas");
                                    window.localStorage.removeItem("coords");
                                    window.localStorage.removeItem("id_servicoxgrupo");
                                    close_modal(modal);
                                }); 
                            });
                            break;
                        case 15: // ISSBI
                            window.localStorage.setItem("tp_aliquota", "ISSBI");
                            $.get('views/form_alteraaliquota.html',function(data) {
                                generic.html(data);
                                var modal = generic.find(".modal");
                                modal.modal("show");
                                modal.on("hidden.bs.modal", (evt) => {
                                    evt.preventDefault();
                                    openmodal=true;
                                    window.localStorage.removeItem("tp_aliquotas");
                                    window.localStorage.removeItem("coords");
                                    window.localStorage.removeItem("id_servicoxgrupo");
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
          
            
        });
    }
    function close_modal(modal) {
        $('.modal-backdrop').remove();
        generic.remove(modal);
        generic.html("");
        generic.html("");
    }
    tfoot.children().find('img').on("click",(evt) => {
        var id = $(evt.target).attr("id");
        if (id == "add_servicos") {
            window.localStorage.setItem("id_user", id_user);
            $.get("views/vi_cadservicos.html", (data) => {
                generic.html(data);
                var modal = generic.find(".modal");
                modal.modal("show");
                modal.on("hidden.bs.modal", (evt) => {
                    evt.preventDefault();
                    openmodal=true;
                    close_modal(modal);
                }); 
            })
        } else {
            var npage = getCurrentPage();

            if (id == "first" && npage != 1) {
                npage = 1
            } else if (id == "prev" && npage != 1) {
                npage -= 1;
            } else if (id != "add_servicos") {
                var nrows = getnRows();
                var nrecords = total_rec.val();
                nrecords = parseInt(nrecords, 10);
                var nlastpage = Math.ceil(nrecords/nrows);
                if (id == "next" && ((npage +1) <= nlastpage)) {
                    npage += 1;
                    if (npage == 0) {
                        npage =1;
                    }
                } else if (id=="last" && npage != nlastpage) {
                    npage = nlastpage;
                }
            } else {
                npage == -1;
            }

            if (npage != -1) {
                setCurrentPage(npage);
                var dict_fields = getDictFields();
                var v_filter = getParms_Pesquisa();
                var v_order = getParms_Ordena();
                var npage = getCurrentPage();
                var nrows = getnRows();
                paginar(npage,nrows,v_filter,v_order,dict_fields);
            }
        }
    });

    /*
        inverte a setinha da imagem para exibir o modal de selecao
        de campos
    */

    $("#show_seltypes").on("click", (evt) => {
        var obj = $(evt.target);
        if (obj.hasClass("rotate-90")) {
            obj.removeClass("rotate-90");
            obj.attr("class", "");
        } 
    });

    $("#show_selfields").on("click", (evt) => {
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
        $("#show_selfields").addClass("rotate-90");
    });
   
    /*
        fechar a janela de selecao de campos de tipo de pesquisa
    */
    $(".modal.fade#show_types").on ("hidden.bs.modal", (evt) => {
       $("#show_seltypes").addClass("rotate-90");
    });

    /*
        rotina de seleção de numero
        de linhas por página
    */

    select_rows.on("change", (e) => {
        var dict_fields = getDictFields();
        var v_filter = getParms_Pesquisa();
        var v_order = getParms_Ordena();
        var npage = getCurrentPage();
        var nrows = getnRows();
        paginar(npage,nrows,v_filter,v_order,dict_fields);

    });

    /*
        rotina de selação de campos
        na tela.
    */

    function getTodosFieldColumn() {
        let modal_columns = $(".modal.fade#show_fields");
        let modal_header = modal_columns.find('.modal-header');
        let img_todos = modal_header.children().find('img#field-todos');
        
        return img_todos;
    }

    $(".modal.fade#show_types").children().on("click", "img", (evt)=> {
        evt.preventDefault();
        evt.stopPropagation();
        var id = $(evt.target).attr("id");
        var src = $(evt.target).attr("src");
        src = (src.indexOf("on") == -1) ? src.replace("off","on") : src.replace("on","off");
        var list_img = getlistFieldsPesquisa();
        $(evt.target).attr("src","");
        $(evt.target).attr("src",src);
        if (id == "type-todos") {
            list_img.each((ix, elem)=> {
                elem.src="";
                elem.src = src;
            });
        } else {
            let fields_checks = list_img.filter((ix, elem) => {
                return $(elem).attr("id") !== "type-todos";
            });
            let checkeds = fields_checks.filter((ix, elem) => {
                return ($(elem).attr("src")).indexOf("on") > 0;
            });
            var bcheck_todos = (fields_checks.length === checkeds.length) ? true : false;
            var todos = list_img.filter((ix, elem) => {
                return $(elem).attr("id") === "type-todos";
            })[0];
            var src = $(todos).attr("src");
            if (bcheck_todos && src.indexOf("on") == -1) {
                src = src.replace("off", "on");
            } else if (bcheck_todos == false && src.indexOf("on") > 0) {
                src = src.replace("on", "off");
            }
            $(todos).attr("src", "");
            $(todos).attr("src", src);
        }
    });

    
    $(".modal.fade#show_fields").children().on("click", "img", (evt)=> {
        var obj = $(evt.target);
        // troca o status do objeto corrente
        var src = obj.attr("src");
        var id = obj.attr("id");
        src = (src.indexOf("on") > 0) ? src.replace("on", "off") : src.replace("off", "on");
        obj.attr("src",src);
        let bt_aplicar = $(".modal.fade#show_fields").children().find('button');

        let vimgs = $(".modal.fade#show_fields").children().find('img');
        let vimgson = vimgs.filter((ix, elem) => { return (($(elem).attr("src")).indexOf("on") > 0 && $(elem).attr('id') !== 'field-todos')});
        if (id == 'field-todos') {
            vimgs.each((ix,elem) => {
                $(elem).attr("src",src);
            });
        } else {
            
            let img_todos = vimgs.eq(0);
            var src= img_todos.attr("src");
            src = (vimgson.length > 0 && vimgson.length == 15) ? src.replace('off', 'on') : src.replace('on', 'off');
            img_todos.attr("src",src);
        }
        // esconder ou mostrar as colunas
        if (id !== "field-todos") {
            vimgs.each ((ix,elem) => {
                if (ix > 0) {
                    var obj = $(elem);
                    var id = obj.attr("id");
                    id = id.replace("field-","");
                    var bshow=true;
                    if ((obj.attr('src')).indexOf('off') > 0) {
                        bshow=false;
                    }
                    dict_fields[id]=bshow;
                }
            });
            if (vimgson.length == 0) {
                bt_aplicar.prop('disabled', true);
            } else {
                bt_aplicar.prop('disabled', false);
            }
        } else {
            var bshow = (src.indexOf('on') > 0) ? true : false;
          
            if (bshow == false) {
                bt_aplicar.prop('disabled', true);
            } else {
                bt_aplicar.prop('disabled', false);
            }
        }
    });

   
    /*
        aplicar filtro na tabela
    */

    $("#aplicar_pesquisa").on("click",(evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        var dict_fields = getDictFields();
        var v_filter = getParms_Pesquisa();
        var v_order = getParms_Ordena();
        var npage = getCurrentPage();
        var nrows = getnRows();
        paginar(npage,nrows,v_filter,v_order,dict_fields);

        $("#limpar_pesquisa").css("display", "");
    });

    /*
        limpa o filtro de pesquisa e apaga a vassoura
    */

    $("#limpar_pesquisa").on("click", (evt) => {
        evt.preventDefault();
        evt.stopPropagation();
        var pesquisa = $("#pesquisa").val();
        if (pesquisa.length > 0) {
            pesquisa="";
            $("#pesquisa").val(pesquisa);
            pesquisa=null;
            $("#limpar_pesquisa").css("display", "none");
            var dict_fields = getDictFields();
            var v_filter = getParms_Pesquisa();
            var v_order = getParms_Ordena();
            var npage = getCurrentPage();
            var nrows = getnRows();
            paginar(npage,nrows,v_filter,v_order,dict_fields);
    
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
        var src = obj.attr('src');
                   
        if (id == 'todos') {
            var vimages = $('.tb_aliquotas > tbody > tr').children().find("img#marcar").get();
            if (src.indexOf("no") == -1) {
                src = src.replace("check", "nocheck");
            } else {
                src = src.replace("no","");
            }
            obj.attr("src","");
            obj.attr("src", src);
            $(vimages).each ((ix, elem) => {
                var obj_img = $(elem);
                obj_img.attr("src","");
                obj_img.attr("src", src);

            });

        } else {
            var list_fields =  new Array('Cd_UF',
                                        'Nm_Muni',
                                        'te_vigencia',
                                        'Cd_Grupo',
                                        'Te_Grupo',
                                        'cd_servicofederal',
                                        'te_servicofederal',
                                        'cd_servicomuni',
                                        'te_servicomuni',
                                        );
            if (src.indexOf("full") != -1) {
                src= src.replace("full", "page");

                obj.attr("src","");
                obj.attr("src", src); // troca a imagem
                if (obj.hasClass("rotate-180")) { // verifica se está invertido
                    obj.removeClass("rotate-180"); // se estiver inverte
                } else {
                    obj.addClass("rotate-180"); // inverte
                }
            } else if (src.indexOf("page") != -1) {
                if (obj.hasClass("rotate-180")) { // verifica se está invertido
                    obj.removeClass("rotate-180"); // se estiver inverte
                } else {
                    obj.addClass("rotate-180"); // inverte
                }
                src= src.replace("page", "no"); // 
                obj.attr("src","");
                obj.attr("src", src); // troca a imagem
            } else {
                src = src.replace("no","full");
                obj.attr("src","");
                obj.attr("src", src); // troca a imagem
            }
            var tr_imgordena = thead.find("img#ordenar");
            var vlist_tudo = tr_imgordena.filter((ix, elem) => {
                return (($(elem).attr('src')).indexOf("full") !== -1);
            });
            

            var vlist_page = tr_imgordena.filter ((ix, elem) => {
                var src = $(elem).attr("src");
                return (src.indexOf("page") != -1);
            });

            var vorder = {};
            var vlist_sort = tr_imgordena.filter ((ix, elem) => {
                var src = $(elem).attr("src");
                var order = "";
                if ((src.indexOf("page") != -1) || (src.indexOf("tudo") != -1)) {
                    if ($(elem).hasClass("rotate-180")) {
                        order = "DESC";
                    } else {
                        order = "ASC";
                    }
                    vorder[list_fields[ix]] = order;
                    return true;
                }
            });

            if (vlist_tudo.length > 0) {
                // ordenar tudo
                if (vlist_sort.length > 0) {
                    var npage = 1;
                    $("#sel_page").val(npage.toString());
                    var dict_fields = getDictFields();
                    var v_filter = getParms_Pesquisa();
                    var v_order = getParms_Ordena();
                    var npage = getCurrentPage();
                    var nrows = getnRows();
                    paginar(npage,nrows,v_filter,v_order,dict_fields);
            
                }
            } else {
                if (vlist_page.length > 0) {
                    var vcolumns = Object.keys(vorder);
                    var positions = new Array();
                    for (var ix =0; ix < vcolumns.length; ix++) {
                        var pos = list_fields.indexOf(vcolumns[ix]);
                        if (pos !== -1) {
                            positions.push(pos);
                        }
                    }
                    waiting.css("display", "");
                    waiting.css("display", "none");
                    rows = classificar(positions);
                    var tbody = $(".table.tb_aliquotas > tbody");
                    tbody.html("");
                    for (var ix=0; ix < rows.length; ix++) {
                        tbody.append(rows[ix]);
                    }
                    waiting.css("display", "");
                    waiting.css("display", "block");

                    function classificar(columns) {
                        var rows = $(".table.tb_aliquotas > tbody > tr").get();
                        var ini = 0;
                        var length = rows.length-1;
                        rows = quicksort(ini, length, rows, columns);
                        return rows;
                    }
            
                    function quicksort(ini,fim, rows, columns) {
                        var ipivot = parseInt(ini+((fim-ini)/2));
                        var row_pivot = rows[ipivot];
                        var content_pivot = get_content(columns, row_pivot);
                        var i = ini;
                        var j = fim;
                        while (i <= j) {
                            while (1) {
                                var row_a = rows[i];
                                var content_a = get_content(columns, row_a);
                                if (content_a < content_pivot) {
                                    i+=1;
                                } else {
                                    break;
                                }
                            }
                            while (1) {
                                var row_a = rows[j];
                                var content_a = get_content(columns, row_a);
                                if (content_a > content_pivot) {
                                    j-=1;
                                    if (j == 0) { break;}
                                } else {
                                    break;
                                }
                            }
                            if (i <= j) { 
                                rows = exchange_rows(rows, i, j);
                                i += 1;
                                j -= 1;
                            }
                        }
                        if (ini < j) { 
                            return quicksort(ini,j,rows,columns);
                        } else if (i < fim) {
                            return quicksort(i,fim,rows,columns);
                        } else {
                            return rows;
                        }
                    }

                    function exchange_rows (rows, i, j) { 
                        var temp = rows[i];
                        rows[i] = rows[j];
                        rows[j] = temp;
                        return rows;
                    }
                    function retData(pdt) {
                        var aux = pdt.split('/');
                        var ano = aux[2];
                        var mes = aux[1];
                        var dia = aux[2];
                        return ano+mes+dia;
                    }
                    function get_content(columns, row) {
                        var caux="";
                        for (var ix=0; ix < columns.length; ix++) {
                            var index = columns[ix];
                            switch(index) {
                                case 0: // Cd_UF
                                    caux += $(row).find("td:eq(2)").text();
                                    break;
                                case 1: // Nm_Muni
                                    caux += $(row).find("td:eq(3)").text();
                                    break;
                                case 2: // Te_Vigencia
                                    var dt = $(row).find("td:eq(4)").text();
                                    var vdt = dt.split('-');
                                    var dt1 = retData(vdt[0]);
                                    var dt2 = retData(vdt[1]);
                                    caux += dt1+dt2;
                                    break;
                                case 3: // Cd_Grupo
                                    caux += $(row).find("td:eq(5)").text();
                                    break;
                                case 4: // Te_Grupo
                                    caux += $(row).find("td:eq(6)").text();
                                    break;
                                case 5: // cd_servicofederal
                                    caux+= $(row).find("td:eq(7)").text();
                                case 6: // te_servicofederal
                                    caux+= $(row).find("td:eq(8)").text();
                                case 7: // cd_servmuni
                                    caux+= $(row).find("td:eq(9)").text();
                                case 8: // te_servimenui
                                    caux+= $(row).find("td:eq(10)").text();

                            }
                        }
                        return caux;
                    }
                }
            }
        }
    });
    
    /*
        selecionar colunas
    */
    function getDictFields() {

        var vlist_img = getlistFieldsColumn();
        var img_todos = getTodosFieldColumn();
        var thead_th = thead.find('th');
        if ((img_todos.attr('src')).indexOf('on') > 0) {
            vlist_img.each ((ix, elem) => {
                var field = ($(elem).attr("id")).replace('field-','');
                dict_fields[field]=true;
                var pos = ix+2;
                $(thead_th[pos]).show();
            });
          
        } else {
            width=0;
            vlist_img.each ((ix, elem) => {
                bshow = ($(elem).attr("src")).indexOf("on") > 0;
                var field = ($(elem).attr("id")).replace('field-','');
               
                dict_fields[field]=bshow;
                var pos = ix+2;
                if (bshow) {
                    $(thead_th[pos]).show();
                } else {
                    $(thead_th[pos]).hide();
                    nwidth = $(thead_th[pos]).css('width');
                    nwidth = nwidth.replace('px','');
                    width += nwidth;
                }
            });
        }
        return dict_fields;
    } 
    $("#aplicar").on("click", (evt) => {
     
        var dict_fields = getDictFields();
        var v_filter = getParms_Pesquisa();
        var v_order = getParms_Ordena();
        var npage = getCurrentPage();
        var nrows = getnRows();
        paginar(npage,nrows,v_filter,v_order,dict_fields);
        
    });
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
                let vimgs = $(".modal.fade#show_fields").children().find('img');
                let vimgson = vimgs.filter((ix, elem) => { 
                    var elem = $(elem);
                    var src = elem.attr("src");
                    var bret = (src.indexOf("on") > 0 && elem.attr('id') !== 'field-todos');
                    return bret;
                });
                if (vimgson.length==15) {
                    // todos os campos estão visiveis
                } else {
                    var pos_fields = Objects.keys(dict_fields);
                    vimgs.each ((ix, elem) => {
                        var id = $(elem).attr('id');
                        if (id !== 'field-todos') {
                            var src = $(elem).attr('src');
                            var ligado = src.indexOf("on") > 0;
                            var pos = ix+2;
                            var field = pos_fields[pos];
                            dict_fields[field] = ligado;
                        }
                    })
                }
                paginar(npage,nrows,v_filter,v_order,dict_fields);
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
