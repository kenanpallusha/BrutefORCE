import os
import requests
import subprocess
from flask import Flask, request, jsonify
from flask_socketio import SocketIO, emit
from flask_cors import CORS

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}})
socketio = SocketIO(app, cors_allowed_origins="*")

def is_subdomain_reachable(subdomain_url):
    try:
        response = requests.get(subdomain_url)
        if response.status_code == 200:
            return True
    except:
        pass
    return False

@socketio.on('connect')
def ws_connect():
    print("WebSocket client connected")

def send_realtime_update(subdomain):
    socketio.emit('update', subdomain)

@app.route("/upload", methods=["POST"])
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
            print("here")   
            # Ping the subdomain
            ping_process = subprocess.Popen(['ping', '-c', '1', subdomain_url], stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            ping_output, ping_error = ping_process.communicate()
            if ping_process.returncode == 0:
                print(f"Ping successful for {subdomain_url}")
                send_realtime_update(subdomain_url)  # Emit a WebSocket message

    print("Domain:", domain)
    print("Subdomains:", subdomains)
    print("Valid Subdomains:", result)

    return jsonify({"subdomains": result})

if __name__ == "__main__":
    socketio.run(app, host="localhost", port=5001)
