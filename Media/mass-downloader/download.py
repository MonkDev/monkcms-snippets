# download2.py - Download many URLs using multiple threads.
import os
import re
import urllib
import workerpool

class DownloadJob(workerpool.Job):
	"Job for downloading a given URL."
	def __init__(self, url):
		self.path = 'files/'
		self.url = url # The url we'll need to download when the job runs
	def run(self):
		filename = os.path.basename(self.url)
		filename = re.sub('^(([A-Za-z]|[0-9]|_)+_)', '', filename)
		filepath = self.path + filename
		if not os.path.isfile(filepath):
			save_to = filepath
			urllib.urlretrieve(self.url, save_to)

# Initialize a pool, 5 threads in this case
pool = workerpool.WorkerPool(size=5)

# Count the total number of files
total = 0
for url in open("urls.txt"):
	url = url.strip()
	if(url):
		total = total + 1

print 'Total files: ' + str(total)

# Loop over urls.txt and create a job to download the URL on each line
count = 0
for url in open("urls.txt"):
	url = url.strip()
	if url:
		count = count + 1
		job = DownloadJob(url)
		pool.put(job)
		filename = os.path.basename(url).strip()
		filename = re.sub('^(([A-Za-z]|[0-9]|_)+_)', '', filename)
		print('Downloading ' + str(count) + ' of ' + str(total) + ' ... ' + filename)

# Send shutdown jobs to all threads, and wait until all the jobs have been completed
pool.shutdown()
pool.wait()
