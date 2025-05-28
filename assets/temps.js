function verifChampOrdre(evt){
    if(evt.target.value.length === 2 && evt.target.value.toUpperCase() === labelOrdre.toUpperCase()){
        evt.target.value = "";
    }
    if(evt.target.value.slice(0, 2).toUpperCase() === labelOrdre.toUpperCase()){
        evt.target.value = evt.target.value.slice(2);
    }
    if(evt.target.value.length > 7){
        evt.target.value = evt.target.value.slice(0, -1);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('cbTacheSpe');
    const selectTacheSpe = document.querySelector('[data-tache-spe="true"]');

    checkbox.addEventListener('change', function () {
        if (selectTacheSpe) {
            if (this.checked) {
                selectTacheSpe.removeAttribute('disabled');
            } else {
                selectTacheSpe.setAttribute('disabled', 'disabled');
                selectTacheSpe.selectedIndex = 0;
            }
        } else {
            console.error('Champ tâche spécifique introuvable !');
        }
    });
});

window.addEventListener('load', function () {
    const input = document.getElementById('ajout_projet_activite');
    const info = document.getElementById('infoActivite');
    console.log('AAAAAAAAAAAH', input, info)

    input.addEventListener('input', function () {
        const id = input.value.trim();

        input.classList.remove('border-green-500', 'border-red-500');
        info.textContent = '';

        if (id === '') return;

        fetch(`/verifier-activite/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.trouve) {
                    input.classList.add('border-green-500');
                    info.textContent = `Activité : ${data.nom}`;
                    info.className = 'text-green-600 mx-3 text-sm mt-1';
                } else {
                    input.classList.add('border-red-500');
                    info.textContent = `Aucune activité trouvée pour cet ID`;
                    info.className = 'text-red-600 mx-3 text-sm mt-1 font-bold';
                }
            })
            .catch(error => {
                console.error('Erreur lors de la vérification de l\'activité:', error);
            });
    });
});