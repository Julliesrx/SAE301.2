document.addEventListener('DOMContentLoaded', () => {

    // ============================================================
    // 1. GESTION DES LIKES (Cœur élastique + AJAX)
    // ============================================================
    const likeButtons = document.querySelectorAll('.like-btn');

    likeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.dataset.id;
            const icon = this.querySelector('i');

            // Logique visuelle
            if (icon.classList.contains('far')) {
                // LIKE
                icon.classList.remove('far');
                icon.classList.add('fas');

                // Animation "Boing"
                icon.classList.add('heart-bounce');
                setTimeout(() => {
                    icon.classList.remove('heart-bounce');
                }, 400);

            } else {
                // UNLIKE
                icon.classList.remove('fas');
                icon.classList.add('far');
            }

            // Appel au serveur (AJAX)
            fetch(`index.php?page=like&id=${postId}`)
                .then(response => response.json())
                .catch(error => console.error('Erreur:', error));
        });
    });

    // ============================================================
    // 2. GESTION DES DISLIKES (Masquer le post)
    // ============================================================
    const dislikeButtons = document.querySelectorAll('.dislike-btn');

    dislikeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.dataset.id;
            const card = this.closest('.card');

            const content = card.querySelector('.post-content');
            const overlay = card.querySelector('.post-overlay');
            const likeIcon = card.querySelector('.like-btn i');

            // Masquer le contenu
            content.classList.add('d-none');
            overlay.classList.remove('d-none');

            // Retirer le like visuellement si présent
            if (likeIcon.classList.contains('fas')) {
                likeIcon.classList.remove('fas');
                likeIcon.classList.add('far');
            }

            // Appel au serveur
            fetch(`index.php?page=dislike&id=${postId}`);
        });
    });

    // Bouton "Réafficher"
    const undoButtons = document.querySelectorAll('.undo-btn');
    undoButtons.forEach(button => {
        button.addEventListener('click', function () {
            const card = this.closest('.card');
            const content = card.querySelector('.post-content');
            const overlay = card.querySelector('.post-overlay');

            overlay.classList.add('d-none');
            content.classList.remove('d-none');
        });
    });

    // ============================================================
    // 3. PRÉVISUALISATION IMAGE (Le petit bonus UX)
    // ============================================================
    const fileInput = document.querySelector('input[type="file"]');

    // On crée la zone d'aperçu dynamiquement si elle n'existe pas
    if (fileInput) {
        const previewDiv = document.createElement('div');
        previewDiv.id = 'preview-container';
        previewDiv.className = 'mt-3 text-center';
        fileInput.parentNode.appendChild(previewDiv);

        fileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewDiv.innerHTML = `
                        <p class="text-muted small mb-1">Aperçu :</p>
                        <img src="${e.target.result}" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                    `;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // ============================================================
    // 4. CONFIRMATION SUPPRESSION (Plus propre que le HTML)
    // ============================================================
    // Cible tous les liens qui contiennent "delete" dans l'URL
    const deleteLinks = document.querySelectorAll('a[href*="delete"]');

    deleteLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            if (!confirm("⚠️ Êtes-vous sûr de vouloir supprimer ceci ?\nCette action est irréversible.")) {
                e.preventDefault(); // Annule le clic si l'utilisateur dit Non
            }
        });
    });

});