<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subdomain Scanner</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
    <style>
        /* Add styles for the loading indicator */
        .loading-indicator {
            display: none; /* Hide by default */
            text-align: center;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body style="margin:auto 0;height:100vh;display:flex;">
    <div class="main-div">
        <div class="item-div">
            <p class="p1">Subdomain Scanner</p>
            <div>
                <div>
                    <input type="file" id="uploaded_file" name="uploaded_file" accept=".txt"
                        enctype="multipart/form-data" style="display: none;">
                    <label for="uploaded_file" class="custom-file-upload"><img src="upload.png" alt="upload" class="upload-img"></label>

                    <input type="text" id="domainInput" name="search" placeholder="Enter Domain" class="search-box">

                    <button style="background: none;border:none;" onclick="searchDomain()"><img src="search.png" alt="search" class="search-img"></button>
                </div>
            </div>

            <!-- Loading indicator -->
            <div id="loadingIndicator" class="loading-indicator">Loading...</div>

            <!-- Results container -->
            <div id="result" class="results"></div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.3/socket.io.js"></script>
    <script>
        var socket = io('http://localhost:5001');

        socket.on('update', function (subdomain) {
            var resultDiv = document.getElementById("result");
            var subdomainLink = document.createElement("a");
            subdomainLink.textContent = subdomain;
            subdomainLink.href = subdomain; // Set the href attribute to the subdomain URL
            subdomainLink.target = "_blank"; // Open links in a new tab (optional)
            resultDiv.appendChild(subdomainLink);
        });

        // Function to clear the selected file from the input field
        function clearFileInput() {
            var fileInput = document.getElementById("uploaded_file");
            fileInput.value = ""; // Clear the selected file
        }

        function searchDomain() {
            // Show the loading indicator
            var loadingIndicator = document.getElementById("loadingIndicator");
            loadingIndicator.style.display = "block";

            var domainInput = document.getElementById("domainInput").value;
            var uploadedFile = document.getElementById("uploaded_file").files[0];

            var formData = new FormData();
            formData.append("domain", domainInput);
            formData.append("uploaded_file", uploadedFile);

            var resultDiv = document.getElementById("result");
            resultDiv.innerHTML = ""; // Clear previous results

            fetch("http://localhost:5001/upload", {
                method: "POST",
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.subdomains && data.subdomains.length > 0) {
                        data.subdomains.forEach(subdomain => {
                            var subdomainLink = document.createElement("a");
                            subdomainLink.textContent = subdomain;
                            subdomainLink.href = subdomain; // Set the href attribute to the subdomain URL
                            subdomainLink.target = "_blank"; // Open links in a new tab (optional)
                            subdomainLink.classList.add("subdomain"); // Add a class for styling (optional)
                            resultDiv.appendChild(subdomainLink);
                        });
                    } else {
                        var messageElement = document.createElement("p");
                        messageElement.textContent = "No valid subdomains found.";
                        resultDiv.appendChild(messageElement);
                    }

                    // Hide the loading indicator when results are ready
                    loadingIndicator.style.display = "none";
                })
                .catch(error => {
                    console.error("Error:", error);
                    // Hide the loading indicator in case of an error
                    loadingIndicator.style.display = "none";
                });

            // Clear the file input
            clearFileInput();
        }
    </script>
</body>

</html>
