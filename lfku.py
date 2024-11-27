import requests
import argparse

def scan_website(base_url):
    endpoints = ["/filemanager", "/laravel-filemanager"]
    results = {"url": base_url, "filemanager_found": False, "image_found": False, "login_required": False}

    # Cek endpoint filemanager dengan timeout cepat
    for endpoint in endpoints:
        url = f"{base_url}{endpoint}"
        try:
            response = requests.get(url, allow_redirects=False, timeout=5)
            if response.status_code == 200 and "laravel-filemanager" in response.text.lower():
                results["filemanager_found"] = True
            elif response.status_code == 302 and "/login" in response.headers.get("Location", ""):
                results["login_required"] = True
        except requests.exceptions.RequestException:
            results["error"] = "Timeout/Connection error"

    # Cek gambar folder.png dengan timeout cepat
    img_url = f"{base_url}/vendor/laravel-filemanager/img/folder.png"
    try:
        response_img = requests.get(img_url, allow_redirects=False, timeout=1)
        if response_img.status_code == 200:
            results["image_found"] = True
    except requests.exceptions.RequestException:
        results["error"] = "Timeout/Connection error"

    return results

def mass_scan(input_file):
    with open(input_file, "r") as f:
        urls = [line.strip() for line in f.readlines()]

    results_list = []
    for url in urls:
        print(f"Scanning: {url}")  # Print URL yang sedang di-scan
        result = scan_website(url)

        # Hanya simpan hasil yang True, tetapi tetap pastikan URL tercatat
        true_results = {key: value for key, value in result.items() if value is True}
        if true_results:  # Jika ada hasil yang True
            # Menyimpan URL dan hasil True saja
            true_results["url"] = url  # Pastikan URL tercatat
            results_list.append(true_results)
        print(f"Result: {true_results}")  # Print hasil scan situs yang ditemukan True saja

    # Simpan hasil ke file output "vuln.txt"
    with open("vuln.txt", "w") as f:
        for result in results_list:
            f.write(f"URL: {result['url']} - {result}\n")  # Simpan URL dan hasil True

    print(f"Hasil scan disimpan di vuln.txt")

def main():
    # Parsing command line arguments
    parser = argparse.ArgumentParser(description="Mass scan websites for Laravel Filemanager")
    parser.add_argument('input_file', help="Input file containing list of URLs to scan")
    args = parser.parse_args()

    # Jalankan pemindaian massal dengan file input yang diberikan dan simpan hasilnya ke "vuln.txt"
    mass_scan(args.input_file)

if __name__ == "__main__":
    main()
