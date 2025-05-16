// const labelOrdre = document.getElementById('labelOrdre').innerText;
// var selectedOption = document.getElementById("type").value;
// var selectedOptionText = document.querySelector('#type option[value="' + selectedOption + '"]').innerText;
// var centreChargeValue = document.getElementById("CDGUser").innerHTML.trim();
// var isValidCentreCharge = Array.from(document.getElementById("centrecharge").options).some(option => option.value === centreChargeValue);

// // 
// //* Détecte un changement de type d'heure
// // 
// formChange();
// document.getElementById("type").addEventListener("change", function () {
//     formChange();
// })

// document.getElementById("cbTacheSpe").addEventListener("change", function () {
//     document.getElementById("tacheSpe").value = -1;
//     if (document.getElementById("cbTacheSpe").checked) {
//         document.getElementById("tacheSpe").disabled = false;
//     } else {
//         document.getElementById("tacheSpe").disabled = true;
//     }
// })

// // 
// //* Affiche les Différents champs en fonction du type d'heure
// // 
// function formChange() {
//     document.getElementById("ordre").value = "";
//     document.getElementById("operation").value = "";
//     document.getElementById("activite").value = "";
//     document.getElementById("divTacheSpe").value = "";
//     document.getElementById("tache").value = -1;
//     document.getElementById("tacheSpe").value = -1;

//     switch (parseInt(document.getElementById("type").value)) {
//         case 1:
//             iconChange(1);
//             tacheChange(1);
//             if (isValidCentreCharge) {
//                 document.getElementById("centrecharge").value = centreChargeValue;
//             }
//             document.getElementById("divOrdre").classList.add("hidden");
//             document.getElementById("divTache").classList.remove("hidden");
//             document.getElementById("divOperation").classList.add("hidden");
//             document.getElementById("divActivite").classList.add("hidden");
//             document.getElementById("divTacheSpe").classList.add("hidden");
//             document.getElementById("divCentreCharge").classList.remove("hidden");
//             document.getElementById("divSaisieTemps").classList.remove("hidden");
//             break;
//         case 2:
//             iconChange(2);
//             document.getElementById("centrecharge").value = -1;
//             document.getElementById("divOrdre").classList.remove("hidden");
//             document.getElementById("divTache").classList.add("hidden");
//             document.getElementById("divOperation").classList.remove("hidden");
//             document.getElementById("divActivite").classList.add("hidden");
//             document.getElementById("divTacheSpe").classList.remove("hidden");
//             document.getElementById("divCentreCharge").classList.add("hidden");
//             document.getElementById("divSaisieTemps").classList.remove("hidden");
//             break;
//         case 3:
//             iconChange(3);
//             document.getElementById("centrecharge").value = -1;
//             document.getElementById("divOrdre").classList.remove("hidden");
//             document.getElementById("divTache").classList.add("hidden");
//             document.getElementById("divOperation").classList.remove("hidden");
//             document.getElementById("divActivite").classList.add("hidden");
//             document.getElementById("divTacheSpe").classList.add("hidden");
//             document.getElementById("divCentreCharge").classList.add("hidden");
//             document.getElementById("divSaisieTemps").classList.remove("hidden");
//             break;
//         case 4:
//             iconChange(4);
//             tacheChange(4);
//             document.getElementById("centrecharge").value = -1;
//             document.getElementById("divOrdre").classList.remove("hidden");
//             document.getElementById("divTache").classList.remove("hidden");
//             document.getElementById("divOperation").classList.add("hidden");
//             document.getElementById("divActivite").classList.remove("hidden");
//             document.getElementById("divTacheSpe").classList.add("hidden");
//             document.getElementById("divCentreCharge").classList.add("hidden");
//             document.getElementById("divSaisieTemps").classList.remove("hidden");
//             break;
//         default:
//             iconChange(-1);
//             document.getElementById("centrecharge").value = -1;
//             document.getElementById("divOrdre").classList.add("hidden");
//             document.getElementById("divTache").classList.add("hidden");
//             document.getElementById("divOperation").classList.add("hidden");
//             document.getElementById("divActivite").classList.add("hidden");
//             document.getElementById("divTacheSpe").classList.add("hidden");
//             document.getElementById("divCentreCharge").classList.add("hidden");
//             document.getElementById("divSaisieTemps").classList.add("hidden");
//             break;
//     }
// }

