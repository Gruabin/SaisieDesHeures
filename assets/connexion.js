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

    inputEmploye = document.getElementById("inputEmploye").value.toUpperCase();
    // Vérifie la longueur de la valeur saisie
    if (inputEmploye.length < 3 || inputEmploye == "") {
        recherche = true;
    }

    // Vérifie si la valeur est vide
    if (inputEmploye.value == "") {
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = "Information employé";
        return;
    }

    // Recherche l'employé par ID Entier
    if (inputEmploye.length == 9 && recherche) {
        var url = "api/get/employe/" + inputEmploye;
        recherche = false;
        a = false;

        fetch(url, {
            headers: {
                'X-API-Key': '^^u6#h289SrB$!DxDDms55reFZcwWoY2e93TcseYf8^URbaZ%!CS^cHD^6YfyX!e4Lo@oPg3&u8b7dzA*Q9PYCdBRVRVGut3r2$JT2J9kU*FNKbmQ$@8oxtE5!mp7m8#'

            }
        }).then(function (response) {
            document.getElementById("btnConnexion").classList.add("btn-disabled");
            document.getElementById("btnConnexion").disabled = true;
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
            document.getElementById("btnConnexion").disabled = false;
        }).catch(function (error) {
            document.getElementById("btnConnexion").classList.add("btn-disabled");
            document.getElementById("btnConnexion").disabled = true;
            document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            document.getElementById("informationEmploye").innerText = error.message;
        });

        recherche = true;
    }

    // Recherche en BDD lorsque les 3 premières caractères sont écrits en fonction d'eux-même
    if (inputEmploye.length > 2) {
        if (inputEmploye.length == 3) {
            recherche = false;
            var url = "api/get/employe2/" + inputEmploye

            fetch(url, {
                headers: {
                    'X-API-Key': '*Q4mZWWphxjuBbcUU6YGWiLwddsFtQxBPDGwP#EwmB5KdmU^UgZYcV3h5puz@cg84YPYX&vmd%obs5$x9sRw58PUSk!iNZSfhzCssYB&5H#9fdFzFuaUUah7QVH8KenB'
                }
            }).then(function (response) {
                document.getElementById("btnConnexion").classList.add("btn-disabled");
                document.getElementById("btnConnexion").disabled = true;
                document.getElementById("informationEmploye").innerText = "";
                document.getElementById("informationEmploye").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
                a = true;

                if (response.ok) {
                    return response.json();
                } else {
                    document.getElementById("btnConnexion").classList.add("btn-disabled");
                    document.getElementById("btnConnexion").disabled = true;
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
                if (employeTable.find((e) => e.id === inputEmploye)) {
                    const employeTrouve = employeTable.find((e) => e.id === inputEmploye);
                    document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
                    document.getElementById("informationEmploye").innerText = employeTrouve.nom;
                    document.getElementById("btnConnexion").classList.remove("btn-disabled");
                    document.getElementById("btnConnexion").disabled = false;
                }
            }).catch(function (error) {
                console.log(error);
            });
        }

        // Vérifie si l'employé existe dans le tableau
        if (employeTable.find((e) => e.id === inputEmploye)) {
            const employeTrouve = employeTable.find((e) => e.id === inputEmploye);
            document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            document.getElementById("informationEmploye").innerText = employeTrouve.nom;
            document.getElementById("btnConnexion").classList.remove("btn-disabled");
            document.getElementById("btnConnexion").disabled = false;
        } else {
            document.getElementById("btnConnexion").classList.add("btn-disabled");
            document.getElementById("btnConnexion").disabled = true;
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

// 
//* Connexion
// 
document.getElementById("btnConnexion").addEventListener("click", function () {
    submitForm();
})

document.addEventListener("keydown", function (event) {
    if (event.key === "Enter") {
        submitForm();
    }
})

function submitForm() {
    if (!document.getElementById("btnConnexion").classList.contains('btn-disabled')) {
        document.getElementById("informationEmploye").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

        // Récupérez l'ID de l'utilisateur depuis le champ input
        const idEmploye = document.getElementById("inputEmploye").value.toUpperCase();
        const token = document.getElementById("loginToken").value;

        // Créez un objet JSON avec l'ID de l'utilisateur
        const data = {
            id: idEmploye,
            token: token
        };
        // Envoyez la requête AJAX
        fetch("/api/post/connexion", {
            method: "POST",
            credentials: 'same-origin',
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        }).then((response) => {
            if (response.status === 200) {
                // Rediriger l'utilisateur en cas de succès
                window.location.href = "/temps";
            } else {
                console.log(response);
                // Gérer d'autres statuts d'erreur ici
                document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
                throw new Error("Réponse inattendue du serveur");
            }
        }).catch((error) => {
            // Afficher un message d'erreur
            console.log(response);
            // Gérer d'autres statuts d'erreur ici
            document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            throw new Error("Réponse inattendue du serveur");
        });
    }
}