<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subdomain Scanner</title>
    <link rel="stylesheet" type="text/css" href="./style.css">

</head>

<body style="margin:auto 0;height:100vh;display:flex;">
    <div class="main-div">
        <div class="item-div">
            <p class="p1">Subdomain Scanner</p>
            <div>
                <div>
                    <input type="text" id="domainInput" name="search" placeholder="Enter Domain" class="search-box">
                    <input type="file" id="uploaded_file" name="uploaded_file" accept=".txt"
                        enctype="multipart/form-data">
                    <button class="search-btn" onclick="searchDomain()">Scan Subdomains</button>
                </div>
            </div>

            <div id="result" style="color:green;"></div> <!-- This is where the results will be displayed -->
            <style>
                /* Add your custom styling here */
                .subdomain {
                    color: green;
                    font-size: 15px;
                }
            </style>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.3/socket.io.js"></script>
    <script>
        var socket = io('http://localhost:5001');

        socket.on('update', function (subdomain) {
            var resultDiv = document.getElementById("result");
            var subdomainElement = document.createElement("p");
            subdomainElement.textContent = subdomain;
            subdomainElement.classList.add("subdomain");
            resultDiv.appendChild(subdomainElement);
        });

        function searchDomain() {
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
                            var subdomainElement = document.createElement("p");
                            subdomainElement.textContent = subdomain;
                            resultDiv.appendChild(subdomainElement);
                        });
                    } else {
                        var messageElement = document.createElement("p");
                        messageElement.textContent = "No valid subdomains found.";
                        resultDiv.appendChild(messageElement);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        }
    </script>
</body>

</html>