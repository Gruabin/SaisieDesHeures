document.getElementById('inputEmploye').addEventListener('input', function () {
    // api request

    var input = document.getElementById('inputEmploye');
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'api/get/employe/' + input.value);
    xhr.send();
    xhr.onload = function () {
        var employe = JSON.parse(xhr.responseText);
        document.getElementById('informationEmploye').innerText = employe.nom + " " + employe.prenom;
    }

})