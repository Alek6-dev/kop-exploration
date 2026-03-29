const $flashMessage = document.querySelector('.js-flash-message');
const $closeButton = document.querySelector('.js-flash-message button');

if($closeButton) {
  $closeButton.addEventListener('click', (e) => {
    $flashMessage.classList.add('hidden');
  });
}
