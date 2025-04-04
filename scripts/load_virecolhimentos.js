$(document).ready((evt) => {

    let id_user = $("input.id_user").val();
    let tp_user = $("input.tp_user").val();
    let vi_generic = $('.generic');
    let table_municipios = $(document.querySelector('.tb_municipios'));
    let vlist_rows = table_municipios.find('tbody').children('tr');
    vlist_rows.each(()=> {
        let curr_row = $(this);
        let td_idmuni = curr_row.find('td>nth_child(2');
        var id_muni = td_idmuni.prop('data-idmuni') || td_idmuni.data('idmuni');
    });
  
   
})