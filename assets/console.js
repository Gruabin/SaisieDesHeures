// 
// * Change les boutons de couleur lors de leur survol

const { data } = require("autoprefixer");

// 
check = document.querySelectorAll('.check');
xmark = document.querySelectorAll('.xmark');
pen = document.querySelectorAll('.pen');
check.forEach(element => {
    element.addEventListener('mouseover', function () {
        element.classList.add('text-success');
    });
    element.addEventListener('mouseout', function () {
        element.classList.remove('text-success');
    });
});
xmark.forEach(element => {
    element.addEventListener('mouseover', function () {
        element.classList.add('text-accent');
    });
    element.addEventListener('mouseout', function () {
        element.classList.remove('text-accent');
    });
});
pen.forEach(element => {
    element.addEventListener('mouseover', function () {
        element.classList.add('text-secondary');
    });
    element.addEventListener('mouseout', function () {
        element.classList.remove('text-secondary');
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
    fetch("api/post/approuver",
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

//
// * Connexion en un autre responsable selon une liste
//

document.querySelector("form[name='filtre_responsable']").addEventListener("submit", function (event) {
    event.preventDefault();
    submitForm();
})

function submitForm() {
        // Sélection de l'élément select
        const selectElement = document.getElementById("filtre_responsable_responsables");

        // Récupération de la ligne sélectionnée
        const selectedOption = selectElement.options[selectElement.selectedIndex];

        // Récupérez l'ID de l'utilisateur depuis le champ input
        const idEmploye = selectedOption.value.toUpperCase();
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
            if (!response.ok) {
                throw new Error("Réponse inattendue du serveur");
            } else {
                // Rediriger l'utilisateur en cas de succès
                window.location.href = response.url;
            }
        }).catch((error) => {
            // Afficher un message d'erreur
            alert(error);
            // Gérer d'autres statuts d'erreur ici
            throw new Error("Réponse inattendue du serveur");
        });
}

//
// * Affiche le formulaire sur une ligne
//
// TODO pour le prochain sprint
//
// ligne.forEach(element => {
//     element.querySelector('#pen').addEventListener("click", () =>{
//         element.querySelector('#pen').classList.add('hidden');
//         element.querySelector('#trash').classList.add('hidden');
//         element.querySelector('#check').classList.remove('hidden');
//         element.querySelector('#xmark').classList.remove('hidden');
//     });
// });