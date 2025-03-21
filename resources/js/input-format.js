document.addEventListener("DOMContentLoaded", function () {
    const chipsView = document.getElementById("chips_view");
    const chipsReal = document.getElementById("chips_real");

    if (chipsView && chipsReal) {
        chipsView.addEventListener("input", function () {
            const numericValue = chipsView.value
                .replace(/,/g, "")
                .replace(/[^\d]/g, "");
            chipsView.value = Number(numericValue).toLocaleString();
            chipsReal.value = numericValue;
        });
    }
});
