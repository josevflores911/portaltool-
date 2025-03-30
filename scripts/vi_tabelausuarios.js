$(document).ready(() => {
    let div_program = $(".generic");

    let bt_criar = document.querySelector('#btcriar');    
    
    $(bt_criar).on("click", (e) => {
        e.preventDefault();
        e.stopPropagation();
        $.get('views/vi_criarusuario.html', function(data) {
            div_program.html('');
            div_program.html(data);
            let modal= $(".modal");
            modal.modal('show');
        });
    });

});
