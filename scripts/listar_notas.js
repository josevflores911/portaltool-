$(document).ready(() => { 
    $(".open-menu").on("click", (ev) => {
        var img = $(ev.target);
        var cdisplay = $(".wrapper").css("display");
        var close_panel = $("#close_panel");
        close_panel.css("left","");
        var principal = $('div.principal');
       
        $(".wrapper").css("display","");
        if (cdisplay == "flex") {
            img.addClass("rotate-180");
            cdisplay = "none";
            close_panel.css("left", '0px');
            principal.css("left", '0px');
            principal.css('width', '100%');
        } else {
            img.removeClass("rotate-180");
            cdisplay = "flex";
            close_panel.css("left", '12.5em;');
            principal.css("left", '12.6rem');
            principal.css('width', '90%');
        }
        $(".wrapper").css("display",cdisplay);
    });

   $("img#btmenu").click( (event) => {
        event.preventDefault();
        var img = $(event.target);
        if (img.hasClass('rotate-90')) {
            img.removeClass('rotate-90');
        } else {
            img.attr("class",'rotate-90');
        }
   });

   $("input[type='button']").click( (event) => {
        event.preventDefault();
        var bt = $(event.target);
        var value = bt.val();
        if (value == "Não") {
            var menu = $("div.row.menu-text").eq(3);
            var img = menu.find("img");
            if (img !== undefined) {
                if (img.hasClass('rotate-90')) {
                    img.removeClass('rotate-90');
                } else {
                    img.addClass('rotate-90');
                }
            }
            $("#sair-sistema").collapse('hide');
        } else {
            /* --- get parent div --- */
            var id_user = bt.attr('data-user');
            var waiting = $("#waiting");
            waiting.css("display", "");
            waiting.css("display", "block");
            var token   = "d5bccefab1ece23d134b307160ab1df6"; 
            parms = { id_user: id_user, token: token};
            $.post("modules/sair.php", parms, function(response) {
                var resp = JSON.parse(response);
                if (resp.Error == '0') {
                    window.location.reload(true);
                    waiting.css("display", "");
                } 
            }); 
        }
   });

   $("li.list-group-item").on("click", (evt) => {
        var opt = $(evt.target);
        var img = opt.children().find("img#btmenu");
        if (img.hasClass("rotate-90")) {
            img.removeClass("rotate-90");
        } else {
            img.addClass("rotate-90");
        }
   });
   

   $("div.sidebar").on("click", (ev)=> {
        ev.preventDefault();
        ev.stopPropagation();
        let div_program = $(".principal");
        var id = $(ev.target).attr("id");
       var v_opt = {
           "menu-sistem-1": "vi_acessos.php",
           "menu-sistem-8": "vi_tabelausuario.html",
        }
                     
        if (v_opt[id] !== undefined) {
           var program = 'views/'+v_opt[id];
           if (exist_files(program)) {
                var id_user = $(ev.target).prop("data-user") || $(ev.target).attr("data-user") || $(ev.target).data("user");
                var tp_user = $(ev.target).prop("data-tpuser") || $(ev.target).attr("data-tpuser") || $(ev.target).data("tpuser");

                $(ev.target).css("cursor", 'wait');
                $.post(program, {id_user: id_user,tp_user: tp_user}, function(data) {
                    div_program.html("");
                    $(ev.target).css("cursor", 'pointer');
                    div_program.html(data);
                });
           } 
        } 
        
        function exist_files(vprog) {
            if (vprog) {
                var req = new XMLHttpRequest();
                req.open('GET', vprog, false);
                req.send();
                return req.status == 200;
            } 
            return false;
        }
   });
 
 });



