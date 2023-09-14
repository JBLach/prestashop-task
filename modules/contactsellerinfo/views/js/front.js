$(document).ready(function() {
  $('.copyButton').on('click', function() {
    const targetText = $('#' + $(this).data('copy-target')).text();
    if (copyToClipboard(targetText)) {
      showNotification('Skopiowano do schowka!');
    } else {
      showNotification('Nie udało się skopiować tekstu. Spróbuj ręcznie.');
    }
  });
});

function copyToClipboard(text) {
  const $textArea = $('<textarea></textarea>');
  $textArea.val(text).appendTo('body').select();
  const successful = document.execCommand('copy');
  $textArea.remove();
  return successful;
}

function showNotification(message) {
  const $notification = $('.seller-info__alert');
  $notification.text(message).show();
  setTimeout(() => $notification.hide(), 3000);
}