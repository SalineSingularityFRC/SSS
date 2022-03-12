from http import cookies
import os
import cgi
import cgitb
import sqlite3

# save reports to log directory
cgitb.enable(display=1, logdir="/var/log/lighttpd/sss.5066.team/")

form = cgi.FieldStorage()
if "key" not in form:
    print("Content-Type: text/plain\n")
    print("Please submit a key.")
    exit(0)

key = form["key"].value.upper()

with open("/usr/local/www/sss/key.txt","r") as f:
    if f.read().strip() != key:
        print("Content-Type: text/plain\n")
        print("Incorrect key!")
        exit(0)


c = cookies.SimpleCookie()
c["key"] = key
print(c.output())

print("Location: /form.php\n")

#cookievalue = cookies.SimpleCookie(os.environ["HTTP_COOKIE"])

#print("Content-Type: text/plain\n")
#print(cookievalue["key"].value)
