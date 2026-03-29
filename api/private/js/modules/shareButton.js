const $shareButton = document.getElementById('share-button');

if($shareButton) {
  $shareButton.addEventListener('click', (e) => {
    const dataTitle = $shareButton.getAttribute('data-share-title');
    const dataDesc = $shareButton.getAttribute('data-share-desc');
    const dataUrl = $shareButton.getAttribute('data-share-url');
    if (navigator.share) {
      navigator.share({
          title: dataTitle,
          text: dataDesc,
          url: dataUrl,
        })
        .then(() => console.log('Successful share'))
        .catch((error) => console.log('Error sharing', error));
    } else {
      console.log('Share not supported on this browser.');
    }
  });
}
