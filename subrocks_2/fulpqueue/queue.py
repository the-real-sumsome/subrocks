print("""
FulpTube Queue v1.13, written by zulc22, 2020-2021
FulpTube is owned and operated by beef boy bazinga.

CHANGELOG (since v1.12):
Made thumbnails 480p, instead of using the raw video resolution
""")

print("Loading libraries...")

import json, mysql.connector, socketserver, random, base64, time, math, threading, sys, subprocess, os
from mysql.connector import Error
from ffmpeg_progress import ffprobe
from uuid import uuid1 as genUUID

print("Loading config.json...")

with open("config.json") as configj:
    config = json.load(configj)

if config["sql"] == "from_env":
    # read SQL vars from environment
    config["sql"] = {
        "host": os.environ.get('MYSQL_HOST'),
        "username": os.environ.get('MYSQL_USER'),
        "password": os.environ.get('MYSQL_PASSWORD'),
        "database": os.environ.get('MYSQL_DATABASE')
    }

print("Defining functions and classes...")

def connectSQL():
    try:
        return mysql.connector.connect(
            host=config["sql"]["host"],
            user=config["sql"]["username"],
            passwd=config["sql"]["password"],
            database=config["sql"]["database"]
        )
    except mysql.connector.errors.InterfaceError:
        print("connectSQL : Couldn't connect to SQL server! Retrying in 1 second")
        time.sleep(1)
        return connectSQL()
    except mysql.connector.errors.ProgrammingError:
        print("connectSQL : Couldn't connect to SQL server! Retrying in 1 second")
        time.sleep(1)
        return connectSQL()

def splitwithEscape(s:str, delimiter:str, escaper:str="\\"):
    out = [""]
    oi = 0
    escaped = False
    for si in s:
        if si == escaper and not escaped:
            escaped = True
            continue
        if si == delimiter and not escaped:
            oi += 1
            out.append("")
            continue
        else:
            out[oi] += si
            escaped = False
            continue
    return out

def genRID():
    characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_'

    result = ''
    lowerresult= ''

    while(not result and lowerresult.find('fool') == -1 and lowerresult.find('idiot') == -1):
        for i in range(0, 11):
            result += random.choice(characters)
            lowerresult= result.lower()

        if(result and lowerresult.find('fool') == -1 and lowerresult.find('idiot') == -1):
            return(result).replace("\n", "")

def startServer():
    print()
    print(f"Listening on {config['server']['host']}:{config['server']['port']}.")
    print()

    with socketserver.TCPServer(
        (config['server']['host'], config['server']['port']),
        MyTCPHandler
    ) as server:
        server.serve_forever()

def process(rid: str):
    UNIXQUOTE = "" if sys.platform == 'win32' else "\""
    # args should be ["Title", "Description", "tag1; tag2; tag3", "C:\path\to\tempfile.mp4", "Author"]
    args = queue2[rid]
    print(rid,": Now processing.")
    processcmd = f"ffmpeg -hide_banner -loglevel error -i \"{args[3]}\" -vcodec h264 -acodec aac -pix_fmt yuv420p -threads 4 -preset medium -vf {UNIXQUOTE}scale=-1:720,pad=ceil(iw/2)*2:ceil(ih/2)*2{UNIXQUOTE} -b:v 2500k \"{config['queue']['videos']}/{rid}.mp4\""
    if os.system(processcmd) != 0:
        print(rid,": FFMPEG FAILED TO CONVERT! Look above for error messages.")
        print("Cancelling processing of video",rid,"\nFiles may be left over")
        del queue2[rid]
        return
    del queue2[rid]
    print(rid,": Done converting. Deleting source file.")
    os.remove(args[3])
    print(rid,": Creating thumbnail")
    processcmd = f"ffmpeg -hide_banner -loglevel panic -i \"{config['queue']['videos']}/{rid}.mp4\" -vf {UNIXQUOTE}select=eq(n\\,34),scale=-1:360{UNIXQUOTE} -vframes 1 \"{config['queue']['thumbs']}/{rid}.png\""
    if os.system(processcmd) != 0:
        print(rid,": FFMPEG FAILED TO CREATE THUMBNAIL! Look above for error messages.")
        print("Cancelling processing of video",rid,"\nFiles may be left over")
        del queue2[rid]
        return
    print(rid,": Getting length of video")
    try:
        length = str( int( float( ffprobe(f"{config['queue']['videos']}/{rid}.mp4")['streams'][0]['duration'] )))
    except subprocess.CalledProcessError:
        print(rid,": FFPROBE FAILED! FFMPEG must have corrupted the file...")
        print("Cancelling processing of video",rid,"\nFiles may be left over")
        return
    print(rid,": Commiting to SQL")
    database = connectSQL()
    cursor = database.cursor(prepared=True)
    cursor.execute(
        "INSERT INTO videos (title, author, filename, thumbnail, description, tags, rid, duration, xml, category) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        ( 
            args[0], #title
            args[4], #author
            f"{rid}.mp4", #filename
            f"{rid}.png", #thumbnail
            args[1], #description
            args[2], #tags
            rid, #rid
            length, #duration
	        args[5],
            args[6]
        )
    )
    database.commit()
    cursor.close()
    database.close()
    print(rid,": Completed processing sucessfully\n")

