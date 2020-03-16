$().ready(function() {

    $("#paraminstall").validate({
        rules: {
            loginsa: {
                required: true
            },
            passa:{
                required: true
            },
            passa2:{
                required: true,
                 equalTo: "#passa"
            },
            serveur:{
                required: true
            }, 
            base:{
                required: true
            }, 
            util:{
                required: true
            }
        },
        message:{
              passa2:{
                 equalTo: "Les mots de passe doivent étre identique"
            }
        }
    })

})