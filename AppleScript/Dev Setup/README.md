# Local Development Setup w/ Applescript

Set up an Ekklesia 360 site locally with one click. For root users only.

### Installation: 

1. Add `Dev Setup.app` to your `~/Applications/`.

2. Create a folder in `~/Sites/` called `root`.

3. Fire up MAMP (Standard Version) and point the Webserver Document Root to `~/Sites/root`.

4. Close MAMP and delete the `root` folder.

5. In System Preferences > Security & Privacy, add `Dev Setup.app` to the Accessibility list.

6. Add the JavaScript inside `dev-setup.min.js` as a bookmarklet in your browser.

7. While inside Ekklesia 360 as a root user, click on your bookmarklet.

The Applescript will fire up Transmit and begin synchronizing the site to a local folder in ~/Sites/ named after the site's domain. According to your Transmit Rules, any files or folders can be ignored. 

### Recommended Transmit Rule

Skip files:

`All`

`Kind` is: `Folder`

`Name` matches: `^_?(.*?)(old|backup|archive|mediafiles|monkcache|monkimage|setupguide)$`
