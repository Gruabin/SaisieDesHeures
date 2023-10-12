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
        document.getElementById("informationEmploye").innerText = employe.nom;
    }).catch(function (error) { // Supprimer les classes de chargement et afficher l'erreur
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = error.message;
    });
});





// Tableau pour stocker les employés
var employeTable = [];

// Variable de recherche
var recherche = true;

// Variable pour vérifier si une recherche a été effectuée
var a = false;

// Écouteur d'événement pour la saisie de l'employé
document.getElementById("inputEmploye2").addEventListener("input", function () {
    // Vérifie la longueur de la valeur saisie
    if (this.value.length == 0 || this.value.length == 1 || this.value.length == 2 || this.value == "") {
        recherche = true;
    }

    // Vérifie si la valeur est vide
    if (this.value == "") {
        document.getElementById("informationEmploye2").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye2").innerText = "Information employé";
        return;
    }

    // Recherche l'employé par ID
    if (this.value.length == 9 && recherche) {
        var url = "api/get/employe/" + this.value;
        recherche = false;
        a = false;

        fetch(url).then(function (response) {
            document.getElementById("informationEmploye2").innerText = "";
            document.getElementById("informationEmploye2").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Employé non trouvé");
            }
        }).then(function (employe) {
            document.getElementById("informationEmploye2").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            document.getElementById("informationEmploye2").innerText = employe.nom;
        }).catch(function (error) {
            document.getElementById("informationEmploye2").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            document.getElementById("informationEmploye2").innerText = error.message;
        });

        recherche = true;
    }

    // Recherche l'employé par un critère de recherche
    if (this.value.length > 2) {
        if (this.value.length == 3) {
            recherche = false;
            var url = "api/get/employe2/" + this.value;

            fetch(url).then(function (response) {
                document.getElementById("informationEmploye2").innerText = "";
                document.getElementById("informationEmploye2").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
                a = true;

                if (response.ok) {
                    return response.json();
                } else {
                    document.getElementById("informationEmploye2").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
                    document.getElementById("informationEmploye2").innerText = "Aucun employé correspondant";
                    throw new Error("Employé non trouvé");
                }
            }).then(function (employe) {
                employe.forEach((unEmploye) => {
                    var employeObjet = {};
                    console.log(unEmploye);
                    employeObjet.id = unEmploye.id;
                    employeObjet.nom = unEmploye.nom;
                    employeTable.push(employeObjet);
                });

                a = true;
            }).catch(function (error) {
                console.log(error);
            });
        }

        // Vérifie si l'employé existe dans le tableau
        if (employeTable.find((e) => e.id === this.value)) {
            const employeTrouve = employeTable.find((e) => e.id === this.value);
            document.getElementById("informationEmploye2").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            document.getElementById("informationEmploye2").innerText = employeTrouve.nom;
        } else {
            if (a) {
                document.getElementById("informationEmploye2").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
                document.getElementById("informationEmploye2").innerText = "Employé inexistant";
            } else {
                document.getElementById("informationEmploye2").innerText = "";
                document.getElementById("informationEmploye2").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            }
        }
    }
});