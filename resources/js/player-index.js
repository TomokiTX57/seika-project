document.addEventListener("DOMContentLoaded", function () {
    function addRowClickEvents() {
        document.querySelectorAll("#player-list tr").forEach(function (row) {
            row.addEventListener("click", function () {
                window.location = this.getAttribute("data-url");
            });
        });
    }

    addRowClickEvents();

    function searchPlayers() {
        let searchText = document
            .getElementById("search_name")
            .value.toLowerCase();
        let rows = document.querySelectorAll("#player-list tr");

        rows.forEach((row) => {
            let playerName = row.cells[0].textContent.toLowerCase();
            if (playerName.includes(searchText)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });

        addRowClickEvents();
    }

    document
        .getElementById("search_name")
        .addEventListener("input", searchPlayers);
});
