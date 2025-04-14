// public/js/cotisation.js
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du formulaire
    const typeCotisationInputs = document.querySelectorAll('input[name="cotisation[typeCotisation]"]');
    const montantMinimumContainer = document.getElementById('montant-minimum-container');
    const montantEcheanceContainer = document.getElementById('montant-echeance-container');
    const periodicFields = document.getElementById('periodic-fields');
    const dateFinInput = document.querySelector('.date-fin-input');
    const noEndDateCheckbox = document.getElementById('no-end-date');

    // Gestion du changement de type de cotisation
    typeCotisationInputs.forEach(input => {
        input.addEventListener('change', function() {
            const isPeriodic = this.value === 'periodique';
            
            // Afficher/masquer les champs appropriés
            montantMinimumContainer.classList.toggle('d-none', isPeriodic);
            montantEcheanceContainer.classList.toggle('d-none', !isPeriodic);
            periodicFields.classList.toggle('d-none', !isPeriodic);
        });
    });

    // Gestion de la case à cocher "Pas de date de fin"
    if (noEndDateCheckbox && dateFinInput) {
        noEndDateCheckbox.addEventListener('change', function() {
            if (this.checked) {
                dateFinInput.value = '';
                dateFinInput.classList.add('disabled');
                dateFinInput.setAttribute('disabled', 'disabled');
            } else {
                dateFinInput.classList.remove('disabled');
                dateFinInput.removeAttribute('disabled');
            }
        });
    }

    // Initialisation de l'état de la date de fin si le formulaire est pré-rempli
    if (dateFinInput && dateFinInput.value === '') {
        noEndDateCheckbox.checked = true;
        dateFinInput.classList.add('disabled');
        dateFinInput.setAttribute('disabled', 'disabled');
    }

    // Changement de devise EUR -> FCFA dans les champs MoneyType
    const moneyFields = document.querySelectorAll('.money-widget');
    moneyFields.forEach(field => {
        const currencySymbol = field.querySelector('.input-group-text');
        if (currencySymbol && currencySymbol.textContent === '€') {
            currencySymbol.textContent = 'FCFA';
        }
    });
});