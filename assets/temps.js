function formChange() {
    switch (parseInt(document.getElementById("type").value)) {
        case 1:
            tacheChange(1);
            document.getElementById("divOrdre").classList.add("hidden");
            document.getElementById("divTache").classList.remove("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            if (parseInt(document.getElementById("tache").value) === 111) {
                document.getElementById("divCentreCharge").classList.remove("hidden");
            }
            document.getElementById("divSaisiTemps").classList.remove("hidden");
            break;
        case 2:
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.remove("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            document.getElementById("divSaisiTemps").classList.remove("hidden");
            break;
        case 3:
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.remove("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            document.getElementById("divSaisiTemps").classList.remove("hidden");
            break;
        case 4:
            tacheChange(4);
            document.getElementById("divOrdre").classList.remove("hidden");
            document.getElementById("divTache").classList.remove("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.remove("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            document.getElementById("divSaisiTemps").classList.remove("hidden");
            break;
        default:
            document.getElementById("divOrdre").classList.add("hidden");
            document.getElementById("divTache").classList.add("hidden");
            document.getElementById("divOperation").classList.add("hidden");
            document.getElementById("divActivite").classList.add("hidden");
            document.getElementById("divCentreCharge").classList.add("hidden");
            document.getElementById("divSaisiTemps").classList.add("hidden");
            break;
    }
}

function tacheChange(id) {

    options = document.getElementById('tache').options;
    for (var i = 0; i < options.length; i++) {
        if (options[i].dataset.idType != id) {
            document.getElementById('tache').options[i].hidden = true;
        }
        else{
            document.getElementById('tache').options[i].hidden = false;
        }
    }
}

async function formSubmit() {
    document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

    const type_heures = document.getElementById("type").value;
    const ordre = document.getElementById("ordre").value;
    const tache = document.getElementById("tache").value;
    const operation = document.getElementById("operation").value;
    const activite = document.getElementById("activite").value;
    const centre_de_charge = document.getElementById("centrecharge").value;
    const temps_main_oeuvre = document.getElementById("saisitemps").value;
    const token = document.getElementById("saisieToken").value;
    const data = {
        'type_heures': type_heures,
        'temps_main_oeuvre': temps_main_oeuvre,
        'token': token
    }
    if (type_heures == "-1") {
        alert("Veuillez selectionner un type d'heure");
        return false;
    }
    if (temps_main_oeuvre == "") {
        alert("Veuillez insérer un temps de main d'oeuvre");
        return false;
    }
    if (ordre !== "") {
        data.ordre = ordre;
    }
    if (tache !== "-1") {
        data.tache = tache;
    }
    if (operation !== "") {
        data.operation = operation;
    }
    if (activite !== "") {
        data.activite = activite;
    }
    if (centre_de_charge !== "-1") {
        data.centre_de_charge = centre_de_charge;
    }
    try {
        const response = await fetch("/api/post/detail_heures", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        })

        document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");

        if (!response.ok) {
            document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue")
            alert(await response.text());
            return false;
        }

        return true;

    } catch (error) {
        document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue")
        console.error("Une erreur s'est produite :", error);
    }

    return false;
}

document.getElementById("type").addEventListener("change", function () {
    formChange();
})

document.getElementById("tache").addEventListener("change", function () {
    formChange();
})

document.getElementById('btnEnregistrerQuitter').addEventListener('click', async function () {
    const state = await formSubmit();
    if (state) {
        document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        window.location.href = '/api/post/deconnexion';
    }
    else {
        document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue")
    }
})

document.getElementById('btnEnregistrerContinue').addEventListener('click', async function () {
    const state = await formSubmit();
    if (state) {
        document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        window.location.href = '/temps';
    }
    else {
        document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue")
    }
})


input = document.getElementById("ordre");
document.getElementById("ordre").addEventListener("input", function () {
    regex = new RegExp("^[A-Z]{2}[A-Z0-9]{1}[0-9]{6}$");
    input.classList.remove("input-success");
    input.classList.remove("input-error");
    if (regex.test(input.value)) {
        input.classList.add("input-success");
    }
    else {
        input.classList.add("input-error");
        document.getElementById("btnEnregistrerQuitter").classList.add("btn-disabled");
        document.getElementById("btnEnregistrerContinue").classList.add("btn-disabled");
    }
    if (input.value == "" || input.value == " ") {
        input.classList.remove("input-success");
        input.classList.remove("input-error");
        document.getElementById("btnEnregistrerQuitter").classList.remove("btn-disabled");
        document.getElementById("btnEnregistrerContinue").classList.remove("btn-disabled");
    }
})