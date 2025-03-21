document.addEventListener("DOMContentLoaded", function () {
    const chipsView = document.getElementById("chips_view");
    const chipsReal = document.getElementById("chips_real");

    if (chipsView && chipsReal) {
        chipsView.addEventListener("input", function () {
            let rawValue = chipsView.value.replace(/,/g, "");

            // 数字と先頭のマイナスのみ許可
            let numericValue = rawValue
                .replace(/[^\d-]/g, "")
                .replace(/(?!^)-/g, "");

            // 整形表示（ただし "-" 単体はそのままにする）
            if (numericValue === "-") {
                chipsView.value = "-";
                chipsReal.value = "";
            } else if (numericValue) {
                const number = parseInt(numericValue, 10);
                chipsView.value = number.toLocaleString();
                chipsReal.value = number;
            } else {
                chipsView.value = "";
                chipsReal.value = "";
            }
        });
    }
});
