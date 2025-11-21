document.addEventListener('DOMContentLoaded', () => {
    const lyceeSelect = document.querySelector('#Eleve_lycee');
    const classeSelect = document.querySelector('#Eleve_classe');

    if (!lyceeSelect || !classeSelect) {
        console.error("❌ Impossible de trouver les champs #Eleve_lycee ou #Eleve_classe");
        return;
    }

    lyceeSelect.addEventListener('change', () => {
        const lyceeId = lyceeSelect.value;
        console.log('🏫 Lycée sélectionné :', lyceeId); // ✅ debug

        if (!lyceeId) {
            classeSelect.innerHTML = '';
            return;
        }

        // 💡 Ici tu ajoutes le fetch
        fetch(`/api/classes/${lyceeId}`)
            .then(response => {
                return response.json(); // ✅ Lire une seule fois
            })
            .then(data => {
                console.log('✅ Réponse brute :', data); // 👍 Affiche les données ici

                // Vider les options précédentes
                classeSelect.innerHTML = '';

                // Ajouter les nouvelles options
                data.forEach(classe => {
                    const option = document.createElement('option');
                    option.value = classe.id;
                    option.textContent = classe.nom;
                    classeSelect.appendChild(option);
                });

                // Si TomSelect est utilisé (EasyAdmin l'utilise par défaut)
                if (classeSelect.tomselect) {
                    classeSelect.tomselect.clearOptions();
                    classeSelect.tomselect.addOptions(
                        data.map(classe => ({
                            value: classe.id,
                            text: classe.nom
                        }))
                    );
                    classeSelect.tomselect.refreshOptions();
                }
            })
            .catch(error => {
                console.error('❌ Erreur lors du chargement des classes :', error);
            });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const matriculeInput = document.querySelector('#Eleve_matricule');
    const cinInput = document.querySelector('#Eleve_cin');

    if (matriculeInput && cinInput) {
        matriculeInput.addEventListener('input', () => {
            const value = matriculeInput.value;
            if (value.length >= 4) {
                fetch('/api/generate-cin?matricule=' + encodeURIComponent(value))
                    .then(response => response.json())
                    .then(data => {
                        if (data.cin) {
                            cinInput.value = data.cin;
                        } else {
                            cinInput.value = '';
                        }
                    })
                    .catch(() => {
                        cinInput.value = '';
                    });
            } else {
                cinInput.value = '';
            }
        });
    }
});
