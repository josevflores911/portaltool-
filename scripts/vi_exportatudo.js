jq2 = jQuery.noConflict();
jq2(function( $ ) {
   
    $("input[name='exportatudo']").on("click", (evt) => {
        var element = $(evt.target);
        if (element.val() == '2') {
            var tbody = $(".tb-fields>tbody");
            
            $.get("modules/ler_nfseStruct.php", (data) => {
                var resp = JSON.parse(data);
                $.each (resp, (key, value) => {
                    
                })
            });

        }
    });
});