Description
===========
DokuWiki Auth Plugin to work with privacyIDEA.

Using this plugin you can authenticate the dokuwiki users gainst privacyIDEA.
The users themselves and their access rights are still managed within dokuwiki.
At the moment you need to create a useridresolver in privacyIDEA, that holds the same users
like the users in dokuwiki.

Then activate the plugin.
Configure necessary plugin settings like:

 * URL of your privacyIDEA server
 * The users realm
 * whether or not the SSL certificate should be checked...

...and select the plugin as active plugin.

Now you can login with the tokens enrolled in privacyIDEA for the users.

Troubleshooting
===============

You can revert to the originial auth plugin by editing the file conf/local.php:

   $conf['authtype'] = 'authplain';


Debug
=====

The auth plugin write some messages to the debug log.
You can activate debugging in the dokuwiki settings.
