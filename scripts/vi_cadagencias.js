$(document).ready((ev)=> {
    let table_agencia = $(".table-agencias > table");
    let tbody = table_agencia.find("tbody");
    let tfoot = table_agencia.find("tfoot");
    let total_records = $("#total_agencias");
    let sel_municipio = $("#municipioSelect");
    let sel_estado = $("#estadoSelect");
    let filtro = $("button#bt_filtro");
    let input_filtro = $("#text_filtro");
    let limpar_filtro = $("button#bt_limpar");
    let select_linhas = tfoot.children().find("#sel-linhas");
  

    filtro.on("click",(ev)=> {
        paginar(input_filtro.val());
        
    });

    limpar_filtro.on("click",(ev)=> {
        input_filtro.val("");
        sel_municipio.prop('selectedIndex', -1);
        sel_estado.prop('selectedIndex', -1);
        paginar();
    });
    paginar();
 
    /*
        pega a página corrente
    */
    function getCurrentPage() {
        var npage = parseInt($("#sel-page").val()); // pagina corrente
        // verifica número de linhas por página
        return npage;
    }
    
    /*
        retorna o numero de linhas da tela
    */
    function getnRows() {
        nrows = select_linhas.val();
        nrows = parseInt(nrows);
        return nrows;
    }


    tfoot.children().find('img').on("click",(evt) => {
        var id = $(evt.target).attr("id");
        var npage = parseInt(getCurrentPage());
       
        if (id == "first" && npage != 1) {
            npage = 1
        } else if (id == "prev" && npage != 1) {
            npage -= 1;
        } else {
            var nrows = getnRows();
            var nrecords = parseInt(total_records.val());
            var nlastpage = Math.ceil(nrecords/nrows);
            if (id == "next" && ((npage +1) <= nlastpage)) {
                npage += 1;
            } else if (id=="last" && npage != nlastpage) {
                npage = nlastpage;
            } else {
                npage == -1;
            }
        }
        var sfiltro = input_filtro.val();
        if (npage != -1) {
            setCurrentPage(npage);
            paginar(sfiltro);
        }
    });

    function setCurrentPage(npage) {
        if (npage !== undefined) {
            var cpage = npage.toString();
            $("#sel-page").val(cpage);       
        } else {
            $("#sel-page").val("1");       
        }
    }
    
    
    function paginar(sfiltro) {

        let cd_estado = sel_estado.val();
        let id_muni = sel_municipio.val();
        let curr_page = getCurrentPage();
        let curr_lines = getnRows();
      
        var payload = {
            cd_estado: cd_estado,
            id_muni: id_muni,
            page: curr_page,
            nrows: curr_lines,
            filter: sfiltro
        }
    
        $.ajax({
            url: 'modules/load_agencias.php',
            method: 'POST',
            data: payload,
            dataType: "json",
            error: function(xhr, status, error) {
               console.log(xhr.responseText, status, error);
            },
            success: function(response) {
                var resp = response;
                var error = response.Error;
                var records = response.Total_Records;
                var data = JSON.parse(response.Data,true);
                if (error == '0') {
                    total_records.val(records);
                    tbody.empty();
                    var cline = ''
                    for (var i = 0; i < data.length; i++) {
                        cline += data[i];
                    }
                    tbody.html(cline);
                }
            }
        });
    }
});