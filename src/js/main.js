// Initialize Realtime Clock
function initRealtimeClock() {
    function updateTime() {
        document.getElementById('time').innerHTML = moment().format('h:mm:ss a');
    }
    updateTime();
    setInterval(updateTime, 1000);
}

// Handle Form Search
function initSearch() {
  let searchForm = document.getElementById("searchForm");
  if (!searchForm) return;

  searchForm.addEventListener("submit", function (event) {
      event.preventDefault();
      let searchValue = document.querySelector("input[name='search']").value;

      fetch(`app/overview.php?search=${encodeURIComponent(searchValue)}`)
          .then(response => response.text())
          .then(data => document.getElementById("search-results").innerHTML = data)
          .catch(error => console.error("Search Error:", error));
  });
}


// Show Modal on Page Load
function showAutoModal() {
    let autoModal = document.getElementById('myModal');
    if (autoModal) new bootstrap.Modal(autoModal).show();
}

// Handle Modals (Edit & Delete)
function initModals() {
    document.addEventListener("show.bs.modal", function (event) {
        let modal = event.target;
        let button = event.relatedTarget;

        if (!button) return;

        if (modal.id === "editModal") {
            document.getElementById("editMemberID").value = button.getAttribute("data-memberid");
            document.getElementById("editISBN").value = button.getAttribute("data-isbn");
            document.getElementById("editBorrowDate").value = button.getAttribute("data-borrowdate");
            document.getElementById("editDueDate").value = button.getAttribute("data-duedate");
        } else if (modal.id === "deleteModal") {
            document.getElementById("deleteMemberID").value = button.getAttribute("data-memberid");
            document.getElementById("deleteISBN").value = button.getAttribute("data-isbn");
        } else if (modal.id === "editMemberModal"){
          document.getElementById("editMemberID").value = button.getAttribute("data-memberid");
          document.getElementById("editLastName").value = button.getAttribute("data-lastname");
          document.getElementById("editFirstName").value = button.getAttribute("data-firstname");
          document.getElementById("editEmail").value = button.getAttribute("data-email");
          document.getElementById("editContact").value = button.getAttribute("data-contact");
          document.getElementById("editJoinDate").value = button.getAttribute("data-joindate");
        }else if (modal.id === "deleteMemberModal") {
          document.getElementById("deleteMemberID").value = button.getAttribute("data-memberid");
        }        if (modal.id === "editBookModal") {
          document.getElementById("editBookID").value = button.getAttribute("data-bookid");
          document.getElementById("editISBN").value = button.getAttribute("data-isbn");
          document.getElementById("editTitle").value = button.getAttribute("data-title");
          document.getElementById("editYear").value = button.getAttribute("data-year");
          document.getElementById("editAuthor").value = button.getAttribute("data-author");
      } 
      else if (modal.id === "deleteBookModal") {
        document.getElementById("deleteBookID").value = button.getAttribute("data-bookid");
      }
    });
}

// Handle Create Form Submission
function initCreateForm() {
    let createForm = document.getElementById("createForm");
    if (!createForm) return;

    createForm.addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch("processing.php", { method: "POST", body: formData })
            .then(response => response.json())
            .then(data => {
                let messageBox = document.getElementById("createMessage");
                messageBox.innerHTML = `<div class="alert alert-${data.success ? 'success' : 'danger'}">${data.message}</div>`;

                if (data.success) {
                    setTimeout(() => {
                        messageBox.innerHTML = "";
                        createForm.reset();
                        new bootstrap.Modal(document.getElementById("createModal")).hide();
                        location.reload(); 
                    }, 1000);
                }
            })
            .catch(error => console.error("Form Submission Error:", error));
    });
}


// Initialize All Functions on Page Load
document.addEventListener("DOMContentLoaded", function () {
    initRealtimeClock();
    initSearch();
    showAutoModal();
    initModals();
    initCreateForm();

});
