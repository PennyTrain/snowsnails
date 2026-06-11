document.querySelector("form").addEventListener("submit", function () {
    const btn = this.querySelector('button[type="submit"]');

    setTimeout(() => {
        btn.disabled = true;
        btn.textContent = "Submitting...";
    }, 0);
});

// this is to stop the site from breaking when someone spam clicks the submit button on any form

// and this is so that on the admin form, when they select the role as "Employee" it shows the correct feilds
document.addEventListener("DOMContentLoaded", function () {
    const roleSelect = document.getElementById("role");
    const employeeFields = document.getElementById("employeeFields");

    if (!roleSelect || !employeeFields) return;

    function toggleEmployeeFields() {
        if (roleSelect.value === "employee") {
            employeeFields.classList.remove("d-none");
        } else {
            employeeFields.classList.add("d-none");
        }
    }

    toggleEmployeeFields();
    roleSelect.addEventListener("change", toggleEmployeeFields);
});