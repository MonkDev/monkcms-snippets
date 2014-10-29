# Ben Goshow's AJAX'd email encoding 

If using AJAX to retreive content that contains Javascript code, check out this snippet by Ben Goshow which handles the MonkCMS / Ekklesia 360 email encoding feature over AJAX. It pre-processes the contents of the added document.write statements so that the AJAX returns the email address already inserted into the content. The receiving template then uses Javascript eval() to execute the email encoding so that addresses can remain protected.
