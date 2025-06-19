document.addEventListener('DOMContentLoaded', function () {
    document.getElementById("queryForm").addEventListener("submit", function (e) {
        e.preventDefault();
        document.getElementById("responseMsg").innerHTML = `<div class="alert alert-success">Query submitted successfully! We'll get back to you soon.</div>`;
        this.reset();
        document.getElementById("previewBox").classList.add("d-none");
    });

    window.previewQuery = function() {
        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const category = document.getElementById("category").value;
        const priority = document.querySelector('input[name="priority"]:checked').value;
        const subject = document.getElementById("subject").value;
        const message = document.getElementById("message").value;

        const preview = `
            <strong>Name:</strong> ${name}<br/>
            <strong>Email:</strong> ${email}<br/>
            <strong>Category:</strong> ${category}<br/>
            <strong>Priority:</strong> ${priority}<br/>
            <strong>Subject:</strong> ${subject}<br/>
            <strong>Message:</strong><br/> ${message.replace(/\n/g, "<br/>")}
        `;

        document.getElementById("previewContent").innerHTML = preview;
        document.getElementById("previewBox").classList.remove("d-none");
    }
});