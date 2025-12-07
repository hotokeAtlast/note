function showToast(message, isError = false) {
  const toast = document.getElementById("toast");
  toast.textContent = message;
  toast.style.background = isError ? "#d32f2f" : "#4CAF50";
  toast.classList.add("show");

  setTimeout(() => {
    toast.classList.remove("show");
  }, 6000);
}


document.getElementById("logoutBtn").addEventListener("click", function () {
  showToast("logging out in 4 seconds...");
  startRedirectCountdown(4, "logout.php");
});

function startRedirectCountdown(seconds, targetUrl) {
  let remaining = seconds;

  const countdownInterval = setInterval(() => {
    remaining--;

    // Update toast content with live countdown
    const toast = document.getElementById("toast");
    toast.textContent = `Redirecting in ${remaining} second${
      remaining !== 1 ? "s" : ""
    }...`;

    if (remaining <= 0) {
      clearInterval(countdownInterval);
      window.location.href = targetUrl;
    }
  }, 1000);
}

function openNoteModal(noteId, isEdit = false) {
  fetch(`get_note.php?id=${noteId}`)
    .then(res => {
      if (!res.ok) {
        return res.json().then(err => { throw new Error(err.error); });
      }
      return res.json();
    })
    .then(note => {
      document.getElementById('modalTitle').textContent = note.title;
      document.getElementById('modalDate').textContent = "Created on " + note.date;

      if (isEdit) {
        document.getElementById('modalContent').style.display = 'none';
        document.getElementById('editArea').style.display = 'block';
        document.getElementById('saveBtn').style.display = 'inline-block';
        document.getElementById('editArea').value = note.content;
        document.getElementById('saveBtn').setAttribute('data-id', note.id);
      } else {
        document.getElementById('modalContent').innerText = note.content;
        document.getElementById('modalContent').style.display = 'block';
        document.getElementById('editArea').style.display = 'none';
        document.getElementById('saveBtn').style.display = 'none';
      }

      document.getElementById('noteModal').style.display = 'flex';
    })
    .catch(err => {
      showToast(err.message || "Something went wrong", true);
    });
}

function closeModal() {
  document.getElementById("noteModal").style.display = "none";
}

function saveNote() {
  const noteId = document.getElementById("saveBtn").getAttribute("data-id");
  const newContent = document.getElementById("editArea").value;

  fetch("update_note.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: noteId, content: newContent }),
  })
    .then((res) => res.text())
    .then((response) => {
      alert(response);
      closeModal();
      location.reload(); // or update DOM without reloading
    });
}
