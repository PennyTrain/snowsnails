document.querySelector("form").addEventListener("submit", function () {
    const btn = this.querySelector('button[type="submit"]');

    setTimeout(() => {
        btn.disabled = true;
        btn.textContent = "Submitting...";
    }, 0);
});

// this is to stop the site from breaking when someone spam clicks the submit button on any form