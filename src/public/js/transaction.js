document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.chat__edit-textarea').forEach(textarea => {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    });
});

document.getElementById('image').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            document.querySelector('.send-chat__image').src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
});

// document.getElementById('messageInput').addEventListener('input', function () {
//     localStorage.setItem('savedMessage', this.value);
// });


// window.addEventListener('DOMContentLoaded', function () {
//     const saved = localStorage.getItem('savedMessage');
//     if (saved) {
//         document.getElementById('messageInput').value = saved;
//     }
// });
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('messageInput');
    if (!input) return;

    const transactionId = input.dataset.transactionId;
    const storageKey = `savedMessage-${transactionId}`;

    const saved = localStorage.getItem(storageKey);
    if (saved) {
        input.value = saved;
    }

    if (!input.readOnly) {
        input.addEventListener('input', function () {
            localStorage.setItem(storageKey, this.value);
        });
    }

    const form = input.closest('form');
    if (form) {
        form.addEventListener('submit', function () {
            localStorage.removeItem(storageKey);
        });
    }
});
