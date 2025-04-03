document.addEventListener("DOMContentLoaded", () => {
    // 編集ボタン処理
    window.enableEdit = (id) => {
        document.getElementById(`display-chips-${id}`).classList.add("d-none");
        document.getElementById(`edit-chips-${id}`).classList.remove("d-none");
        document
            .getElementById(`display-comment-${id}`)
            .classList.add("d-none");
        document
            .getElementById(`edit-comment-${id}`)
            .classList.remove("d-none");
        document.getElementById(`save-btn-${id}`).classList.remove("d-none");
    };

    // 保存処理
    window.saveEdit = (id) => {
        const newChips = document.getElementById(`edit-chips-${id}`).value;
        const newComment = document.getElementById(`edit-comment-${id}`).value;

        fetch(`/ring-transactions/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ chips: newChips, comment: newComment }),
        }).then((response) => {
            if (response.ok) {
                location.reload();
            } else {
                alert("保存に失敗しました");
            }
        });
    };

    // 削除確認
    window.confirmDelete = (id) => {
        if (confirm("このデータを削除しますか？")) {
            fetch(`/ring-transactions/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            }).then((response) => {
                if (response.ok) {
                    document.getElementById(`row-${id}`).remove();
                } else {
                    alert("削除に失敗しました");
                }
            });
        }
    };
});
