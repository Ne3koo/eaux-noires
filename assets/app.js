document.addEventListener('DOMContentLoaded', () => {
  new App();
})

class App {
  constructor() {
    this.handleCommentForm();
  }

  handleCommentForm() {

    const commentForm = document.querySelector('form.comment-form');

    if(null === document.querySelector('form.comment-form')) {
      return;
    }

    commentForm.addEventListener('submit', async(e) =>{
      e.preventDefault();
      //console.log(e);

      const response = await fetch('/ajax/comment', {
        method: 'POST',
        body: new FormData(e.target)
      });

      if(!response.ok) {
        return;
      }

      const json = await response.json;

      console.log(json);

      if(json === 'COMMENT_ADD_SUCCESS') {
        const commentList = document.querySelector('.comment-list');
        const commentCount = document.querySelector('#comment-count');
        const commentContent = document.querySelector('#comment_content');
        commentList.insertAdjacentHTML('beforeend', json.message);
        commentList.lastElementChild.scrollIntoView();
        commentCount.innerText = json.numberOfComments;
        commentContent.value = '';
      }
    })
  }
}

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
