document.getElementById("btnExport").addEventListener("click", function () {
    const url = "api/get/export/";

    // Effectuer la requête fetch
    fetch(url).then(function (response) { // Vérifier si la réponse est OK
    }).catch(function (error) {
        console.log(error);
    });
})