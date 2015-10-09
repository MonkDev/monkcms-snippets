# Multi-threaded downloading with Workerpool
 
https://code.google.com/p/workerpool/

## Instructions for MacOS:

### Step 1

Place the folder `mass-downloader` on your Desktop.

### Step 2

Open the Terminal (Applications > Utilities > Terminal).

In Terminal, type this command (without the dollar sign) and press Enter. 

```
$ cd ~/Desktop/mass-downloader
```

This command points your Terminal to the right folder.

### Step 3

Then, run this command to begin the download:

```
$ python download.py
```

All the files listed in `urls.txt` will be queued in the Terminal and will begin to download. When they are finished, they will be present in the included folder called `files`.

When the script has fully completed, the command will be complete in Terminal and another new command line ending in `$` will appear. You're done!

### Please Note

Depending on the amount and size of the files, downloading may take several hours to complete.

