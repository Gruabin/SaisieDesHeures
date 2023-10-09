

document.getElementById("type").addEventListener("change", function () {
    switch (parseInt(this.value)) {
        case 0:
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.remove("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            break;
        case 1:
            document.getElementById("divOrdre").classList.add("hidden");
            document.getElementById("divTache").classList.remove("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            break;
        case 2:
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.remove("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            break;
        case 3:
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.remove("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.remove("hidden");
            break;
        default:
            document.getElementById("divOrdre").classList.add("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            break;
    }
});



// Tableau pour stocker les taches
var tacheTable = [];

// Lorsque le DOM est chargé
document.addEventListener("DOMContentLoaded", function () { // Construire l'URL pour la requête fetch
    var url = "api/get/tache/";

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
            var tacheObjet = {};
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

//     document.getElementById("info").innerText = "";
//     document.getElementById("info").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

// document.getElementById("info").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
//             document.getElementById("info").innerText = "test";
