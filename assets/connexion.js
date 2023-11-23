// Tableau pour stocker les employés
var employeTable = [];

// Variable de recherche
var recherche = true;

// Variable pour vérifier si une recherche a été effectuée
var a = false;

// Lorsque l'input "inputEmploye" est modifié
document.getElementById("inputEmploye").addEventListener("input", function () { // Vérifier si la valeur est vide
    findEmploye();
});

function findEmploye() {

    inputEmploye = document.getElementById("inputEmploye");
    // Vérifie la longueur de la valeur saisie
    if (inputEmploye.value.length < 3 || inputEmploye.value == "") {
        recherche = true;
    }

    // Vérifie si la valeur est vide
    if (inputEmploye.value == "") {
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = "Information employé";
        return;
    }

    // Recherche l'employé par ID Entier
    if (inputEmploye.value.length == 9 && recherche) {
        var url = "api/get/employe/" + inputEmploye.value;
        recherche = false;
        a = false;

        fetch(url).then(function (response) {
            document.getElementById("btnConnexion").classList.add("btn-disabled");
            document.getElementById("informationEmploye").innerText = "";
            document.getElementById("informationEmploye").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Employé non trouvé");
            }
        }).then(function (employe) {
            document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            document.getElementById("informationEmploye").innerText = employe.nom;
            document.getElementById("btnConnexion").classList.remove("btn-disabled");
        }).catch(function (error) {
            document.getElementById("btnConnexion").classList.add("btn-disabled");
            document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            document.getElementById("informationEmploye").innerText = error.message;
        });

        recherche = true;
    }

    // Recherche en BDD lorsque les 3 premières caractères sont écrits en fonction d'eux-même
    if (inputEmploye.value.length > 2) {
        if (inputEmploye.value.length == 3) {
            recherche = false;
            var url = "api/get/employe2/" + inputEmploye.value;

            fetch(url).then(function (response) {
                document.getElementById("btnConnexion").classList.add("btn-disabled");
                document.getElementById("informationEmploye").innerText = "";
                document.getElementById("informationEmploye").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
                a = true;

                if (response.ok) {
                    return response.json();
                } else {
                    document.getElementById("btnConnexion").classList.add("btn-disabled");
                    document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
                    document.getElementById("informationEmploye").innerText = "Aucun employé correspondant";
                    throw new Error("Employés non trouvés");
                }
            }).then(function (employe) {
                employe.forEach(async (unEmploye) => {
                    var employeObjet = {};
                    employeObjet.id = unEmploye.id;
                    employeObjet.nom = unEmploye.nom;
                    await employeTable.push(employeObjet);
                });

                a = true;
                if (employeTable.find((e) => e.id === inputEmploye.value)) {
                    const employeTrouve = employeTable.find((e) => e.id === inputEmploye.value);
                    document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
                    document.getElementById("informationEmploye").innerText = employeTrouve.nom;
                    document.getElementById("btnConnexion").classList.remove("btn-disabled");
                }
            }).catch(function (error) {
                console.log(error);
            });
        }

        // Vérifie si l'employé existe dans le tableau
        if (employeTable.find((e) => e.id === inputEmploye.value)) {
            const employeTrouve = employeTable.find((e) => e.id === inputEmploye.value);
            document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            document.getElementById("informationEmploye").innerText = employeTrouve.nom;
            document.getElementById("btnConnexion").classList.remove("btn-disabled");
        } else {
            document.getElementById("btnConnexion").classList.add("btn-disabled");
            if (a) {
                document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
                document.getElementById("informationEmploye").innerText = "Employé inexistant";
            } else {
                document.getElementById("informationEmploye").innerText = "";
                document.getElementById("informationEmploye").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            }
        }
    }

}


document.getElementById("btnConnexion").addEventListener("click", function () {
    if (!document.getElementById("btnConnexion").classList.contains('btn-disabled')) {
        document.getElementById("informationEmploye").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

        // Récupérez l'ID de l'utilisateur depuis le champ input
        const idEmploye = document.getElementById("inputEmploye").value;
        const token = document.getElementById("loginToken").value;

        // Créez un objet JSON avec l'ID de l'utilisateur
        const data = {
            id: idEmploye,
            token: token
        };

        // Envoyez la requête AJAX
        fetch("/api/post/connexion", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        })
            .then((response) => {
                if (response.status === 404) {
                    // Gérer le cas où la réponse est un statut 404
                    return response.json().then((errorData) => {
                        document.getElementById("boxAlertMessage").innerHTML = errorData.message;
                        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
                    });
                } else if (response.status === 200) {
                    // Rediriger l'utilisateur en cas de succès
                    window.location.href = "/temps";
                } else {
                    // Gérer d'autres statuts d'erreur ici
                    throw new Error("Réponse inattendue du serveur");
                }
            })
            .catch((error) => {
                // Afficher un message d'erreur
                alert(error);
            });
    }
})