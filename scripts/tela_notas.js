$(document).ready(function() {
    var id_agencia = $(document.querySelector('#id_agencia')).val();
    var table_notas = $(document.querySelector('#table-notas'));
    let tbody = table_notas.find('tbody');
    let tfoot = table_notas.find('tfoot');
    
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


});