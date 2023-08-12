#initialize colorama for windows
from colorama import init
init()

# the domain to scan for subdomains
domain = "google.com"
#qetu duhet me editu me marr web-in prej frontit

filename="subdomains.txt"
#qetu duhet me editu me marr file prej frontit


with open(filename, "r") as file:
    for subdomain in file.readlines():
        # define subdomain url
        subdomain_url = f"https://{subdomain.strip()}.{domain}"
        try:
            response = requests.get(subdomain_url)
            
            #200 success code, edhe ktu duhet me ndrru
            if response.status_code==200:
                print(Fore.GREEN +f"Subdomain Found [+]: {subdomain_url}")
        except:
            pass  
            #ktu duhet me shtu outpitin me na show ne front

            #https://github.com/topics/subdomain-scanner?l=python
            link per scannera te subdomainve ne github
            #mos harro me filtru per bruteforce

            #https://www.techgeekbuzz.com/blog/how-to-make-a-subdomain-scanner-in-python/
            #Linku me larte eshte kopju sourcecode simple