queue = {}
queue2 = {}

def startQueue():
    while True:
        for f in list(queue):
            print("Starting new thread for processing of",f,"...\n")
            queue2[f] = queue[f]
            del queue[f]
            threading.Thread(target=lambda: process(f)).start()
        time.sleep(0.5)

class MyTCPHandler(socketserver.BaseRequestHandler):

    DELIMITER = ";"
    DELIMNAME = "semicolon"

    def handle(self):
        # self.request is the TCP socket connected to the client
        self.data = self.request.recv(1024).strip()
        print("Request recieved --", self.data.decode())
        try:
            c = self.data.decode().split(self.DELIMITER)
            self.validCommands[c[0]](self, splitwithEscape( self.DELIMITER.join(c[1:]), self.DELIMITER ) )
        except KeyError:
            print("(the request was invalid.)\n")
            self.request.sendall(b"invalid command \""+self.data+b"\".")
        except IndexError:
            print(f"(couldn't split apart, likely because the {self.DELIMNAME} was missing.)\n")
            self.request.sendall(b"no "+self.DELIMNAME.encode()+b" in \""+self.data+b"\".")

    def getQueue(self, args):
        print("Queue requested\n")
        qout = {}
        qout.update(queue)
        qout.update(queue2)
        self.request.sendall(json.dumps(qout).encode())

    def pushQueue(self, args):
        # args should be ["Title", "Description", "tag1; tag2; tag3", "C:\path\to\tempfile.mp4", "Author"]
        print("Video requested to add to queue --",args[0])
        if not os.path.exists(args[3]):
            print("The file",args[3],"didn't exist...")
            self.request.sendall(b"file \""+args[3].encode()+b"\" doesn't exist.")
            return
        print("Generating RID for",args[0])
        vidUUID = genRID()
        print("RID for",args[0],"is",vidUUID)
        print("Queueing",vidUUID)
        queue[vidUUID] = args
        print("Queued.\n")
        self.request.sendall(b"{\"rid\":\""+str(vidUUID).encode()+b"\"}")

    validCommands = {
        "getQueue": getQueue,
        "pushQueue": pushQueue 
    }

print("Checking if database connection works...")
database = connectSQL()
print("Success!")
database.close()

TCP_thread = threading.Thread(target=startServer, daemon=True)
FFMPEG_thread = threading.Thread(target=startQueue, daemon=True)

TCP_thread.start()
FFMPEG_thread.start()

try:
    # Wait forever, so we can receive KeyboardInterrupt.
    while True:
        time.sleep(1)
except KeyboardInterrupt:
    print("CTRL+C recieved, quitting...")
    sys.exit(0)

