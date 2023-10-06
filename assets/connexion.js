document.getElementById("inputEmploye").addEventListener("input", function () {
    if (this.value == "") {
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = "Information employé";
        return;
    }
    document.getElementById("informationEmploye").innerText = "";
    document.getElementById("informationEmploye").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
    var input = document.getElementById("inputEmploye");
    var url = "api/get/employe/" + input.value;
    fetch(url).then(function (response) {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error("Employé non trouvé");
        }
    }).then(function (employe) {
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = employe.nom + " " + employe.prenom;
    }).catch(function (error) {
        document.getElementById("informationEmploye").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye").innerText = error.message;
    });
});
var employeTable = [];
document.addEventListener("DOMContentLoaded", function () {
    var url = "api/get/employe2/";
    fetch(url).then(function (response) {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error("Employé non trouvé");
        }
    }).then(function (employe) {
        employe.forEach((unEmploye) => {
            var employeObjet = {};
            employeObjet.id = unEmploye.id;
            employeObjet.nom = unEmploye.nom;
            employeObjet.prenom = unEmploye.prenom;
            employeTable.push(employeObjet);
        });
    }).catch(function (error) {
        console.log(error);
    });
});
document.getElementById("inputEmploye2").addEventListener("input", function () {
    if (this.value == "") {
        document.getElementById("informationEmploye2").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye2").innerText = "Information employé";
        return;
    }
    document.getElementById("informationEmploye2").innerText = "";
    document.getElementById("informationEmploye2").classList.add("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
    var input = document.getElementById("inputEmploye2");
    if (employeTable.find(e => e.id === parseInt(input.value))) {
        const employeTrouve = employeTable.find(e => e.id === parseInt(input.value));
        document.getElementById("informationEmploye2").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye2").innerText = employeTrouve.nom + " " + employeTrouve.prenom;;
    }
    else {
        document.getElementById("informationEmploye2").classList.remove("loading", "loading-dots", "loading-lg", "text-gruau-dark-blue");
        document.getElementById("informationEmploye2").innerText = "Employé non trouvé";
    }
});