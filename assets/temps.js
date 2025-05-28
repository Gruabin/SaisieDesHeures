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

document.addEventListener('turbo:load', function () {
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