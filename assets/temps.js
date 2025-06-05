function setupDelegatedActiviteVerification() {
    document.removeEventListener('input', delegatedHandler);
    document.addEventListener('input', delegatedHandler);

    const timers = new Map();

    function delegatedHandler(event) {
        if (event.target && event.target.id === 'ajout_projet_activite') {
            handleInputEvent(event.target);
        }
    }

    function handleInputEvent(input) {
        if (timers.has(input)) {
            clearTimeout(timers.get(input));
        }

        timers.set(input, setTimeout(() => {
            timers.delete(input);

            const info = document.getElementById('infoActivite');
            const id = input.value.trim();

            input.classList.remove('border-green-500', 'border-red-500');
            if (info) {
                info.textContent = '';
                info.className = '';
            }

            if (id === '') return;

            fetch(`/verifier-activite/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (!info) return;

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
                    console.error("Erreur lors de la vérification de l'activité:", error);
                });
        }, 1000));
    }
}

document.addEventListener('change', function(event) {
    if (event.target && event.target.id === 'cbTacheSpe') {
        const selectTacheSpe = document.querySelector('[data-tache-spe="true"]');
        if (!selectTacheSpe) return;

        if (event.target.checked) {
            selectTacheSpe.removeAttribute('disabled');
        } else {
            selectTacheSpe.setAttribute('disabled', 'disabled');
            selectTacheSpe.selectedIndex = 0;
        }
    }
});

document.addEventListener('turbo:frame-load', () => {
    const btn = document.querySelector('#btnLoading');
    if (!btn) return;

    btn.classList.remove('loading', 'loading-spinner', 'loading-xs');
    btn.removeAttribute('disabled');

    btn.addEventListener('click', () => {
        btn.classList.add('loading', 'loading-spinner', 'loading-xs');
        btn.setAttribute('disabled', 'disabled');
    });
});


document.addEventListener('turbo:load', () => {
    setupDelegatedActiviteVerification();
});

document.addEventListener('turbo:frame-load', () => {
    setupDelegatedActiviteVerification();
});
