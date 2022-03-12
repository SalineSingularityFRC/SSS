import os
import cgi
import cgitb
import sqlite3
from http import cookies

# save reports to log directory
cgitb.enable(display=1, logdir="/var/log/lighttpd/sss.5066.team/")

# error print
def eprint(s):
    print("Content-Type: text/plain\n")
    print(f"ERROR: {s}")
    exit(0)

# read the cookies, and make sure that it actually exists
if "HTTP_COOKIE" not in os.environ: eprint("No key submitted!")
c = cookies.SimpleCookie(os.environ["HTTP_COOKIE"])
if "key" not in c: eprint("No key submitted!")

# compare the key to /key.txt
key = c["key"].value
with open("/usr/local/www/sss/key.txt","r") as f:
    if f.read().strip() != key:
        eprint("Incorrect key!")

# extract the GET params and make sure all the values are there
form = cgi.FieldStorage()
if "name" not in form:
    eprint("Please submit a name.")

# get the values from the form
name = form["name"].value.upper()

# create record in database
c = sqlite3.connect("/usr/local/www/sss/a.db")
cur = c.cursor()
cur.execute(f"insert into entries (name,out,d) values ('{name}',0,DATE())")
c.commit()
c.close()

print(f"Location: /form.php?m={name} signed in.\n")
