$(document).ready((evt) => {
    var id_user = $("#id_user").val();
    var tp_user = $("#tp_user").val();
    var id_muni = $("#id_muni").val();
    var dt_compet = mudarFormato($("#info-dtcompet").val()).trim();
    let div_modal = $('.generic-recolhimento');
    let waiting_recolhimento= $(".waiting-recolhimento");
    var npage = $("sel-page").val();

    let total_records = $("input#info-total");

    var table = $("table#tabela-agencias");
    
    var tfoot = table.find("tfoot");
    var nrows = tfoot.children().find('#sel-linhas > option:selected').val();

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
            nrows: nrows
        },
        success: function(response) {
            var resp = JSON.parse(response);
            var erro = resp.Error;
            var message = resp.Message;
            var data = JSON.parse(resp.Data);
            var ntotal = parseInt(resp.Total_Records);
            total_records.val(ntotal);
            waiting_recolhimento.css('display', 'none');
            data.forEach((elem,ix) => {
                var obj_row = $(elem);
                tbody_recolhimento.append(obj_row);
            });
            if (ntotal > 0) {
                var vlist_tr = tbody_recolhimento.children('tr');
                vlist_tr.each((ix,elem) => {
                  
                   var base_ISS = $(elem).children('td').eq(8);
                   var cd_tipoagencia = base_ISS.prop('data-cd_tipoagencia') || base_ISS.data('cd_tipoagencia');
                   var label_vlbase = base_ISS.find('label[id^="vl_baseISS-"]');

                   var id_agencia = label_vlbase.attr('id').replace(/\D+/,'');
                   var vl_baseISS = label_vlbase.text();
                   if (vl_baseISS.length > 0) {
                       try {
                            vl_baseISS = parseFloat(vl_baseISS.replace('.', '').replace(',','.'));
                       } catch (e) {
                           console.error('Erro ao converter valor do ISS:', e);
                            vl_baseISS = 0.0;
                       }
                       if (vl_baseISS > 0.0 && cd_tipoagencia == 'T') {
                            base_ISS.on('click', (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                payload = {
                                    id_agencia: id_agencia,
                                    dt_compet: dt_compet,
                                }
                                
                                
                                $.ajax({
                                    url:'views/tela_notas.php',
                                    type: 'POST',
                                    data: payload,
                                    success: function(response) {
                                        div_modal.html('');
                                        setTimeout(() => {
                                            div_modal.html(response);
                                            let modal_notas = $(document.querySelector('#modal-notas'));
                                            if (modal_notas.length > 0) {
                                                /*
                                                    creating a button to dispatch the modal
                                                */
                                                let btn_modal = $('<button type="button" data-toggle="modal" data-target="#modal-notas" style="display:none"/>');
                                                div_modal.append(btn_modal);
                                                btn_modal.trigger('click');
                                                let bt_fechar = div_modal.children().find("button.btn-close");
                                                bt_fechar.on('click', (e) => {
                                                    e.preventDefault();
                                                    e.stopPropagation();
                                                    modal_notas.modal('hide');
                                                    $('.modal-backdrop').remove();
                                                    $('body').removeClass('.modal-page');
                                                    $('body').removeClass('.modal-open');
                                                    $('body').removeClass('.modal-show');
                                                    $('body').removeClass('.modal-dialog-scrollable');
                                                    $('body').removeClass('.modal-dialog-centered');
                                                    $('body').removeClass('.modal-static');
                                                    $('body').removeClass('.modal-dialog-centered');
                                                    $('body').css('padding', 0);
                                                    div_modal.remove(btn_modal);
                                                    div_modal.html('');

                                                });
                                            }
                                        },1000);
                                        

                                      
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Erro ao carregar dados do recolhimento:', error);
                                    }
                                });
                            });
                        
                       }
                   }
                });
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


   
    
});