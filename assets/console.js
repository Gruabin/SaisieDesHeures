let ligne = document.querySelectorAll('.ligne');
ligne.forEach(element => {

    //
    // * Affiche le formulaire sur une ligne
    //
    element.querySelector('#pen').addEventListener("click", () => {
        element.querySelector('#pen').classList.add('hidden');
        element.querySelector('#trash').classList.add('hidden');
        element.querySelector('#check').classList.remove('hidden');
        element.querySelector('#xmark').classList.remove('hidden');
        formModif(element);
    });
    element.querySelector('#xmark').addEventListener("click", () => {
        element.querySelector('#pen').classList.remove('hidden');
        element.querySelector('#trash').classList.remove('hidden');
        element.querySelector('#check').classList.add('hidden');
        element.querySelector('#xmark').classList.add('hidden');
        resetModif(element);
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


    //
    // * Modifier une ligne
    //
    element.querySelector('#check').addEventListener("click", () => {
        
        // await APIModifier(element);
        modifierLigne(element);
    });

});

// Supprime la ligne
document.getElementById('btnModalSuppr').addEventListener("click", () => {
    APISuppression(ligneASupprimer);
});


// 
// * Gestion des couleurs des boutons du tableau
// 
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
// * Sélection de tout les checkboxs
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
// * Déclenche l'envoie des données valides pour approbation
// 
let donnees = [];
document.getElementById('validation').addEventListener('click', function () {
    document.getElementById('validation').classList.add("hidden");
    document.getElementById('quitter').classList.add("hidden");
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
            document.getElementById('validation').classList.remove("hidden");
            document.getElementById('quitter').classList.remove("hidden");
            document.getElementById("loading").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            throw new Error("Réponse inattendue du serveur");
        }
        window.location.href = '/console';
    }).catch((error) => {
        document.getElementById('validation').classList.remove("hidden");
        document.getElementById('quitter').classList.remove("hidden");
        document.getElementById("loading").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        window.location.href = '/console';
        throw new Error("Réponse inattendue du serveur");
    });
});


//
// * Envoie le requête de suppression
//
function APISuppression(ligneASupprimer) {

    token = ligneASupprimer.querySelector('#ligneToken').value;
    document.getElementById('btnModalSuppr').classList.add("hidden");
    document.getElementById('btnModalAnnuler').classList.add("hidden");
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
        document.getElementById('btnModalSuppr').classList.remove("hidden");
        document.getElementById('btnModalAnnuler').classList.remove("hidden");
        document.getElementById("modalLoading").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById('modalSuppr').close();
    }).catch((error) => {
        throw new Error("Réponse inattendue du serveur");
    });
}


//
// * Affiche la modification d'une ligne
//
function formModif(element) {
    element.querySelectorAll('.form').forEach((form) => {

        form.classList.remove("hidden");
    });
    element.querySelectorAll('.texte').forEach((form) => {
        form.classList.add("hidden");
    });

}

// 
// * Cache la modification d'une ligne
//
function resetModif(element) {
    element.querySelectorAll('.form').forEach((form) => {

        form.classList.add("hidden");
    });
    element.querySelectorAll('.texte').forEach((form) => {
        form.classList.remove("hidden");
    });

}


function modifierLigne(element) {
    element.querySelectorAll('.form').forEach((form) => {

    });
}

// function async APIModifier(element) {
//     const token = element.querySelector('#ligneToken').value;
//     const data = {
//         id: element.dataset.idligne,
//         ordre: element.querySelector('#ordre').value,
//         commentaire: element.querySelector('#commentaire').value,
//         token: token
//     };
//     fetch("api/post/modifierLigne",
//         {
//             method: 'POST',
//             headers: {
//                 "Content-Type": "application/json"
//             },
//             body: JSON.stringify(data)
//         }
//     ).then((response) => {
//         if (response.ok) {
//             element.querySelector('.form').forEach((form) => {
//                 form.classList.add("hidden");
//             });
//             element.querySelector('.texte').forEach((form) => {
//                 form.classList.remove("hidden");
//             });
//             addToastSuccess("Saisie modifiée");
//         } else {
//             addToastErreur("Erreur lors de la modification de la saisie");
//         }
//     }).catch((error) => {
//         throw new Error("Réponse inattendue du serveur");
//     });
// }


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


//
//* Effectue la RegEx pour vérifier le champs Ordre
//
document.querySelectorAll("#ordre").forEach(element => {
    element.addEventListener("input", function () {
        regex = new RegExp("^[0-9A-Z]{9}$");
        element.classList.remove("input-success");
        element.classList.remove("input-error");
        if (regex.test(element.value)) {
            element.classList.add("input-success");
        }
        else {
            element.classList.add("input-error");
        }
        if (element.value == "") {
            element.classList.remove("input-success");
            element.classList.remove("input-error");
        }
    });
});