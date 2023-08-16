import requests
from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route("/upload.php", methods=["POST"])
def upload_file():
    domain = request.form.get("domain")

    uploaded_file = request.files["uploaded_file"]
    subdomains_content = uploaded_file.read().decode("utf-8")
    subdomains = [subdomain.strip() for subdomain in subdomains_content.splitlines()]

    result = []
    for subdomain in subdomains:
        subdomain_url = f"https://{subdomain}.{domain}"
        try:
            response = requests.get(subdomain_url)
            if response.status_code == 200:
                result.append(subdomain_url)
        except:
            pass

    # Log the data in the console
    print("Domain:", domain)
    print("Subdomains:", subdomains)
    print("Valid Subdomains:", result)

    return jsonify({"subdomains": result})

if __name__ == "__main__":
    app.run()
