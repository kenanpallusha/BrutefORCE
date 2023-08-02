<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body style="margin:auto 0;height:100vh;display:flex;">
	<div class="main-div">
		<div class="item-div">

			<p class="p1">Subdomain Bruteforce</p>
			<div>
				<input type="text" name="search" placeholder="Search Domain" class="search-box">
				<button class="search-btn">Search</button>
			</div>
			<form action="upload.php" method="post" enctype="multipart/form-data">
				<input style="color:green; font-size:15px;width:15rem;" type="file" name="fileToUpload" id="fileToUpload">
				<button class="upload" type="submit" value="Upload Image">Upload</button>
			</form>
		</div>
	</div>

	<!-- Shearch Box qe e ka me shkru te dhanat e subdomainit 

psh: google.com (search) 

incinailzohet me python brute force e cila ben kerkime online te subdomainve te google.com 
//nepermjet listes e cila e ka me larte ose kudo qofte sipas dizajnit te frontit, me pas edhe ni uplouad .txt

dmt lista e subdomainve potencial 
-->

</body>
</html>


