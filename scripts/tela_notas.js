$(document).ready(function() {
    var id_agencia = $(document.querySelector('#id_agencia')).val();
    var dt_compet = $(document.querySelector('#dt_compet')).val();
    var table_notas = $(document.querySelector('.table-notas'));
    let tbody_notas = table_notas.find('tbody');
    let tfoot_notas = table_notas.find('tfoot');
    let waiting_notas = $('.waiting-notas');
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
        let sel_linhas = tfoot_notas.children().find("#sel-linhas > option:selected");
        nrows = sel_linhas.val();
        nrows = parseInt(nrows);
        return nrows;
    }

    tfoot_notas.children().find('img').on("click",(evt) => {
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

    function paginar() {
        var nrows = getnRows();
        console.log(nrows)
        var npage = getCurrentPage();
        console.log(npage);
        var nrecords = parseInt($("#total_rec").val());
        waiting_notas.css('display','');
        tbody_notas.html("");
        $.ajax({
            url: "modules/ler_notas.php",
            method: "POST",
            data: {
                id_agencia: parseInt(id_agencia),
                dt_compet: dt_compet.split('/').reverse().join('-'),
                page: npage,
                rows: nrows
            },
            success: function(response) {
                var resp = JSON.parse(response);
                var error = resp.Error;
                var data = JSON.parse(resp.Data);
                const quantidade = resp.Total_Records;
                console.log("qtn:" + quantidade);
                $('h6#qtn-notas').html("<p>Quant Notas: " + quantidade + "</p>");
                if (error === '0' ) {
                    waiting_notas.css('display','none');
                    data.forEach((elem,ix) => {
                        var obj_row = $(elem);
                        tbody_notas.append(obj_row);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                console.error("AJAX error: " + error);
            }

        });
    }

    paginar();
});