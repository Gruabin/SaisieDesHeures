
try {

    document.getElementById("connexion_id").addEventListener("input", function() {
        // Vérifier si la valeur est vide
        findEmploye();
    });

} catch (error) {

    // Variable de contrôle pour savoir si l'event listener est actif
    let eventListenerActive = false;

    // Sélectionne la balise turbo-frame avec l'ID "connexion_id"
    const frame = document.querySelector("#identification");

    // Crée une instance de MutationObserver avec une fonction de rappel
    const observer = new MutationObserver(function (mutationsList, observer) {
        // Il y a eu un changement dans l'architecture HTML de la balise turbo-frame
        if (!eventListenerActive) {
            // Ajoute l'event listener uniquement si celui-ci n'est pas déjà actif
            document.getElementById("connexion_id").addEventListener("input", function () {
                findEmploye();
            });
            eventListenerActive = true;
        }
    });

    // Configure les options de l'observateur pour observer les modifications des enfants et des attributs de la balise turbo-frame
const config = { childList: true, subtree: true };

    // Commence à observer les mutations dans la balise turbo-frame avec les options spécifiées
    observer.observe(frame, config);
}

function findEmploye() {

    inputEmploye = document.getElementById("connexion_id").value.toUpperCase();

    // Vérifie si la valeur est vide
    if (inputEmploye.length !== 9) {
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = "Non identifié";
        document.getElementById("connexion_bouton").classList.add("btn-disabled");
        document.getElementById("connexion_id").classList.remove('input-success', 'input-error', 'input-primary');
        document.getElementById("connexion_id").disabled = false;
        return;
    }

    // Recherche l'employé par matricule Entier
    if (inputEmploye.length == 9) {
        var url = "api/get/employe/" + inputEmploye;

        document.getElementById("connexion_bouton").classList.add("btn-disabled");
        document.getElementById("connexion_bouton").disabled = true;
        document.getElementById("informationEmploye").innerText = "";
        document.getElementById("informationEmploye").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("connexion_id").classList.add('input-primary');
        document.getElementById("connexion_id").classList.remove('input-success', 'input-error');
        document.getElementById("connexion_id").disabled = true;

        fetch(url, {
            headers: {
                'X-API-Key': '^^u6#h289SrB$!DxDDms55reFZcwWoY2e93TcseYf8^URbaZ%!CS^cHD^6YfyX!e4Lo@oPg3&u8b7dzA*Q9PYCdBRVRVGut3r2$JT2J9kU*FNKbmQ$@8oxtE5!mp7m8#'
            }
        }).then(function (response) {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Employé non trouvé");
            }
        }).then(function (employe) {
            document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            document.getElementById("informationEmploye").innerText = employe.nom;
            document.getElementById("connexion_bouton").classList.remove("btn-disabled");
            document.getElementById("connexion_bouton").disabled = false;
            document.getElementById("connexion_id").classList.add('input-success');
            document.getElementById("connexion_id").classList.remove('input-primary', 'input-error');
            document.getElementById("connexion_id").disabled = false;
        }).catch(function (error) {
            document.getElementById("connexion_bouton").classList.add("btn-disabled");
            document.getElementById("connexion_bouton").disabled = true;
            document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            document.getElementById("informationEmploye").innerText = error.message;
            document.getElementById("connexion_id").classList.add('input-error');
            document.getElementById("connexion_id").classList.remove('input-primary', 'input-success');
            document.getElementById("connexion_id").disabled = false;
        });
    }
}