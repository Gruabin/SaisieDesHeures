// 
//* Détecte un changement de type d'heure
// 
document.getElementById("type").addEventListener("change", function () {
    formChange();
})

document.getElementById("cbTacheSpe").addEventListener("change", function () {
    document.getElementById("tacheSpe").value = -1;
    if (document.getElementById("cbTacheSpe").checked) {
        document.getElementById("tacheSpe").disabled = false;
    }else{
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
            document.getElementById("divCentreCharge").classList.add("hidden");
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
    if (state.status != 400 && state.status != 500) {
        window.location.href = '/api/post/deconnexion';
    }
    else {
        alert("Une erreur s'est produite")
    }
    document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
})
document.getElementById('btnEnregistrerContinue').addEventListener('click', async function () {
    document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
    const state = await formSubmit();

    if (state.status != 400 && state.status != 500) {
        window.location.href = '/temps';
    }
    else{
        alert("Une erreur s'est produite")
    }
    document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
})

// 
//* Envoie les données du formulaire au serveur
// 
async function formSubmit() {
    document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

    const type_heures = document.getElementById("type").value;
    const ordre = document.getElementById("ordre").value;
    const tache = document.getElementById("tache").value;
    const operation = document.getElementById("operation").value;
    const tacheSpecifique = document.getElementById("tacheSpe").value;
    const activite = document.getElementById("activite").value;
    const centre_de_charge = document.getElementById("centrecharge").value;
    const temps_main_oeuvre = document.getElementById("saisitemps").value;
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
        data.ordre = ordre;
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
        document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        if (!response.ok) {
            document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue")
            return response;
        }
        return true;
    } catch (error) {
        document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue")
        console.error("Une erreur s'est produite :", error);
    }

    return false;
}

// 
//* Affiche les centres de charges si la tache 111 est sélectionné
// 
document.getElementById("tache").addEventListener("change", function () {
    if (parseInt(document.getElementById("tache").value) === 111) {
        document.getElementById("divCentreCharge").classList.remove("hidden");
    } else {
        document.getElementById("divCentreCharge").classList.add("hidden");
        document.getElementById("centrecharge").value = document.getElementById("CDGUser").innerHTML;
    }
})

//
//* Effectue la RegEx pour vérifier le champs Ordre
//
inputOrdre = document.getElementById("ordre");
document.getElementById("ordre").addEventListener("input", function () {
    regex = new RegExp("^[0-9A-Z]{9}$");
    inputOrdre.classList.remove("input-success");
    inputOrdre.classList.remove("input-error");
    if (regex.test(inputOrdre.value)) {
        inputOrdre.classList.add("input-success");
        document.getElementById("btnEnregistrerQuitter").classList.remove("btn-disabled");
        document.getElementById("btnEnregistrerContinue").classList.remove("btn-disabled");
    }
    else {
        inputOrdre.classList.add("input-error");
        document.getElementById("btnEnregistrerQuitter").classList.add("btn-disabled");
        document.getElementById("btnEnregistrerContinue").classList.add("btn-disabled");
    }
    if (inputOrdre.value == "") {
        inputOrdre.classList.remove("input-success");
        inputOrdre.classList.remove("input-error");
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