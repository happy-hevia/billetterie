// désactive les liens avec la classe disabled
$(".disabled").click(function (e) {
    e.preventDefault();
    return false;
});

gestionValidationReservation();
gestionValidationTarifs();
gestionValidationVisiteurs();

// gestion de la navigation avec les onglets du tunnel d'achat
$('.ongletsNavigation-element').each(function () {
    if ($(this).data("url") != null) {
        $(this).click(function () {
            $('#conteneur-ticketManager').load($(this).data("url") + " #contenu-ticketManager",null, function(){
                $.getScript($('#js-tunnelAchat').attr('src'));
            });

        })
    }
})

// gestion de la navigation avec les onglets des visiteurs
$('.onglets-visiteurs').each(function () {
    if ($(this).data("url") != null) {
        $(this).click(function () {
            $('#conteneur-ticketManager').load($(this).data("url") + " #contenu-ticketManager",null, function(){
                $.getScript($('#js-tunnelAchat').attr('src'));
            });
        })
    }
})

// gestion du bouton passer à l'étape suivante de la page tarif
function gestionValidationTarifs() {
    $('#boutonValidation1').click(function () {
        $('#conteneur-ticketManager').load($(this).data("url") + " #contenu-ticketManager",null, function(){
            $.getScript($('#js-tunnelAchat').attr('src'));
        });
    })
}


// gestion de la validation du formulaire de reservation
function gestionValidationReservation() {
    $('#formReservation').submit(function (event) {
        event.preventDefault();

        $.ajax($(this).attr("action"), {
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            type: 'POST',
            data: $('#formReservation').serialize(),
            success: function (data) {
                $("#conteneur-ticketManager").html($(data).find("#contenu-ticketManager"));
                $.getScript($('#js-tunnelAchat').attr('src'));
            }
        })

    });
}

// gestion des formulaire de la page visiteur
function gestionValidationVisiteurs() {
    $('#formVisiteurs').submit(function (event) {
        event.preventDefault();

        $.ajax($(this).attr("action"), {
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            type: 'POST',
            data: $('#formVisiteurs').serialize(),
            success: function (data) {
                $("#conteneur-ticketManager").html($(data).find("#contenu-ticketManager"));
                $.getScript($('#js-tunnelAchat').attr('src'));
            }
        })

    });
}
