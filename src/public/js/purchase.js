document.addEventListener('DOMContentLoaded', function () {
    const paymentSelect = document.getElementById('purchaseMethodSelect');
    const paymentInput = document.getElementById('paymentMethodInput');

    // select ボックスの変更時に発火
    paymentSelect.addEventListener('change', function () {
        // 選択された値を取得して反映
        const selectedValue = paymentSelect.value;
        paymentInput.value = selectedValue ?
        selectedValue : '選択してください';
    });
});