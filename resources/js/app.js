import './bootstrap';

// Import Bootstrap JavaScript
import 'bootstrap';

// Custom JavaScript for API documentation or admin interface
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize Bootstrap popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // API endpoint testing functionality (if needed)
    const apiTestButtons = document.querySelectorAll('.test-endpoint');
    apiTestButtons.forEach(button => {
        button.addEventListener('click', function() {
            const endpoint = this.dataset.endpoint;
            const method = this.dataset.method;
            testApiEndpoint(endpoint, method);
        });
    });
});

// Function to test API endpoints (for documentation page)
function testApiEndpoint(endpoint, method) {
    const token = localStorage.getItem('api_token');
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    fetch(endpoint, {
        method: method,
        headers: headers
    })
    .then(response => response.json())
    .then(data => {
        console.log('API Response:', data);
        // Show result in modal or alert
        showApiResult(data);
    })
    .catch(error => {
        console.error('API Error:', error);
        showApiError(error);
    });
}

function showApiResult(data) {
    // Create or update result display
    const resultDiv = document.getElementById('api-result');
    if (resultDiv) {
        resultDiv.innerHTML = `<pre class="bg-light p-3 rounded">${JSON.stringify(data, null, 2)}</pre>`;
    }
}

function showApiError(error) {
    const resultDiv = document.getElementById('api-result');
    if (resultDiv) {
        resultDiv.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
    }
}
