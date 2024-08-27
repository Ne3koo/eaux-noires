class App {
  constructor() {
    this.handleCommentForm();
  }

  handleCommentForm() {
    const commentForm = document.querySelector('.comment-form');

    if (commentForm) {
        let isSubmitting = false;

        commentForm.addEventListener('submit', function(event) {
            event.preventDefault();

            if (isSubmitting) {
                return; // Empêcher les soumissions multiples
            }

            isSubmitting = true; // Définir l'indicateur à true

            const formData = new FormData(this);

            fetch('/ajax/comment', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json', // S'assurer que la réponse est en JSON
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour le nombre de commentaires
                    const commentCountElement = document.getElementById('comment-count');
                    if (commentCountElement) {
                        commentCountElement.textContent = data.numberOfComments;
                    }

                    // Mettre à jour le contenu des commentaires
                    const commentContainerElement = document.getElementById('comment-container');
                    if (commentContainerElement) {
                        commentContainerElement.innerHTML = data.commentsHtml;
                    }

                    // Réinitialiser le formulaire
                    commentForm.reset();
                } else {
                    console.error('Erreur lors de l\'ajout du commentaire:', data.code);
                }
            })
            .catch(error => {
                console.error('Erreur lors de la requête AJAX:', error);
            })
            .finally(() => {
                isSubmitting = false; // Réinitialiser l'indicateur
            });
        });
    }
}
}

// Initialiser l'application
document.addEventListener('DOMContentLoaded', () => {
  new App();
});

function openMenuMobile() {
  document.querySelector('.header__nav').classList.add('open');
  document.querySelector('.overlay-menu-mobile').classList.add('open');
}
function closeMenuMobile() {
  const overlay = document.querySelector('.overlay-menu-mobile');
  const nav = document.querySelector('.header__nav');

  overlay.classList.add('closing');
  nav.classList.add('closing');

  setTimeout(() => {
      overlay.classList.remove('open', 'closing');
      nav.classList.remove('open', 'closing')
  }, 300); 
} 
