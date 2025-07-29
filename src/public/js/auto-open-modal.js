document.addEventListener('DOMContentLoaded', function () {
    const modal = document.querySelector('[data-auto-open-modal]');
    console.log("モーダル対象:", modal); 
    if (modal) {
        window.location.hash = modal.id;
    }
});