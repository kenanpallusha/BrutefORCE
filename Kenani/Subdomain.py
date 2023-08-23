import os
import requests
import subprocess
from flask import Flask, request, jsonify

app = Flask(__name__)

def is_subdomain_reachable(subdomain_url):
    try:
        response = requests.get(subdomain_url)
        if response.status_code == 200:
            return True
    except:
        pass
    return False

@app.route("/upload.php", methods=["POST"])
def upload_file():
    domain = request.form.get("domain")

    uploaded_file = request.files["uploaded_file"]
    subdomains_content = uploaded_file.read().decode("utf-8")
    subdomains = [subdomain.strip() for subdomain in subdomains_content.splitlines()]

    result = []
    for subdomain in subdomains:
        subdomain_url = f"https://{subdomain}.{domain}"
        
        if is_subdomain_reachable(subdomain_url):
            result.append(subdomain_url)

            # Ping the subdomain
            ping_process = subprocess.Popen(['ping', '-c', '1', subdomain_url], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            ping_output, ping_error = ping_process.communicate()
            if ping_process.returncode == 0:
                print(f"Ping successful for {subdomain_url}")

    # Determine the path for tested_websites.txt in the same folder as Subdomain.py
    script_directory = os.path.dirname(os.path.abspath(__file__))
    tested_websites_path = os.path.join(script_directory, 'tested_websites.txt')

    # Save tested websites to the created file
    with open(tested_websites_path, 'w') as f:
        for subdomain_url in result:
            f.write(subdomain_url + '\n')

    print("Domain:", domain)
    print("Subdomains:", subdomains)
    print("Valid Subdomains:", result)

    # Display all tested subdomains in the console
    for subdomain_url in result:
        print("Tested Subdomain:", subdomain_url)

    # Send the list of tested websites to upload.php using a local file path
    with open(tested_websites_path, 'r') as f:
        tested_websites = f.read()
        response = requests.post('http://localhost/upload.php', data={'tested_content': tested_websites})
        print(response.text)  # Print the response from upload.php

    return jsonify({"subdomains": result})

if __name__ == "__main__":
    app.run()
