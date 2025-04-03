$(document).ready((evt) => {
    var id_user = $("#id_user").val();
    var tp_user = $("#tp_user").val();
    var id_muni = $("#id_muni").val();
    var dt_compet = mudarFormato($("#info-dtcompet").val()).trim();

    var npage = $("sel-page").val();

    let total_records = $("input#total_rec");

    var table = $("table.tabela-agencias");
    var tfoot = table.find("tfoot");
    var nrows = getnRows();

    var waiting = $("div.waiting");
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
    
    console.log("row",nrows)


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

    let tbody_recolhimento = $(document.querySelector('#tbody-recolhimentos'));

    $.ajax({
        url:'modules/ler_recolhimentos.php',
        type: 'POST',
        data: {
            id_user: id_user,
            tp_user: tp_user,
            id_muni: id_muni,
            dt_compet: dt_compet,
            npage: npage,
            // nrows: nrows
        },
        dataType:'json',
        success: function(response) {
            var erro = response.Error;
            var message = response.Message;
            var data = JSON.parse(response.Data);
            var ntotal = parseInt(response.Total_Records);
            console.log(data);
            if (erro == '0') {
                total_records.val(ntotal);
                if (ntotal > 0) {
                    tbody_recolhimento.empty();
                    data.forEach((line) => {
                        if (line) {
                            var obj_line = $(line);
                            tbody_recolhimento.append(obj_line);
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
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
            console.error('Erro ao carregar o modal:', error);
        }
    });

    /*
        retorna o numero de linhas da tela
    */
        function getnRows() {
            let sel_linhas = tfoot.children().find("#sel-linhas > option:selected");
            nrows = sel_linhas.val();
            nrows = parseInt(nrows);
            return nrows;
    }
    

    function mudarFormato(data) {
        var partes = data.split('/');
        return partes[1] + '-' + partes[0];
    }



    let div_continner = $('.generic-notas');
    $(document).on("click", "#valorBase", function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // let div_cont = $('.generic');
        
        $.post('views/tela_notas.php', function (data) { 
            div_continner.html('');
            div_continner.html(data)
            let modal = $(".modal");
            modal.modal('show');
        });
        
    });

    // close modal
    $(document).on("click", "#btn-closenotas", function (e) {     
        // $('.modal:first').modal('hide');
        // let modalExistente = $('#modal-recolhimento');
            // let backdrop = $('.modal-backdrop');

            // if(modalExistente.length) {
            //     modalExistente.remove();
            // Remove the modal backdrop
            //     if (backdrop.length) {
            //         backdrop.remove();
            //     }
                
        // }
        // div_continner.html('')
        $('.modal').modal('hide');
    })
    // data-user-id='123'
    // console.log("hello", e.target.dataset.userId);
    
});