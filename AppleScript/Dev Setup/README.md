# Local Development Setup w/ Applescript

Set up an Ekklesia 360 site locally with one click. For root users only.

### Installation: 

1. Create a folder in ~/Sites/ called "root".

2. Fire up MAMP (Standard Version) and point the Webserver Document Root to "~/Sites/root".

3. Close MAMP and delete the "root" folder.

4. In System Preferences > Security & Privacy, add `Dev Setup.app` to the Accessibility list.

5. Add the JavaScript inside `dev-setup.min.js` as a bookmarklet in your browser.

6. While inside Ekklesia 360 as a root user, click on your bookmarklet.

7. The Applescript will fire up Transmit and begin synchronizing the site to a local folder in ~/Sites/ named after the site's domain. According to your Transmit Rules, any files or folders can be ignored. 

### Recommended Transmit Rule

Skip files:

`All`

`Kind` is: `Folder`

`Name` matches: `^_?(.*?)(old|backup|archive|mediafiles|monkcache|monkimage|setupguide)$`
