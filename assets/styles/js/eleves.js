function attachLyceeClasseListener(container = document) {
    const lyceeSelect = container.querySelector('#eleve_type_form_lycee');
    const classeSelect = container.querySelector('#eleve_type_form_classe');

    if (!lyceeSelect || !classeSelect) return;

    lyceeSelect.addEventListener('change', function () {
        const lyceeId = this.value;
        classeSelect.innerHTML = '<option value="">جار التحميل...</option>';
        classeSelect.disabled = true;

        if (lyceeId) {
            fetch(`/api/classes/${lyceeId}`)
                .then(response => response.json())
                .then(data => {
                    classeSelect.innerHTML = '<option value="">إختر القسم</option>';
                    data.forEach(classe => {
                        const option = document.createElement('option');
                        option.value = classe.id;
                        option.text = classe.nom;
                        classeSelect.appendChild(option);
                    });
                    classeSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des classes :', error);
                    classeSelect.innerHTML = '<option value="">خطأ في التحميل</option>';
                });
        }
    });
}

function attachFormSubmitHandler(form, modal) {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(resp => resp.text())
            .then(data => {
                if (data.trim() === 'success') {
                    modal.hide();
                    alert('Modification enregistrée avec succès!');
                    location.reload();
                } else {
                    const modalBody = document.getElementById('modal-body');
                    modalBody.innerHTML = data;

                    const newForm = modalBody.querySelector('form');
                    if (newForm) {
                        attachFormSubmitHandler(newForm, modal);
                        attachLyceeClasseListener(modalBody);
                    }
                }
            })
            .catch(err => {
                console.error('Erreur lors de la soumission:', err);
                alert('Erreur lors de la sauvegarde');
            });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            const cin = this.dataset.cin;

            fetch(`/eleve/${cin}/edit`)
                .then(response => {
                    if (!response.ok) throw new Error('Erreur réseau');
                    return response.text();
                })
                .then(html => {
                    const modalBody = document.getElementById('modal-body');
                    modalBody.innerHTML = html;

                    const modalElement = document.getElementById('editStudentModal');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    const form = modalBody.querySelector('form');
                    if (form) {
                        attachFormSubmitHandler(form, modal);
                        attachLyceeClasseListener(modalBody);
                    }
                })
                .catch(error => {
                    console.error('Erreur de chargement du formulaire:', error);
                    alert('Impossible de charger le formulaire');
                });
        });
    });

    // Initialiser la logique pour le formulaire principal (ajout)
    attachLyceeClasseListener(document);
});
