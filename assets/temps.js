let codeEmploye;

// 
//* Détecte un changement de type d'heure
// 
document.getElementById("type").addEventListener("change", function () {
    formChange();
    ordreLabelChange();
})

document.getElementById("cbTacheSpe").addEventListener("change", function () {
    document.getElementById("tacheSpe").value = -1;
    if (document.getElementById("cbTacheSpe").checked) {
        document.getElementById("tacheSpe").disabled = false;
    } else {
        document.getElementById("tacheSpe").disabled = true;
    }

})

// 
//* Affiche les différents champs en fonction du type d'heure
// 
function formChange() {
    document.getElementById("ordre").value = "";
    document.getElementById("operation").value = "";
    document.getElementById("activite").value = "";
    document.getElementById("divTacheSpe").value = "";
    document.getElementById("tache").value = -1;
    document.getElementById("tacheSpe").value = -1;

    switch (parseInt(document.getElementById("type").value)) {
        case 1:
            tacheChange(1);
            document.getElementById("centrecharge").value = document.getElementById("CDGUser").innerHTML;
            document.getElementById("divOrdre").classList.add("hidden");
            document.getElementById("divTache").classList.remove("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            document.getElementById("divTacheSpe").classList.add("hidden");
            document.getElementById("divCentreCharge").classList.remove("hidden");
            document.getElementById("divSaisiTemps").classList.remove("hidden");
            break;
        case 2:
            document.getElementById("centrecharge").value = -1;
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.remove("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            document.getElementById("divTacheSpe").classList.remove("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            document.getElementById("divSaisiTemps").classList.remove("hidden");
            break;
        case 3:
            document.getElementById("centrecharge").value = -1;
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.remove("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            document.getElementById("divTacheSpe").classList.add("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            document.getElementById("divSaisiTemps").classList.remove("hidden");
            break;
        case 4:
            tacheChange(4);
            document.getElementById("centrecharge").value = -1;
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.remove("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.remove("hidden");
            document.getElementById("divTacheSpe").classList.add("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            document.getElementById("divSaisiTemps").classList.remove("hidden");
            break;
        default:
            document.getElementById("centrecharge").value = -1;
            document.getElementById("divOrdre").classList.add("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            document.getElementById("divTacheSpe").classList.add("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            document.getElementById("divSaisiTemps").classList.add("hidden");
            break;
    }
}

// 
//* Renvoie la liste des taches en fonction du type d'heure
// 
function tacheChange(id) {
    options = document.getElementById('tache').options;
    for (var i = 0; i < options.length; i++) {
        if (options[i].dataset.idtype != id) {
            document.getElementById('tache').options[i].hidden = true;
        }
        else {
            document.getElementById('tache').options[i].hidden = false;
        }
    }
}

// 
//* Validation du formulaire
// 
document.getElementById('btnEnregistrerQuitter').addEventListener('click', async function () {
    document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
    const state = await formSubmit();
    if (!state) {
        alert("Une erreur s'est produite")
        document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
    }
    else {
        window.location.href = '/api/post/deconnexion';
    }
})
document.getElementById('btnEnregistrerContinue').addEventListener('click', async function () {
    document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
    const state = await formSubmit();

    if (!state) {
        alert("Une erreur s'est produite")
        document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
    }
    else {
        window.location.href = '/temps';
    }
})

// 
//* Envoie les données du formulaire au serveur
// 
async function formSubmit() {
    document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

    const type_heures = document.getElementById("type").value;
    const ordre = codeEmploye + document.getElementById("ordre").value;
    const tache = document.getElementById("tache").value;
    const operation = document.getElementById("operation").value;
    const tacheSpecifique = document.getElementById("tacheSpe").value;
    const activite = document.getElementById("activite").value;
    const centre_de_charge = document.getElementById("centrecharge").value;
    const temps_main_oeuvre = document.getElementById("saisieTemps").value;
    const token = document.getElementById("saisieToken").value;
    const data = {
        'type_heures': type_heures,
        'temps_main_oeuvre': temps_main_oeuvre,
        'token': token
    }
    if (type_heures == "-1") {
        alert("Veuillez selectionner un type d'heure");
        document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

        return respnse.status = 400;
    }
    if (temps_main_oeuvre == "") {
        alert("Veuillez insérer un temps de main d'oeuvre");
        document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

        return respnse.status = 400;
    }
    if (ordre !== "") {
        data.ordre = ordre.toUpperCase();
    }
    if (tache !== "-1") {
        data.tache = tache;
    }
    if (operation !== "") {
        data.operation = operation;
    }
    if (activite !== "") {
        data.activite = activite;
    }
    if (tacheSpecifique !== "-1") {
        data.tacheSpecifique = tacheSpecifique;
    }
    if (centre_de_charge !== "-1") {
        data.centre_de_charge = centre_de_charge;
    }
    try {
        const response = await fetch("/api/post/detail_heures", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        })
        if (!response.ok) {
            return response;
        }
        return true;
    } catch (error) {
        console.error("Une erreur s'est produite :", error);
    }

    return false;
}

// 
//* Affectation du numéro de site sur l'odre
//
function ordreLabelChange(){
    codeEmploye = document.getElementById('codeEmploye').innerText.substring(0, 2);
    document.getElementById('labelOrdre').innerText = codeEmploye;
}

//
//* Vérifie la saisie de l'ordre (Notemment pour les douchettes)
//
document.getElementById("ordre").addEventListener("input", function (evt) {
    if(evt.target.value.length === 2 && evt.target.value.toUpperCase() === codeEmploye.toUpperCase()){
        evt.target.value = "";
    }
});

//
//* Effectue la RegEx pour vérifier le champs Ordre
//
let inputOrdre = document.getElementById("ordre");
let inputOrdreLabel = document.getElementById("ordre").parentElement;
document.getElementById("ordre").addEventListener("input", function () {
    let regex = new RegExp("^[0-9A-Za-z]{1}[0-9]{6}$");
    inputOrdreLabel.classList.remove("input-success");
    inputOrdreLabel.classList.remove("input-error");
    if (regex.test(inputOrdre.value)) {
        inputOrdreLabel.classList.add("input-success");
        document.getElementById("btnEnregistrerQuitter").classList.remove("btn-disabled");
        document.getElementById("btnEnregistrerContinue").classList.remove("btn-disabled");
    }
    else {
        inputOrdreLabel.classList.add("input-error");
        document.getElementById("btnEnregistrerQuitter").classList.add("btn-disabled");
        document.getElementById("btnEnregistrerContinue").classList.add("btn-disabled");
    }
    if (inputOrdre.value == "") {
        inputOrdreLabel.classList.remove("input-success");
        inputOrdreLabel.classList.remove("input-error");
        document.getElementById("btnEnregistrerQuitter").classList.remove("btn-disabled");
        document.getElementById("btnEnregistrerContinue").classList.remove("btn-disabled");
    }
})

//
//* Retourne toutes les activités
//
function makeAPIActivite() {
    var url = "/api/get/activite";
    var activiteTable = [];
    fetch(url).then(function (response) {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error("Erreur");
        }
    }).then(function (activite) {
        activite.forEach(async (unActivite) => {
            var activiteObjet = {};
            activiteObjet.id = unActivite.id;
            activiteObjet.nom = unActivite.nom;
            await activiteTable.push(activiteObjet)
        });

    }).catch(function (error) {
        console.log(error);
    });
    return activiteTable;
}

var tableActivite = makeAPIActivite();

//
//* Effectue la RegEx pour vérifier le champs Activité et afffiche le nom de l'activité
//
document.getElementById("activite").addEventListener("input", function () {
    inputActivite = document.getElementById("activite");
    regex = new RegExp("^[0-9]{3}$");
    inputActivite.classList.remove("input-success");
    inputActivite.classList.remove("input-error");
    if (regex.test(inputActivite.value) && tableActivite.find((e) => e.id == inputActivite.value)) {
        const activiteTrouve = tableActivite.find((e) => e.id == inputActivite.value);
        inputActivite.classList.add("input-success");
        document.getElementById("infoActivite").innerText = activiteTrouve.nom;
        document.getElementById("btnEnregistrerQuitter").classList.remove("btn-disabled");
        document.getElementById("btnEnregistrerContinue").classList.remove("btn-disabled");
    }
    else {
        inputActivite.classList.add("input-error");
        document.getElementById("infoActivite").innerText = "";
        document.getElementById("btnEnregistrerQuitter").classList.add("btn-disabled");
        document.getElementById("btnEnregistrerContinue").classList.add("btn-disabled");
    }
    if (inputActivite.value == "") {
        inputActivite.classList.remove("input-success");
        inputActivite.classList.remove("input-error");
        document.getElementById("infoActivite").innerText = "";
        document.getElementById("btnEnregistrerQuitter").classList.remove("btn-disabled");
        document.getElementById("btnEnregistrerContinue").classList.remove("btn-disabled");
    }
})