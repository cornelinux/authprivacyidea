Description
===========
DokuWiki Auth Plugin to work with LinOTP.

Using this plugin you can authenticate the dokuwiki users gainst LinOTP.
The users themselves and their access rights are still managed within dokuwiki.
At the moment you need to create a useridresolver in LinOTP, that holds the same users
like the users in dokuwiki.

Then activate the plugi.
Configure necessary plugin settings like:

 * URL of your LinOTP server
 * The users realm
 * whether or not the SSL certificate should be checked...

...and select the plugin as active plugin.

Now you can login with the tokens enrolled in LinOTP for the users.