// function iconChange(selectedCase){
//     if (parseInt(selectedOption) === parseInt(selectedCase)) {
//         document.getElementById("iconPlein").classList.remove("hidden");
//         document.getElementById("iconTransparent").classList.add("hidden");
//     }else{
//         document.getElementById("iconPlein").classList.add("hidden");
//         document.getElementById("iconTransparent").classList.remove("hidden");
//     }
// }

// // 
// //* Renvoie la liste des taches en fonction du type d'heure
// // 
// function tacheChange(id) {
//     options = document.getElementById('tache').options;
//     for (var i = 0; i < options.length; i++) {
//         if (options[i].dataset.idtype != id) {
//             document.getElementById('tache').options[i].hidden = true;
//         }
//         else {
//             document.getElementById('tache').options[i].hidden = false;
//         }
//     }
// }

// // 
// //* Validation du formulaire
// // 
// document.getElementById('btnEnregistrerQuitter').addEventListener('click', async function () {
//     if (verif()) {
//         document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-primary");
//         const state = await formSubmit();
//         if (!state) {
//             alert("Une erreur s'est produite")
//             document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-primary");
//         }
//         else {
//             window.location.href = '/deconnexion';
//         }
//     }
// })
// document.getElementById('btnEnregistrerContinue').addEventListener('click', async function () {
//     if (verif()) {
//         document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-primary");
//         const state = await formSubmit();
//         if (!state) {
//             alert("Une erreur s'est produite")
//             document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-primary");
//         }
//         else {
//             window.location.href = '/temps';
//         }
//     }
// })

// // 
// //* Vérifie que tous les champs sont remplis
// //
// function verif() {
//     let response = true;
//     const type_heures = document.getElementById("type").value;
//     const ordre = document.getElementById("ordre").value;
//     const tache = document.getElementById("tache").value;
//     const operation = document.getElementById("operation").value;
//     const activite = document.getElementById("activite").value;
//     const centre_de_charge = document.getElementById("centrecharge").value;
//     const temps_main_oeuvre = document.getElementById("saisieTemps").value;

//     switch (parseInt(type_heures)) {
//         case 1:
//             if (tache == "-1" || centre_de_charge == -1 || temps_main_oeuvre == "") {
//                 alert("Veuillez remplir tous les champs");
//                 response = false;
//             }
//             break;
//         case 2:
//         case 3:
//             if (ordre == "" || operation == "" || temps_main_oeuvre == "") {
//                 alert("Veuillez remplir tous les champs");
//                 response = false;
//             }
//             break;
//         case 4:
//             if (ordre == "" || tache == -1 || activite == "" || temps_main_oeuvre == "") {
//                 alert("Veuillez remplir tous les champs");
//                 response = false;
//             }
//             break;
//         default:
//             alert("Veuillez selectionner un type d'heure");
//             response = false;
//     }
//     return response;
// }

// // 
// //* Envoie les données du formulaire au serveur
// // 
// async function formSubmit() {
//     document.getElementById("informationSaisiHeures").classList.add("loading", "loading-dots", "loading-lg", "text-primary");

//     const type_heures = document.getElementById("type").value;
//     let ordre = "";
//     if(document.getElementById("ordre").value){
//         ordre = labelOrdre + document.getElementById("ordre").value;
//     }
//     const tache = document.getElementById("tache").value;
//     const operation = document.getElementById("operation").value;
//     const tacheSpecifique = document.getElementById("tacheSpe").value;
//     const activite = document.getElementById("activite").value;
//     const centre_de_charge = document.getElementById("centrecharge").value;
//     const temps_main_oeuvre = document.getElementById("saisieTemps").value;
//     const token = document.getElementById("saisieToken").value;
//     const data = {
//         'type_heures': type_heures,
//         'temps_main_oeuvre': temps_main_oeuvre,
//         'token': token
//     }
//     if (type_heures == "-1") {
//         alert("Veuillez selectionner un type d'heure");
//         document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-primary");

