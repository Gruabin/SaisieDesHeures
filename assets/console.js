import TomSelect from "tom-select";
let ligneASupprimer;
let token;
let ligne = document.querySelectorAll('.ligne');
let tabEmploye = document.querySelectorAll('.tabEmploye');
ligne.forEach(element => {

    //
    // * Affiche le formulaire sur une ligne
    //
    element.querySelector('.pen').addEventListener("click", () => {
        element.querySelector('.pen').classList.add('hidden');
        element.querySelector('.trash').classList.add('hidden');
        element.querySelector('.check').classList.remove('hidden');
        element.querySelector('.xmark').classList.remove('hidden');
        formModif(element);
    });
    element.querySelector('.xmark').addEventListener("click", () => {
        element.querySelector('.pen').classList.remove('hidden');
        element.querySelector('.trash').classList.remove('hidden');
        element.querySelector('.check').classList.add('hidden');
        element.querySelector('.xmark').classList.add('hidden');
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
    element.querySelector('.trash').addEventListener("click", () => {
        document.getElementById('modalSuppr').showModal();
        ligneASupprimer = element;
    });


    //
    // * Modifier une ligne
    //
    element.querySelector('.check').addEventListener("click", () => {
        element.querySelector('.loading').classList.remove('hidden');
        element.querySelector('.check').classList.add('hidden');
        element.querySelector('.xmark').classList.add('hidden');
        APIModification(element);
    });

});

// Supprime la ligne
document.getElementById('btnModalSuppr').addEventListener("click", () => {
    APISuppression(ligneASupprimer);
});



// 
// * Gestion des couleurs des boutons du tableau
// 
const buttons = document.querySelectorAll('.check, .xmark, .pen, .trash');
buttons.forEach(button => {
    button.addEventListener('mouseover', function () {
        if (button.classList.contains('check')) {
            button.classList.add('text-success');
        } else if (button.classList.contains('xmark')) {
            button.classList.add('text-accent');
        } else if (button.classList.contains('pen')) {
            button.classList.add('text-secondary');
        } else if (button.classList.contains('trash')) {
            button.classList.add('text-accent');
        }
    });

    button.addEventListener('mouseout', function () {
        button.classList.remove('text-success', 'text-accent', 'text-secondary');
    });
});

// 
// * Affiche uniquement les anomalies
//
document.getElementById("select_anomalies").addEventListener("click", () => {
    tabEmploye.forEach(tableau => {
        let allHidden = true;
        tableau.querySelectorAll('.ligne').forEach(element => {
            if (document.getElementById("select_anomalies").checked && element.dataset.statut == 3) {
                element.classList.add("hidden");
            } else {
                element.classList.remove("hidden");
                allHidden = false;
            }
        });
        if (allHidden && document.getElementById("select_anomalies").checked) {
            tableau.classList.add("hidden");
        }
        else {
            tableau.classList.remove("hidden");
        }
    });
});

// 
// * Sélection de tout les checkboxs
// 
let checkbox = document.getElementById("select_all");
if (checkbox) {
    checkbox.addEventListener("click", (event) => {
        let checkboxDiv = document.getElementById("select_all_checkboxes");
        checkboxDiv.querySelectorAll("input[type=checkbox]").forEach((item) => {
            if (event.target.checked) {
                if (!item.checked) {
                    item.click();
                }
            } else {
                if (item.checked) {
                    item.click();
                }
            }
        });
        checkboxDiv.querySelectorAll("input[type=checkbox]").forEach((item) => {
            item.addEventListener("click", (event) => {
                if (!event.target.checked) {
                    checkbox.checked = false;
                }
            })
        });
    });
}

// 
// * Sélection de tout les checkboxs d'un employé
//
let checkboxes = document.querySelectorAll("#select_user");
checkboxes.forEach(checkbox => {
    const tab = document.querySelector('.tabEmploye[data-employe="' + checkbox.dataset.employe + '"]');
    checkbox.addEventListener("click", (event) => {
        if (event.target.checked) {
            tab.querySelectorAll("input[type=checkbox]").forEach((item) => {
                if (!item.checked) {
                    item.click();
                }
            });
        } else {
            tab.querySelectorAll("input[type=checkbox]").forEach((item) => {
                if (item.checked) {
                    item.click();
                }
            });
        }
    });
    tab.querySelectorAll("input[type=checkbox]").forEach((item) => {
        item.addEventListener("click", (event) => {
            if (!event.target.checked) {
                checkbox.checked = false;
            }
        })
    });
});

// 
// * Déclenche l'envoie des données valides pour approbation
// 
let donnees = [];
document.getElementById('validation').addEventListener('click', function () {
    document.getElementById('validation').classList.add("hidden");
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
            document.getElementById("loading").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
            throw new Error("Réponse inattendue du serveur");
        }
        window.location.href = '/console';
    }).catch((error) => {
        document.getElementById('validation').classList.remove("hidden");
        document.getElementById("loading").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        window.location.href = '/console';
        throw new Error("Réponse inattendue du serveur");
    });
});


