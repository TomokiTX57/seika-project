window.submitUnifiedForm = function (type) {
    const form = document.getElementById("unified-form");

    if (!form) return;

    const amountInput = form.querySelector(
        'input[name="amount"], input[name="zero_amount"]'
    );
    const commentInput = form.querySelector(
        'textarea[name="withdraw_comment"]'
    );

    if (!amountInput) {
        alert("金額フィールドが見つかりません。");
        return;
    }

    if (
        !amountInput.value ||
        isNaN(amountInput.value) ||
        parseInt(amountInput.value) <= 0
    ) {
        alert("正しい金額を入力してください。");
        return;
    }

    if (type === "withdraw") {
        form.action = form.dataset.withdrawUrl;
        amountInput.name = "withdraw_amount";

        if (commentInput) commentInput.name = "withdraw_comment";
    } else if (type === "zero") {
        form.action = form.dataset.zeroUrl;
        amountInput.name = "zero_amount";

        if (commentInput) commentInput.name = ""; // コメントは送信しない
    } else {
        console.error("無効なタイプです: " + type);
        return;
    }

    form.submit();
};
