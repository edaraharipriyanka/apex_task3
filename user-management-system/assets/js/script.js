// Show / Hide Password

function togglePassword(inputId, button)
{
    let input = document.getElementById(inputId);
    let icon = button.querySelector("i");

    if(input.type === "password")
    {
        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    }
    else
    {
        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }
}

// Auto Hide Alerts

setTimeout(() =>
{
    let alerts = document.querySelectorAll(".alert");

    alerts.forEach(alert =>
    {
        alert.style.transition = "0.5s";
        alert.style.opacity = "0";

        setTimeout(() =>
        {
            alert.remove();
        }, 500);
    });

}, 4000);

// Confirm Delete

function confirmDelete()
{
    return confirm(
        "Are you sure you want to delete this user?"
    );
}

// Preview Profile Image

function previewImage(event)
{
    const preview =
        document.getElementById("preview");

    preview.src =
        URL.createObjectURL(event.target.files[0]);

    preview.style.display = "block";
}

// Dashboard Counter Animation

function animateCounter(id, target)
{
    let counter = document.getElementById(id);

    if(!counter) return;

    let count = 0;

    let interval = setInterval(() =>
    {
        count++;

        counter.innerText = count;

        if(count >= target)
        {
            clearInterval(interval);
        }

    }, 20);
}