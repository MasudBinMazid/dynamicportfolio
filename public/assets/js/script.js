document.addEventListener("DOMContentLoaded", () => {
  console.log("Portfolio website loaded");
  const subscribeForm = document.querySelector('form');
  if (subscribeForm) {
    const subscribeButton = subscribeForm.querySelector('button[type="submit"]');
    if (subscribeButton) {
      subscribeButton.addEventListener('click', function(e) {
        e.preventDefault();
        subscribeButton.textContent = 'Subscribed!';
        subscribeButton.disabled = true;
        subscribeButton.classList.add('subscribed');
          // Show popup with OK button
          let popup = document.createElement('div');
          popup.style.position = 'fixed';
          popup.style.top = '50%';
          popup.style.left = '50%';
          popup.style.transform = 'translate(-50%, -50%)';
          popup.style.background = '#fff';
          popup.style.padding = '20px 40px';
          popup.style.borderRadius = '8px';
          popup.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
          popup.style.zIndex = '9999';
          popup.style.textAlign = 'center';

          let message = document.createElement('div');
          message.textContent = 'Thank you for subscribing!';
          popup.appendChild(message);

          let okButton = document.createElement('button');
          okButton.textContent = 'OK';
          okButton.style.marginTop = '15px';
          okButton.style.padding = '8px 24px';
          okButton.style.border = 'none';
          okButton.style.background = '#007bff';
          okButton.style.color = '#fff';
          okButton.style.borderRadius = '4px';
          okButton.style.cursor = 'pointer';
          okButton.onclick = function() {
            popup.remove();
          };
          popup.appendChild(okButton);

          document.body.appendChild(popup);
      });
    }
  }
});
