

const buttons = document.querySelectorAll('.check, .xmark, .pen');
buttons.forEach(button => {
    button.addEventListener('mouseover', function () {
        if (button.classList.contains('check')) {
            button.classList.add('text-success');
        } else if (button.classList.contains('xmark')) {
            button.classList.add('text-accent');
        } else if (button.classList.contains('pen')) {
            button.classList.add('text-secondary');
        }
    });

    button.addEventListener('mouseout', function () {
        button.classList.remove('text-success', 'text-accent', 'text-secondary');
    });
});

// 
// * Sélectionne tout les éléments
// 
let checkbox = document.getElementById("select_all");
if (!!checkbox) {
    checkbox.addEventListener("click", (event) => {
        let checkboxDiv = document.getElementById("select_all_checkboxes");

        if (event.target.checked) {
            checkboxDiv.querySelectorAll("input[type=checkbox]").forEach((item) => {
                if (item.checked === false) {
                    item.click();
                }
            });
        } else {
            checkboxDiv.querySelectorAll("input[type=checkbox]").forEach((item) => {
                if (item.checked === true) {
                    item.click();
                }
            });
        }
    });
}

// 
// * Déclenche l'envoie des données valides
// 
let ligne = document.querySelectorAll('.ligne');
let donnees = [];
document.getElementById('validation').addEventListener('click', function () {
    document.getElementById('validation').classList.add("invisible");
    document.getElementById('quitter').classList.add("invisible");
    document.getElementById("loading").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

    ligne.forEach(element => {
        if (element.querySelector('input[type=checkbox]').checked) {
            donnees.push(element.dataset.idligne)

        }
    })
    const token = document.getElementById("approbationToken").value;
    const data = {
        id: donnees,
        token: token
    };
    fetch("api/post/approuverLigne",
        {
            method: 'POST',
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        }
    ).then((response) => {
        if (!response.ok) {
            document.getElementById('validation').classList.remove("invisible");
            document.getElementById('quitter').classList.remove("invisible");
            document.getElementById("loading").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            throw new Error("Réponse inattendue du serveur");
        }
        window.location.href = '/console';
    }).catch((error) => {
        document.getElementById('validation').classList.remove("invisible");
        document.getElementById('quitter').classList.remove("invisible");
        document.getElementById("loading").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        window.location.href = '/console';
        throw new Error("Réponse inattendue du serveur");
    });
});

ligne.forEach(element => {

    //
    // * Affiche le formulaire sur une ligne
    //
    element.querySelector('#pen').addEventListener("click", () => {
        element.querySelector('#pen').classList.add('hidden');
        element.querySelector('#trash').classList.add('hidden');
        element.querySelector('#check').classList.remove('hidden');
        element.querySelector('#xmark').classList.remove('hidden');
    });


    //
    // * Supprimmer une ligne
    //

    // Ferme la modal
    document.getElementById('btnModalAnnuler').addEventListener("click", () => {
        document.getElementById('modalSuppr').close();
    });
    //  Ouvre la modal
    element.querySelector('#trash').addEventListener("click", () => {
        document.getElementById('modalSuppr').showModal();
        ligneASupprimer = element
    });
});

// Supprime la ligne
document.getElementById('btnModalSuppr').addEventListener("click", () => {
    APISuppression(ligneASupprimer);
});

//
// * Supprime une ligne
//
function APISuppression(ligneASupprimer) {

    token = ligneASupprimer.querySelector('#ligneToken').value;
    document.getElementById('btnModalSuppr').classList.add("invisible");
    document.getElementById('btnModalAnnuler').classList.add("invisible");
    document.getElementById("modalLoading").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

    const data = {
        id: ligneASupprimer.dataset.idligne,
        token: token
    };
    fetch("api/post/supprimerligne",
        {
            method: 'POST',
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        }
    ).then((response) => {
        if (response.ok) {
            if (ligneASupprimer.dataset.statut == 2) {
                document.getElementById("nbAnomalie").innerHTML = parseInt(document.getElementById("nbAnomalie").innerHTML) - 1;
            }
            ligneASupprimer.remove();
            // element = null;
            addToastSuccess("Saisie supprimée");
        } else {
            addToastErreur("Erreur lors de la suppression de la saisie");
        }
        document.getElementById('btnModalSuppr').classList.remove("invisible");
        document.getElementById('btnModalAnnuler').classList.remove("invisible");
        document.getElementById("modalLoading").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById('modalSuppr').close();
    }).catch((error) => {
        throw new Error("Réponse inattendue du serveur");
    });
}

// 
// * Affiche un toast de succès
// 
function addToastSuccess(message) {
    const toastHTML = `
        <div class="toast" id="toast-success">
            <div role="alert" class="alert alert-success">
                <i class="fa-regular fa-circle-check"></i>
                <span>`+ message + `</span>
                <button onclick="this.parentNode.parentNode.remove()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>`;
    document.body.insertAdjacentHTML('beforeend', toastHTML);
}

// 
// * Affiche un toast d'erreur
//
function addToastErreur(message) {
    const toastHTML = `
        <div class="toast" id="toast-error">
            <div role="alert" class="alert alert-error">
                <i class="fa-regular fa-circle-exclamation"></i>
                <span>`+ message + `</span>
                <button onclick="this.parentNode.parentNode.remove()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>`;
    document.body.insertAdjacentHTML('beforeend', toastHTML);
}