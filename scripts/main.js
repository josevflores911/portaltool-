$(document).ready(()=> {
  var form = document.querySelector('form');
  form.setAttribute('autocomplete', 'off');
  $("#usuario").attr("autocomplete", "off");
  $("#senha").attr("autocomplete", "off");
  $("#usuario").focus();
  let mudou_senha = false;
  let img_logo = $("#logo");
  let img_favicon = $("#favicon");
  let body = $(document.querySelector('body'));
  let img_spin = $("#spin");

  $("#btenviar").on("click", (e)=> {
    e.preventDefault();
    var usuario = $("#usuario").val();
    var senha = $("#senha").val();
    if (usuario.length == 0) {
      mudou_senha=false;
      $(".message").html("");
      $(".message").html("<b>Usuário é campo obrigatório!</b>");
    } else {
        
        if (senha.length == 0) {
          mudou_senha=false;
          $(".message").html("");
          $(".message").html("<b>Senha é campo obrigatório!</b>");
        } else {
            if (senha.indexOf(";") === -1) {
              mask_pwd(senha);
              senha = $("#senha").val();
            }
            var waiting = $(".waiting");
            var lbl_message = $(".message");
            lbl_message.html("");
            waiting.css("display", "");
            waiting.css("display", "block");
            waiting.css("display", "block");
            $.post("modules/valida.php", {email:usuario, senha: senha}, function(data) {
              var resp = JSON.parse(data);
              var message = resp.message;
              waiting.css("display", "");
              waiting.css("display", "none");
              waiting.css("display", "none");
              $(".message").html("<b>" + message + "</b>");

              if (resp.erro == '0') {
                  $principal = $("div.load_pages");
                  var parms = {id_user : resp.id_user, nm_user : resp.nm_user};    
                  $.post ("views/main_page.php", parms, (data) => {
                      $principal.html("");
                      $principal.html(data);
                  });
              } else {
                mudou_senha = false; 
                $("#senha").val("");
                $("#senha").val("");
              }
            }); 
        }
    }
});

 
$("#usuario").on("focus", (e) => {
  e.preventDefault();
  e.stopPropagation();
  $(this).val("");
  $("#senha").val("");
  $(".message").html("");
});



function mask_pwd(data) {
  new_value="";
  if (data.length > 0) {
    for (var idx = 0; idx < data.length; idx++) {
      c = data.charCodeAt(idx);
      c += 32;
      new_value += String.fromCharCode(c)+";";
    }
    mudou_senha = true;
  }
  return new_value;
}

$("#senha").on("focus", (e) => {
  e.preventDefault();
  e.stopPropagation();
 
  e.target.value = "";
  $(".message").html("");
});

$("#senha").on("change, blur", (e) => {
  if (mudou_senha == false) {
    var obj = $(e.target);
    obj.val(mask_pwd(obj.val()));
  
  }
})

$("#btenviar").on("mouseover", (e) => {
  if ($("#senha").val().length > 0) {
    if (mudou_senha == false) {
      $("#senha").val(mask_pwd($("#senha").val()));
      
    }
  }
});
  
});