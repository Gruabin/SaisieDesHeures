// Lorsque l'input "inputEmploye" est modifié
document.getElementById("inputEmploye").addEventListener("input", function () { // Vérifier si la valeur est vide
    if (this.value == "") { // Supprimer les classes de chargement et réinitialiser le texte
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = "Information employé";
        return;
    }
    // Réinitialiser le texte et ajouter les classes de chargement
    document.getElementById("informationEmploye").innerText = "";
    document.getElementById("informationEmploye").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

    // Récupérer l'input et construire l'URL pour la requête fetch
    var input = document.getElementById("inputEmploye");
    var url = "api/get/employe/" + input.value;

    // Effectuer la requête fetch
    fetch(url).then(function (response) { // Vérifier si la réponse est OK
        if (response.ok) {
            return response.json();
        } else {
            throw new Error("Employé non trouvé");
        }
    }).then(function (employe) { // Supprimer les classes de chargement et afficher les informations de l'employé
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = employe.nom + " " + employe.prenom;
    }).catch(function (error) { // Supprimer les classes de chargement et afficher l'erreur
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = error.message;
    });
});

// Tableau pour stocker les employés
var employeTable = [];

// Lorsque le DOM est chargé
document.addEventListener("DOMContentLoaded", function () { // Construire l'URL pour la requête fetch
    var url = "api/get/employe2/";

    // Effectuer la requête fetch
    fetch(url).then(function (response) { // Vérifier si la réponse est OK
        if (response.ok) {
            return response.json();
        } else {
            throw new Error("Employé non trouvé");
        }
    }).then(function (employe) { // Parcourir les employés et les ajouter au tableau
        employe.forEach((unEmploye) => {
            document.getElementById("inputEmploye2").disabled = false;
            var employeObjet = {};
            employeObjet.id = unEmploye.id;
            employeObjet.nom = unEmploye.nom;
            employeObjet.prenom = unEmploye.prenom;
            employeTable.push(employeObjet);
        });
    }).catch(function (error) {
        console.log(error);
    });

    // Désactiver l'input "inputEmploye2"
    document.getElementById("inputEmploye2").disabled = true;
});

// Lorsque l'input "inputEmploye2" est modifié
document.getElementById("inputEmploye2").addEventListener("input", function () { // Vérifier si la valeur est vide
    if (this.value == "") { // Supprimer les classes de chargement et réinitialiser le texte
        document.getElementById("informationEmploye2").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye2").innerText = "Information employé";
        return;
    }
    // Réinitialiser le texte et ajouter les classes de chargement
    document.getElementById("informationEmploye2").innerText = "";
    document.getElementById("informationEmploye2").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

    // Récupérer l'input et vérifier si l'employé existe dans le tableau
    if (employeTable.find((e) => e.id === parseInt(this.value))) { // Si l'employé existe, afficher ses informations
        const employeTrouve = employeTable.find((e) => e.id === parseInt(this.value));
        document.getElementById("informationEmploye2").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye2").innerText = employeTrouve.nom + " " + employeTrouve.prenom;
    } else { // Si l'employé n'existe pas, afficher un message d'erreur
        document.getElementById("informationEmploye2").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye2").innerText = "Employé non trouvé";
    }
});
