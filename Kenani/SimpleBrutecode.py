import requests
from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route("/bruteforce.php", methods=["GET"])
def upload_file():
    domain = request.form.get("domain")

    uploaded_file = request.files["uploaded_file"]
    subdomains_content = uploaded_file.read().decode("utf-8")
    subdomains = subdomains_content.splitlines()

    result = []
    for subdomain in subdomains:
        subdomain_url = f"https://{subdomain.strip()}.{domain}"
        try:
            response = requests.get(subdomain_url)
            if response.status_code == 200:
                result.append(subdomain_url)
        except:
            pass

    return jsonify(result)

if __name__ == "__main__":
    app.run()