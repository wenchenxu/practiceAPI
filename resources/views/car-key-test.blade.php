<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Essential for Laravel POST requests --}}
    <title>Car Key API Tester</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"] { padding: 8px; margin-right: 5px; min-width: 200px; }
        button { padding: 8px 15px; cursor: pointer; }
        #responseArea { margin-top: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9; white-space: pre-wrap; word-wrap: break-word; }
        .loading { color: blue; }
        .error { color: red; }
    </style>
</head>
<body>

    <h1>Test Car Key API Coordinate Endpoint</h1>

    {{-- We handle submission via JavaScript, so the form's action/method aren't strictly needed --}}
    <form id="deviceForm">
        <div>
            <label for="deviceIdInput">Device ID:</label>
            <input type="text" id="deviceIdInput" name="devId" required>
            <button type="submit">Send POST Request</button>
        </div>
    </form>

    <h2>API Response:</h2>
    <div id="responseArea">
        Click the button to send the request.
    </div>

    <script>
        const form = document.getElementById('deviceForm');
        const deviceIdInput = document.getElementById('deviceIdInput');
        const responseArea = document.getElementById('responseArea');
        // Get CSRF token from meta tag for Laravel POST requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default browser form submission

            const deviceId = deviceIdInput.value;
            if (!deviceId) {
                responseArea.textContent = 'Please enter a Device ID.';
                responseArea.className = 'error'; // Add error class for styling
                return;
            }

            responseArea.textContent = 'Loading...';
            responseArea.className = 'loading'; // Add loading class

            // Use the Fetch API to send the POST request
            fetch('{{ route("car-key.post-coords") }}', { // Use Laravel route() helper
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Specify we're sending JSON
                    'Accept': 'application/json',       // Specify we accept JSON response
                    'X-CSRF-TOKEN': csrfToken          // Include CSRF token for Laravel security
                },
                body: JSON.stringify({ deviceId: deviceId }) // Send deviceId in the request body as JSON
            })
            .then(response => {
                // Check if the response was successful (status code 2xx)
                // We proceed even for errors here to display the API's error message if any
                return response.json(); // Parse the JSON response body
            })
            .then(data => {
                // Display the raw JSON response
                responseArea.textContent = JSON.stringify(data, null, 2); // Pretty print JSON
                responseArea.className = ''; // Remove loading/error class
            })
            .catch(error => {
                // Handle network errors or issues with the fetch itself
                console.error('Fetch Error:', error);
                responseArea.textContent = 'Network error or failed to fetch. Check console.';
                responseArea.className = 'error'; // Add error class
            });
        });
    </script>

</body>
</html>