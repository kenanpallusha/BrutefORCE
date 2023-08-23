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
					<input type="file" id="uploaded_file" name="uploaded_file" accept=".txt" enctype="multipart/form-data">
					<button class="search-btn" onclick="searchDomain()">Scan Subdomains</button>
				</div>
			</div>

			<div id="result"></div> <!-- This is where the results will be displayed -->
		</div>
	</div>
	<script>
		function searchDomain() {
			var domainInput = document.getElementById("domainInput").value;
			var uploadedFile = document.getElementById("uploaded_file").files[0];

			var formData = new FormData();
			formData.append("domain", domainInput);
			formData.append("uploaded_file", uploadedFile);

			var resultDiv = document.getElementById("result");
			resultDiv.innerHTML = ""; // Clear previous results

			var xhr = new XMLHttpRequest();
			xhr.open("POST", "upload.php", true);
			xhr.onreadystatechange = function () {
				if (xhr.readyState == 4 && xhr.status == 200) {
					var response = JSON.parse(xhr.responseText);

					if (response.subdomains && response.subdomains.length > 0) {
						response.subdomains.forEach(function (subdomain) {
							var subdomainElement = document.createElement("p");
							subdomainElement.textContent = subdomain;
							resultDiv.appendChild(subdomainElement);
						});
					} else {
						var messageElement = document.createElement("p");
						messageElement.textContent = "No valid subdomains found.";
						resultDiv.appendChild(messageElement);
					}
				}
			};
			xhr.send(formData);
		}

		// SSE to receive real-time updates
		var eventSource = new EventSource("sse.php");
		eventSource.onmessage = function(event) {
			var subdomain = event.data;
			var subdomainElement = document.createElement("p");
			subdomainElement.textContent = subdomain;
			resultDiv.appendChild(subdomainElement);
		};
	</script>
</body>

</html>
