document.addEventListener("DOMContentLoaded", () => {
    // 編集ボタン処理
    window.enableEdit = (id) => {
        document.getElementById(`display-chips-${id}`)?.classList.add("d-none");
        document.getElementById(`edit-chips-${id}`)?.classList.remove("d-none");

        document
            .getElementById(`display-comment-${id}`)
            ?.classList.add("d-none");
        document
            .getElementById(`edit-comment-${id}`)
            ?.classList.remove("d-none");

        document.getElementById(`save-btn-${id}`)?.classList.remove("d-none");
    };

    // 保存処理
    window.saveEdit = (id) => {
        const newChips = document.getElementById(`edit-chips-${id}`)?.value;
        const newComment = document.getElementById(`edit-comment-${id}`)?.value;

        console.log("送信内容:", { id, newChips, newComment });

        // detail- or ring- の判別
        let url = "";
        if (id.startsWith("detail-")) {
            const detailId = id.replace("detail-", "");
            url = `/zero-system-details/${detailId}`;
        } else if (id.startsWith("ring-")) {
            const ringId = id.replace("ring-", "");
            url = `/ring-transactions/${ringId}`;
        }

        fetch(url, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ chips: newChips, comment: newComment }),
        })
            .then((response) => {
                console.log("サーバーレスポンス:", response.status);
                if (!response.ok) {
                    return response.text().then((text) => {
                        console.error("レスポンスエラー:", text);
                        throw new Error("保存に失敗しました");
                    });
                }
                return response.json();
            })
            .then((data) => {
                console.log("更新成功:", data);
                location.reload();
            })
            .catch((error) => {
                alert(error.message);
            });
    };

    window.confirmDelete = (id) => {
        if (!confirm("このデータを削除しますか？")) return;

        console.log("削除対象 ID:", id);

        let url = "";
        if (id.startsWith("detail-")) {
            url = `/zero-system-details/${id.replace("detail-", "")}`;
        } else if (id.startsWith("ring-")) {
            url = `/ring-transactions/${id.replace("ring-", "")}`;
        }

        fetch(url, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
            .then((response) => {
                if (response.ok) {
                    document.getElementById(`row-${id}`)?.remove();
                } else {
                    return response.text().then((text) => {
                        console.error("削除エラー:", text);
                        throw new Error("削除に失敗しました");
                    });
                }
            })
            .catch((error) => {
                alert(error.message);
            });
    };
});
