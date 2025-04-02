function showForm(formId) {
    document.querySelectorAll('.form-box, .box, .page-container').forEach(form => {
        form.classList.remove('active');
        form.style.display = 'none';
    });
    var activeForm = document.getElementById(formId);
    activeForm.classList.add('active');
    activeForm.style.display = 'block';

    var pageContainer = document.querySelector('.page-container');
    if (formId === 'welcome') {
        pageContainer.style.display = 'flex';
    } else {
        pageContainer.style.display = 'none';
    }
}

// important frog function
function frog() {
    console.log("Ribbit!");
}

function fadeOutMessages() {
    setTimeout(function() {
        var errorMessage = document.getElementById('error-message');
        var successMessage = document.getElementById('success-message');
        if (errorMessage) {
            errorMessage.style.transition = 'opacity 1s';
            errorMessage.style.opacity = '0';
            setTimeout(function() {
                errorMessage.style.display = 'none';
            }, 1000);
        }
        if (successMessage) {
            successMessage.style.transition = 'opacity 1s';
            successMessage.style.opacity = '0';
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 1000);
        }
    }, 5000);
}

fadeOutMessages();