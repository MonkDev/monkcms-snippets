# Monk Command Line Interface
created by Skyler Katz - skyler@monkdevelopment.com

### Version 1.1
Monk CLI is used to manipulate a sites Cloud Files Account  

##### checkSize
This command will check the expected file size of an export from a specified rackspace cloudfiles container.
```
$ php monk checkSize cloudfilescontainername
```

##### exportContainer
This command will export all content located in the specified rackspace cloudfiles container and put everything in a .zip

```
$ php monk exportContainer cloudfilescontainername
```
Future goals are to automatically upload the archive to a web server that the user can download from.
Currently it will loop through all of the files in their container and only download the original of whatever they uploaded in Ekklesia.
You will want to make sure you have enough hard drive space to download all of the files, and then create a .zip of said files.  

After the archive is created, the original files are deleted from your system.

##### Setting up the CLI
Once you download the repo, you will need to run `composer update` to install all of the dependencies for the CLI.

Sensitive credentials are stored in a .env file in the root of the project folder that will need to be created the first time you clone the project.  This file is to be kept out of version control.
check the .env.example file for required items.
