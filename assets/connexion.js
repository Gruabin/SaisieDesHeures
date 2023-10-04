document.getElementById('inputEmploye').addEventListener('input', function () {
    if(this.value == "") {
        document.getElementById('informationEmploye').classList.remove('loading', 'loading-dots', 'loading-lg', 'text-gruau-dark-blue')
        document.getElementById('informationEmploye').innerText = "Information employé";
        return;
    }
    // Afficher "Chargement..."
    document.getElementById('informationEmploye').innerText = "";
    document.getElementById('informationEmploye').classList.add('loading', 'loading-dots', 'loading-lg', 'text-gruau-dark-blue')

    var input = document.getElementById('inputEmploye');
    var url = 'api/get/employe/' + input.value;

    fetch(url)
        .then(function (response) {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Employé non trouvé');
            }
        })
        .then(function (employe) {
    document.getElementById('informationEmploye').classList.remove('loading', 'loading-dots', 'loading-lg', 'text-gruau-dark-blue')
    document.getElementById('informationEmploye').innerText = employe.nom + " " + employe.prenom;
        })
        .catch(function (error) {
    document.getElementById('informationEmploye').classList.remove('loading', 'loading-dots', 'loading-lg', 'text-gruau-dark-blue')
    document.getElementById('informationEmploye').innerText = error.message;
        });
});