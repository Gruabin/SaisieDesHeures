function formChange() {
    switch (parseInt(document.getElementById("type").value)) {
        case 0:
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.remove("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            document.getElementById("divSaisiTemps").classList.remove("hidden");
            break;
        case 1:
            document.getElementById("divOrdre").classList.add("hidden");
            document.getElementById("divTache").classList.remove("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            if (parseInt(document.getElementById("tache").value) === 111) {
                document.getElementById("divCentreCharge").classList.remove("hidden");
            }
            document.getElementById("divSaisiTemps").classList.remove("hidden");
            break;
        case 2:
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.remove("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            document.getElementById("divSaisiTemps").classList.remove("hidden");
            break;
        case 3:
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.remove("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.remove("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            document.getElementById("divSaisiTemps").classList.remove("hidden");
            break;
        default:
            document.getElementById("divOrdre").classList.add("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            document.getElementById("divSaisiTemps").classList.add("hidden");
            break;
    }
}

async function formSubmit() {
    document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

    const type_heures = document.getElementById("type").value;
    const ordre = document.getElementById("ordre").value;
    const tache = document.getElementById("tache").value;
    const operation = document.getElementById("operation").value;
    const activite = document.getElementById("activite").value;
    const centre_de_charge = document.getElementById("centrecharge").value;
    const temps_main_oeuvre = document.getElementById("saisitemps").value;
    const token = document.getElementById("saisieToken").value;
    const data = {
        'type_heures': type_heures,
        'temps_main_oeuvre': temps_main_oeuvre,
        'token': token
    }
    if (ordre !== "") {
        data.ordre = ordre;
    }
    if (tache !== "") {
        data.tache = tache;
    }
    if (operation !== "") {
        data.operation = operation;
    }
    if (activite !== "") {
        data.activite = activite;
    }
    if (centre_de_charge !== "-1") {
        data.centre_de_charge = centre_de_charge;
    }
    const response = await fetch("/api/post/detail_heures", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    })
    document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
    if (response.status === 201) {
        return true
    }
    if (response.status !== 201) {
        alert("Réponse inattendue du serveur");
        return false
    }
    throw new Error("Réponse inattendue du serveur");
}

document.getElementById("type").addEventListener("change", function () {
    formChange();
})

document.getElementById("tache").addEventListener("change", function () {
    formChange();
})

document.getElementById('btnEnregistrerQuitter').addEventListener('click', async function () {
    const state = await formSubmit();
    if (state) {
        document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        window.location.href = '/api/post/deconnexion';
    }
})

document.getElementById('btnEnregistrerContinue').addEventListener('click', async function () {
    const state = await formSubmit();
    if (state) {
        document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        window.location.href = '/temps';
    }
})


// Tableau pour stocker les taches
let tacheTable = [];

// Lorsque le DOM est chargé
document.addEventListener("DOMContentLoaded", function () { // Construire l'URL pour la requête fetch
    const url = "api/get/tache/";

    // Effectuer la requête fetch
    fetch(url).then(function (response) { // Vérifier si la réponse est OK
        if (response.ok) {
            return response.json();
        } else {
            throw new Error("Employé non trouvé");
        }
    }).then(function (tache) { // Parcourir les taches et les ajouter au tableau
        tache.forEach((uneTache) => {
            document.getElementById("tache").disabled = false;
            let tacheObjet = {};
            tacheObjet.id = uneTache.id;
            tacheObjet.nom = uneTache.nom;
            tacheTable.push(tacheObjet);
        });
    }).catch(function (error) {
        console.log(error);
    });

    // Désactiver l'input "infoTache"
    document.getElementById("tache").disabled = true;
});

document.getElementById("tache").addEventListener("change", function () {
    if (this.value == "") { // Supprimer les classes de chargement et réinitialiser le texte
        document.getElementById("infoTache").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("infoTache").innerText = "Information employé";
        return;
    }
    // Réinitialiser le texte et ajouter les classes de chargement
    document.getElementById("infoTache").innerText = "";
    document.getElementById("infoTache").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

    // Récupérer l'input et vérifier si l'employé existe dans le tableau
    if (tacheTable.find((e) => e.id === parseInt(this.value))) { // Si l'employé existe, afficher ses informations
        const tacheTrouve = tacheTable.find((e) => e.id === parseInt(this.value));
        document.getElementById("infoTache").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("infoTache").innerText = tacheTrouve.nom;
    } else { // Si l'employé n'existe pas, afficher un message d'erreur
        document.getElementById("infoTache").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("infoTache").innerText = "Tache non trouvé";
    }
});