//
// * Envoie le requête de suppression
//
function APISuppression(ligneASupprimer) {
    token = ligneASupprimer.querySelector('.ligne').value;
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
            MAJTempsJourna(ligneASupprimer.dataset.employe)
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


// 
//* Envoie les données du formulaire au serveur
// 
async function APIModification(element) {

    const id = element.dataset.idligne;
    const statut = element.dataset.statut;
    const ordre = element.querySelector("input[name='ordre']").value;
    const tache = element.querySelector("select[name='tache']").value;
    const operation = element.querySelector("input[name='operation']").value;
    const activite = element.querySelector("input[name='activite']").value;
    const centre_de_charge = element.querySelector("select[name='centrecharge']").value;
    const temps_main_oeuvre = element.querySelector("input[name='saisieTemps']").value;
    const token = element.querySelector("input[name='ligneToken']").value;
    const data = {
        'id': id,
        'temps_main_oeuvre': temps_main_oeuvre,
        'statut': statut,
        'token': token
    }
    if (temps_main_oeuvre == "") {
        alert("Veuillez insérer un temps de main d'oeuvre");

        return respnse.status = 400;
    }
    if (ordre !== "") {
        data.ordre = ordre;
    } else {
        data.ordre = null;
    }
    if (tache !== "-1") {
        data.tache = tache;
    } else {
        data.tache = null;
    }
    if (operation !== "") {
        data.operation = operation;
    } else {
        data.operation = null;
    }
    if (activite !== "") {
        data.activite = activite;
    } else {
        data.activite = null;
    }
    if (centre_de_charge !== "-1") {
        data.centre_de_charge = centre_de_charge;
    } else {
        data.centre_de_charge = null;
    }
    fetch("/api/post/modifierLigne", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),

    }).then((response) => {
        element.querySelector('.loading').classList.add('hidden');
        if (!response.ok) {
            addToastErreur("Erreur lors de la modification de la saisie");
            element.querySelector('.check').classList.remove('hidden');
            element.querySelector('.xmark').classList.remove('hidden');

        }
        addToastSuccess("Saisie modifiée");
        element.querySelector('.pen').classList.remove('hidden');
        element.querySelector('.trash').classList.remove('hidden');
        MAJDonnees(element, data);
        if (statut == 2) {
            document.getElementById("nbAnomalie").innerHTML = parseInt(document.getElementById("nbAnomalie").innerHTML) - 1;
        }
        resetModif(element)

    }).catch((error) => {
        console.error("Une erreur s'est produite :", error);
        addToastErreur("Erreur lors de la modification de la saisie");
    });
}

// 
// * Met à jour les données de la ligne après une modification
//
function MAJDonnees(element, data) {
    element.dataset.statut = 3;
    element.querySelector(".fa-circle-check").classList.remove("hidden");
    element.querySelector(".fa-circle-xmark").classList.add("hidden");
    element.querySelector("input[name='checkbox_ligne']").disabled = false;
    element.querySelector("p[name='texte_ordre']").innerHTML = data.ordre;
    element.querySelector("p[name='texte_tache']").innerHTML = data.tache;
    element.querySelector("p[name='texte_operation']").innerHTML = data.operation;
    element.querySelector("p[name='texte_activite']").innerHTML = data.activite;
    element.querySelector("p[name='texte_centrecharge']").innerHTML = data.centre_de_charge;
    element.querySelector("p[name='texte_saisieTemps']").innerHTML = data.temps_main_oeuvre;
    MAJTempsJourna(element.dataset.employe)
}

// 
// * Met à jour le temps total de l'employé pour la saisie modifiée/supprimée
// 
function MAJTempsJourna(employe) {
    const tab = document.querySelector('.tabEmploye[data-employe="' + employe + '"]');
    let temps = 0;
    tab.querySelectorAll('.texte_saisieTemps').forEach(element => {
        temps += parseFloat(element.innerHTML);
    });
    tab.querySelector(".tempsTotal").innerHTML = temps.toFixed(2) + " h";
}


//
//* Effectue la RegEx pour vérifier le champs Ordre
//
document.querySelectorAll(".ordre").forEach(element => {
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

//
//* Effectue la RegEx pour vérifier le champs Activité 
//
var tableActivite = makeAPIActivite();
document.querySelectorAll(".activite").forEach(element => {
    element.addEventListener("input", function () {
        regex = new RegExp("^[0-9]{3}$");
        element.classList.remove("input-success");
        element.classList.remove("input-error");
        if (regex.test(element.value) && tableActivite.find((e) => e.id == element.value)) {
            const activiteTrouve = tableActivite.find((e) => e.id == element.value);
            element.classList.add("input-success");
        }
        else {
            element.classList.add("input-error");
        }
        if (element.value == "") {
            element.classList.remove("input-success");
            element.classList.remove("input-error");
        }
    })
});

//
//* Retourne toutes les activités pour la regex
//
function makeAPIActivite() {
    var url = "/api/get/activite";
    var activiteTable = [];
    fetch(url).then(function (response) {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error("Erreur");
        }
    }).then(function (activite) {
        activite.forEach(async (unActivite) => {
            var activiteObjet = {};
            activiteObjet.id = unActivite.id;
            activiteObjet.nom = unActivite.nom;
            await activiteTable.push(activiteObjet)
        });
    }).catch(function (error) {
        console.log(error);
    });
    return activiteTable;
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

//
// * Amélioration du select multiple avec Tom Select
//

const tomSelectInstance = new TomSelect("#filtre_responsable_responsable", {
    plugins: {
        'clear_button': {
            'title': 'Retirer tous les managers sélectionnés'
        },
        'remove_button': {
            'title': 'Retirer ce manager'
        }
    },
    onInitialize: function () {
        const element = this.input.parentElement.querySelector('.ts-control');
        this.input.parentElement.classList.add('w-full');
        this.input.parentElement.classList.remove('mb-6');
        element.style.maxHeight = '6rem';
        element.style.overflow = 'auto';
    }
});

document.getElementById('check-all').addEventListener('click', function (event) {
    const allValues = tomSelectInstance.options;
    var valuesToSelect = Object.keys(allValues).map(function (key) {
        return allValues[key].value;
    });
    tomSelectInstance.setValue(valuesToSelect);
});
