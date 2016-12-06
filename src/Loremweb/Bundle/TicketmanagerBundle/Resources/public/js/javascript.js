gestionAjaxTunnel();


// Fonction à appeler à chaque chargement AJAX

function gestionAjaxTunnel() {
    desactiveLiens();
    gestionNavigationTunnel();
    gestionNavigationVisiteur();
    gestionValidationTarifs();
    gestionValidationReservation();
    gestionValidationVisiteurs();

    gestionSelectionNombreBillet();

    //    Gestion des datepicker
    gestionDatePickerReservation();
    gestionDatePickerVisiteur();

//    gestion type de billet
    gestionTypeDeBilletDisabled();
}

// désactive les liens avec la classe disabled
function desactiveLiens() {
    $(".disabled").click(function (e) {
        e.preventDefault();
        return false;
    });
}


// gestion de la navigation avec les onglets du tunnel d'achat
function gestionNavigationTunnel() {
    $('.ongletsNavigation-element').each(function () {
        if ($(this).data("url") != null) {
            $(this).click(function () {
                $('.loading').show();
                $('.panneau-principal.active').removeClass("slideApparition").addClass("slideDisparition");
                $('#conteneur-ticketManager').load($(this).data("url") + " #contenu-ticketManager", null, function () {
                    gestionAjaxTunnel();
                });

            })
        }
    })
}

function gestionNavigationVisiteur() {
// gestion de la navigation avec les onglets des visiteurs
    $('.onglets-visiteurs').each(function () {
        if ($(this).data("url") != null) {
            $(this).click(function () {
                $('.loading').show();
                $('.panneau-principal.active').removeClass("slideApparition").addClass("slideDisparition");
                $('#conteneur-ticketManager').load($(this).data("url") + " #contenu-ticketManager", null, function () {
                    gestionAjaxTunnel();
                });
            })
        }
    })
}


// gestion du bouton passer à l'étape suivante de la page tarif
function gestionValidationTarifs() {
    $('#boutonValidation1').click(function () {
        $('.loading').show();
        $('.panneau-principal.active').removeClass("slideApparition").addClass("slideDisparition");
        $('#conteneur-ticketManager').load($(this).data("url") + " #contenu-ticketManager", null, function () {
            gestionAjaxTunnel();
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
            beforeSend: function () {
                $('.loading').show();
                $('.panneau-principal.active').removeClass("slideApparition").addClass("slideDisparition");
            },
            success: function (data) {
                $("#conteneur-ticketManager").html($(data).find("#contenu-ticketManager"));
                gestionAjaxTunnel();
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
            beforeSend: function () {
                $('.loading').show();
                $('.panneau-principal.active').removeClass("slideApparition").addClass("slideDisparition");
            },
            success: function (data) {
                $("#conteneur-ticketManager").html($(data).find("#contenu-ticketManager"));
                gestionAjaxTunnel();
            }
        })

    });
}

//    Gestion des datepicker


// DatePicker formulaire de reservation
function gestionDatePickerReservation() {
    var $dateVisite = $('#reservation_dateVisite');

    if ($dateVisite.length) {
        // recupère paramètre du bundle
        var parameters = JSON.parse($dateVisite.attr('data-param'));
        // recupère le nombre de visiteurs potentiel selon la dates
        var $reservation = $('#reservation');
        var visiteurParDate = JSON.parse($reservation.attr('data-visiteurs'));

        // créer le tableau des dates fermés
        var disabledDates = [];
        var anneeCourrante = new Date().getFullYear();

        parameters['date']['date_closed'].forEach(function (el) {
            disabledDates.push(moment(anneeCourrante + el, "YYYYMM-DD"));
            disabledDates.push(moment((anneeCourrante + 1) + el, "YYYYMM-DD"));
        });

        // Si il n'y a plus de place pour un jour donné, alors l'ajouter aux dates desactivé
        for (var el in visiteurParDate) {
            if (visiteurParDate[el] == 0) {
                disabledDates.push(moment(el, "YYYY-MM-DD"));
            }
        }


        // Configuration du datepicker reservation
        $dateVisite.datetimepicker({
            locale: "fr",
            format: 'YYYY-MM-DD',
            minDate: moment(),
            maxDate: moment().add(365, 'day'),
            disabledDates: disabledDates,
            daysOfWeekDisabled: parameters['date']['days_closed'],
            viewMode: 'days'
        });
    }
    return parameters;
}

// Modifie le champ de selection de billet selon le nombre de billet restant
function gestionSelectionNombreBillet() {
    var $nombreBillet = $('#reservation_nombreBillet');

    if ($nombreBillet.length) {
        // recupère le nombre de visiteurs potentiel selon la dates
        var $reservation = $('#reservation');
        var visiteurParDate = JSON.parse($reservation.attr('data-visiteurs'));

        var reservation_dateVisite = $('#reservation_dateVisite');


        var $dateVisite = $('#reservation_dateVisite');
        var parameters = JSON.parse($dateVisite.attr('data-param'));


        reservation_dateVisite.on('dp.change', function (e) {
            if (visiteurParDate[e.date.format('YYYY-MM-DD')]) {
                var nombreBilletRestant = visiteurParDate[e.date.format('YYYY-MM-DD')];
                $nombreBillet.empty();
                for (var i = 1; i <= nombreBilletRestant; i++) {
                    $('<option>').val(i).text(i).appendTo('#reservation_nombreBillet');

                    if (i >= parameters['nb_tickets_max']){
                        break;
                    }
                }
            } else {
                $nombreBillet.empty();
                for (var i = 1; i <= parameters['nb_tickets_max']; i++) {
                    $('<option>').val(i).text(i).appendTo('#reservation_nombreBillet');
                }
            }
        })
    }
}

//  Datepicker formulaire visiteur
function gestionDatePickerVisiteur() {
    var $dateNaissance = $('#visiteur_dateNaissance');

    if ($dateNaissance.length) {
        // Configuration du reservation
        $dateNaissance.datetimepicker({
            locale: "fr",
            format: 'YYYY-MM-DD',
            minDate: moment("1902-01-01", "YYYY-MM-DD"),
            maxDate: moment(),
            viewMode: 'years'
        });
    }
}

function gestionTypeDeBilletDisabled() {
    var reservation_dateVisite = $('#reservation_dateVisite');

    if (reservation_dateVisite.length) {
        reservation_dateVisite.on('dp.change', function (e) {
            if(e.date.format('YYYY-MM-DD') == moment().format("YYYY-MM-DD") && moment().format('H') >= 14 ){
                $('#reservation_typeBillet_0').attr('disabled', 'disabled');
            }
        })
    }
};



