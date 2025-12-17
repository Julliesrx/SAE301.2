document.addEventListener('DOMContentLoaded', () => {

    // --- GESTION DES LIKES (Cœur avec effet élastique) ---
    const likeButtons = document.querySelectorAll('.like-btn');

    likeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.dataset.id;
            const icon = this.querySelector('i');

            // Logique visuelle
            if (icon.classList.contains('far')) {
                // DEVIENT LIKÉ (Plein)
                icon.classList.remove('far');
                icon.classList.add('fas');

                // --- DÉBUT DE L'ANIMATION ---
                // 1. On ajoute la classe CSS qu'on vient de créer
                icon.classList.add('heart-bounce');

                // 2. On l'enlève après 400ms (durée de l'anim) pour pouvoir la refaire
                setTimeout(() => {
                    icon.classList.remove('heart-bounce');
                }, 400);
                // --- FIN DE L'ANIMATION ---

            } else {
                // DEVIENT UNLIKÉ (Vide)
                icon.classList.remove('fas');
                icon.classList.add('far');
                // Pas d'animation spéciale au "Dislike", c'est plus propre
            }

            // Appel AJAX (Reste identique)
            fetch(`index.php?page=like&id=${postId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) alert(data.error);
                })
                .catch(error => console.error('Erreur:', error));
        });
    });

// --- DISLIKES (Masquer + Enlever Like) ---
    const dislikeButtons = document.querySelectorAll('.dislike-btn');

    dislikeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.id;
            const card = this.closest('.card');
            
            // Éléments visuels
            const content = card.querySelector('.post-content');
            const overlay = card.querySelector('.post-overlay');
            const likeIcon = card.querySelector('.like-btn i'); // Le cœur

            // 1. Masquer le contenu (Visuel)
            content.classList.add('d-none');
            overlay.classList.remove('d-none');

            // 2. Enlever le Like visuellement (si présent)
            if (likeIcon.classList.contains('fas')) {
                likeIcon.classList.remove('fas');
                likeIcon.classList.add('far'); // Devient vide
                // Pas d'animation, on l'éteint juste discrètement
            }

            // 3. Appel Serveur (BDD)
            fetch(`index.php?page=dislike&id=${postId}`);
        });
    });

    // --- GESTION DU "RÉAFFICHER" ---
    const undoButtons = document.querySelectorAll('.undo-btn');

    undoButtons.forEach(button => {
        button.addEventListener('click', function () {
            const card = this.closest('.card');
            const content = card.querySelector('.post-content');
            const overlay = card.querySelector('.post-overlay');

            // Inverse : On cache le message et on remet le contenu
            overlay.classList.add('d-none');
            content.classList.remove('d-none');
        });
    });

});