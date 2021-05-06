# download2.py - Download many URLs using multiple threads.
import os
import re
import urllib
import workerpool

# Job for downloading a given URL.
class DownloadJob(workerpool.Job):
	def __init__(self, url):
		self.path = 'files/'
		self.url = url
	def run(self):
		filename = os.path.basename(self.url)
		filename = re.sub('^(([A-Za-z]|[0-9]|_)+_)', '', filename)
		filepath = self.path + filename
		if not os.path.isfile(filepath):
			save_to = filepath
			urllib.urlretrieve(self.url, save_to)

# Count the number of lines in a file
def countLinesInFile(filepath):
    lines = 0
    for line in open(filepath):
        if (line.strip()): lines += 1
    return lines

# Define vars
file = 'urls.txt'
totalFiles = countLinesInFile(file)
pool = workerpool.WorkerPool(size=5)

# Count the total number of files
print 'Total files: ' + str(totalFiles)

# Loop over urls.txt and create a job to download the URL on each line
count = 0
for url in open(file):
	url = url.strip()
	if url:
		count = count + 1
		job = DownloadJob(url)
		pool.put(job)
		filename = os.path.basename(url).strip()
		filename = re.sub('^(([A-Za-z]|[0-9]|_)+_)', '', filename)
		print('Downloading ' + str(count) + ' of ' + str(totalFiles) + ' ... ' + filename)

# Send shutdown jobs to all threads, and wait until all the jobs have been completed
pool.shutdown()
pool.wait()