//         return respnse.status = 400;
//     }
//     if (temps_main_oeuvre == "") {
//         alert("Veuillez insérer un temps de main d'oeuvre");
//         document.getElementById("informationSaisiHeures").classList.remove("loading", "loading-dots", "loading-lg", "text-primary");

//         return respnse.status = 400;
//     }
//     if (ordre !== "") {
//         data.ordre = ordre.toUpperCase();
//     }
//     if (tache !== "-1") {
//         data.tache = tache;
//     }
//     if (operation !== "") {
//         data.operation = operation;
//     }
//     if (activite !== "") {
//         data.activite = activite;
//     }
//     if (tacheSpecifique !== "-1") {
//         data.tacheSpecifique = tacheSpecifique;
//     }
//     if (centre_de_charge !== "-1") {
//         data.centre_de_charge = centre_de_charge;
//     }
//     try {
//         const response = await fetch("/api/post/detail_heures", {
//             method: "POST",
//             headers: {
//                 "Content-Type": "application/json",
//             },
//             body: JSON.stringify(data),
//         })
//         if (!response.ok) {
//             return response;
//         }
//         return true;
//     } catch (error) {
//         console.error("Une erreur s'est produite :", error);
//     }

//     return false;
// }

// //
// //* Vérifier la saisie de l'ordre (Notamment pour les douchettes)
// //
// document.getElementById("ordre").addEventListener("input", verifChampOrdre);
// document.getElementById("ordre").addEventListener("paste", verifChampOrdre);

// function verifChampOrdre(evt){
//     if(evt.target.value.length === 2 && evt.target.value.toUpperCase() === labelOrdre.toUpperCase()){
//         evt.target.value = "";
//     }
//     if(evt.target.value.slice(0, 2).toUpperCase() === labelOrdre.toUpperCase()){
//         evt.target.value = evt.target.value.slice(2);
//     }
//     if(evt.target.value.length > 7){
//         evt.target.value = evt.target.value.slice(0, -1);
//     }
// }

// //
// //* Effectue la RegEx pour vérifier le champs Ordre
// //
// let inputOrdre = document.getElementById("ordre");
// let inputOrdreLabel = document.getElementById("ordre").parentElement;
// document.getElementById("ordre").addEventListener("input", function () {
//     let regex = new RegExp("^[0-9A-Za-z]{2}[0-9]{5}$");
//     inputOrdreLabel.classList.remove("input-success");
//     inputOrdreLabel.classList.remove("input-error");
//     if (regex.test(inputOrdre.value)) {
//         inputOrdreLabel.classList.add("input-success");
//         document.getElementById("btnEnregistrerQuitter").classList.remove("btn-disabled");
//         document.getElementById("btnEnregistrerContinue").classList.remove("btn-disabled");
//     }
//     else {
//         inputOrdreLabel.classList.add("input-error");
//         document.getElementById("btnEnregistrerQuitter").classList.add("btn-disabled");
//         document.getElementById("btnEnregistrerContinue").classList.add("btn-disabled");
//     }
//     if (inputOrdre.value == "") {
//         inputOrdreLabel.classList.remove("input-success");
//         inputOrdreLabel.classList.remove("input-error");
//         document.getElementById("btnEnregistrerQuitter").classList.remove("btn-disabled");
//         document.getElementById("btnEnregistrerContinue").classList.remove("btn-disabled");
//     }
// })

// //
// //* Retourne toutes les activités
// //
// function makeAPIActivite() {
//     var url = "/api/get/activite";
//     var activiteTable = [];
//     fetch(url).then(function (response) {
//         if (response.ok) {
//             return response.json();
//         } else {
//             throw new Error("Erreur");
//         }
//     }).then(function (activite) {
//         activite.forEach(async (unActivite) => {
//             var activiteObjet = {};
//             activiteObjet.id = unActivite.id;
//             activiteObjet.nom = unActivite.nom;
//             await activiteTable.push(activiteObjet)
//         });

//     }).catch(function (error) {
//         console.log(error);
//     });
//     return activiteTable;
// }

