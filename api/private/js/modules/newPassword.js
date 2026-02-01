const $buttonPassword = document.getElementById('deploy-new-password');
const $fieldsPassword = document.getElementById('js-new-password');
const $errorInPassword = document.querySelectorAll('#js-new-password .form-error');

if($buttonPassword) {
  $buttonPassword.addEventListener('click', (e) => {
    displayFields();
  });
}

if($errorInPassword.length > 0) {
  displayFields();
}

function displayFields() {
  $fieldsPassword.classList.remove('hidden');
  $buttonPassword.parentNode.classList.add('hidden');
}
