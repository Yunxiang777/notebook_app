let currentNoteId = null;

// 顯示新增記事 Modal
function showCreateModal() {
  document.getElementById("createModal").style.display = "block";
  document.getElementById("new-title").value = "";
  document.getElementById("new-content").value = "";
}

// 關閉新增記事 Modal
function closeCreateModal() {
  document.getElementById("createModal").style.display = "none";
}

// 點擊 Modal 外部關閉
window.onclick = function (event) {
  const modal = document.getElementById("createModal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
};

// 新增記事
async function createNote() {
  const title = document.getElementById("new-title").value.trim();
  const content = document.getElementById("new-content").value.trim();

  if (!title || !content) {
    alert("請填寫標題和內容");
    return;
  }

  try {
    const response = await fetch("api/notes.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ title, content }),
    });

    const result = await response.json();

    if (result.success) {
      alert("記事新增成功！");
      closeCreateModal();
      location.reload();
    } else {
      alert("新增失敗：" + result.message);
    }
  } catch (error) {
    alert("發生錯誤：" + error.message);
  }
}

// 選擇記事
async function selectNote(noteId) {
  try {
    const response = await fetch(`api/notes.php?id=${noteId}`);
    const result = await response.json();

    if (result.success) {
      const note = result.data;
      currentNoteId = note.id;

      document.getElementById("empty-state").style.display = "none";
      document.getElementById("note-editor").style.display = "flex";

      document.getElementById("note-id").value = note.id;
      document.getElementById("note-title").value = note.title;
      document.getElementById("note-text").value = note.content;
    }
  } catch (error) {
    alert("載入記事失敗：" + error.message);
  }
}

// 儲存記事
async function saveNote() {
  const id = document.getElementById("note-id").value;
  const title = document.getElementById("note-title").value.trim();
  const content = document.getElementById("note-text").value.trim();

  if (!title || !content) {
    alert("標題和內容不能為空");
    return;
  }

  try {
    const response = await fetch("api/notes.php", {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id, title, content }),
    });

    const result = await response.json();

    if (result.success) {
      alert("儲存成功！");
      location.reload();
    } else {
      alert("儲存失敗：" + result.message);
    }
  } catch (error) {
    alert("發生錯誤：" + error.message);
  }
}

// 刪除記事
async function deleteNote() {
  if (!confirm("確定要刪除這則記事嗎？")) {
    return;
  }

  const id = document.getElementById("note-id").value;

  try {
    const response = await fetch("api/notes.php", {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id }),
    });

    const result = await response.json();

    if (result.success) {
      alert("刪除成功！");
      location.reload();
    } else {
      alert("刪除失敗：" + result.message);
    }
  } catch (error) {
    alert("發生錯誤：" + error.message);
  }
}

// 取消編輯
function cancelEdit() {
  document.getElementById("note-editor").style.display = "none";
  document.getElementById("empty-state").style.display = "block";
  currentNoteId = null;
}
