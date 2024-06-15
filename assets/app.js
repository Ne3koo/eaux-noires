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
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            document.getElementById('comment-count').textContent = data.numberOfComments;
            document.getElementById('comment-container').innerHTML = data.commentsHtml;
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
