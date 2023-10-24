function findEmploye() {
    if (document.getElementById("inputEmploye").value === "") { // Supprimer les classes de chargement et réinitialiser le texte
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = "Information employé";
        return;
    }
    // Réinitialiser le texte et ajouter les classes de chargement
    document.getElementById("informationEmploye").innerText = "";
    document.getElementById("informationEmploye").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

    // Récupérer l'input et construire l'URL pour la requête fetch
    let input = document.getElementById("inputEmploye");
    let url = "api/get/employe/" + input.value;

    // Effectuer la requête fetch
    fetch(url).then(function (response) { // Vérifier si la réponse est OK
        if (response.ok) {
            return response.json();
        } else {
            throw new Error("Employé non trouvé");
        }
    }).then(function (employe) {
        // Supprimer les classes de chargement et afficher les informations de l'employé
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = employe.nom;
        // Active le bouton connection
        document.getElementById("btnConnexion").classList.remove('btn-disabled');
    }).catch(function (error) {
        // Supprimer les classes de chargement et afficher l'erreur
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = error.message;
        document.getElementById("btnConnexion").classList.add('btn-disabled');
    });
}

// Lorsque l'input "inputEmploye" est modifié
document.getElementById("inputEmploye").addEventListener("input", function () { // Vérifier si la valeur est vide
    findEmploye();
});
findEmploye();

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