// var tableActivite = makeAPIActivite();

// //
// //* Effectue la RegEx pour vérifiés le champs activité et afffiche le nom de l'activité
// //
// document.getElementById("activite").addEventListener("input", function () {
//     inputActivite = document.getElementById("activite");
//     regex = new RegExp("^[0-9]{3}$");
//     inputActivite.classList.remove("input-success");
//     inputActivite.classList.remove("input-error");
//     if (regex.test(inputActivite.value) && tableActivite.find((e) => e.id == inputActivite.value)) {
//         const activiteTrouve = tableActivite.find((e) => e.id == inputActivite.value);
//         inputActivite.classList.add("input-success");
//         document.getElementById("infoActivite").innerText = activiteTrouve.nom;
//         document.getElementById("btnEnregistrerQuitter").classList.remove("btn-disabled");
//         document.getElementById("btnEnregistrerContinue").classList.remove("btn-disabled");
//     }
//     else {
//         inputActivite.classList.add("input-error");
//         document.getElementById("infoActivite").innerText = "";
//         document.getElementById("btnEnregistrerQuitter").classList.add("btn-disabled");
//         document.getElementById("btnEnregistrerContinue").classList.add("btn-disabled");
//     }
//     if (inputActivite.value == "") {
//         inputActivite.classList.remove("input-success");
//         inputActivite.classList.remove("input-error");
//         document.getElementById("infoActivite").innerText = "";
//         document.getElementById("btnEnregistrerQuitter").classList.remove("btn-disabled");
//         document.getElementById("btnEnregistrerContinue").classList.remove("btn-disabled");
//     }
// })

// //
// //* Enregistre le type d'heure dans la base de données pour la gestion des favoris
// //
// document.getElementById('btnEnregistrerParDefaut').addEventListener('click', function (event) {
//     // Envoie une requête POST contenant le type d'heure sélectionné de manière asynchrone avec une animation de chargement pendant l'envoi
//     document.getElementById("btnEnregistrerParDefaut").classList.add("loading", "loading-dots", "loading-lg", "text-primary");
//     document.getElementById("btnEnregistrerParDefaut").classList.add("btn-disabled");
//     document.getElementById("iconPlein").classList.add("hidden");
//     document.getElementById("iconTransparent").classList.add("hidden");

//     // Requête POST asynchrone à l'URL /api/post/type_heure
//     fetch('/api/post/type_heure', {
//         method: 'POST',
//         headers: {
//             'Accept': 'application/json',
//             'Content-Type': 'application/json'
//         },
//         body: JSON.stringify({ type: document.getElementById("type").value })
//     })
//         .then(response => {
//             if (!response.ok) {
//                 document.getElementById("btnEnregistrerParDefaut").classList.remove("loading", "loading-dots", "loading-lg", "text-primary");

//                 document.getElementById("alertError").classList.remove("hidden");
//                 setTimeout(function () {
//                     document.getElementById("alertError").classList.add("hidden");
//                     document.getElementById("btnEnregistrerParDefaut").classList.remove("btn-disabled");
//             }, 5000);
//         }
//             else {
//                 document.getElementById("btnEnregistrerParDefaut").classList.remove("loading", "loading-dots", "loading-lg", "text-primary");

//                 document.getElementById("alertSuccess").classList.remove("hidden");
//                 selectedOption = document.getElementById("type").value;
//                 selectedOptionText = document.querySelector('#type option[value="' + selectedOption + '"]').innerText;
//                 formChange();
//                 if(parseInt(selectedOption) < 0){
//                     document.getElementById("alertSuccess").querySelector('span').innerText = "Aucun type d'heure par défaut désigné";
//                 }else{
//                     document.getElementById("alertSuccess").querySelector('span').innerText = "Le type d'heure " + selectedOptionText + " a été enregistré comme paramètre par défaut";
//                 }
//                 setTimeout(function () {
//                     document.getElementById("alertSuccess").classList.add("hidden");
//                     document.getElementById("btnEnregistrerParDefaut").classList.remove("btn-disabled");
//             }, 5000);
//             }
//         })
// })

