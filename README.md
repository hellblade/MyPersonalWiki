# My Personal Wiki

This is a quickly thrown together site to be used as a person wiki. It is designed for a single user, although multiple users could easily be supported, and should be hosted either on a local network or on a private server with no sensitive information.
It likely doesn't follow all the best security practices, so should not be used outside the intended purpose. 

Uses an SQLite database, this gives you a single file which stores all the pages, making backups and restores easy.

Pages are created/edited in markdown and generate to HTML to be displayed.

Uses [Parsedown](https://github.com/erusev/parsedown).  License included in the LICENSE file.

## Requirements
Made for PHP 5.5+ (7 may not work).
Designed with Apache in mind, although should work with other web servers easily enough.
Designed to work with SQLite (should be preinstalled with PHP).

Supports HTTPS and HTTP (not recommended).

## Installation
Download the source, add the files in the 'src' folder somewhere acessable by your webserver.
The 'include', 'views' and 'controllers' folders can be located elsewhere on the server (so they cannot be accessed via the web server. e.g. place in /srv/wiki/) - in 'index.php', at the top put the folder they are stored in for $includeDir, if they are located elsewhere.
Do this in the install.php file as well. [This could be done better in the future].

In the 'src/include/settings.php' file, fill out your settings:

* $wikiWordStart and $wikiWordEnd are used for easy linking to other pages. With the default values, text like {{Home}} will link to the home page
* $databaseConnectionString is used for the database connection. Should be for sqlite and a path to a file (recommnded to be outside of the folder the webserver uses, e.g. /srv/wiki/database.db)
* You can set the timezone in the settings file as well (or remove it to use the system default). 

Navigate to the 'install.php' in a browser. Enter a username and password and click install. This will create all the tables and the user account. Remove the 'install.php' file after installation.

In the future this may be better designed, especially to help with upgrading to newer versions (if they are made).

Note - in your apache config you may need the following in your VirtualHost:
  <Directory /var/www/yoursite.com/public>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order allow,deny
        allow from all
    </Directory>

# Things to note

* You can rename pages when editing. This won't update any wiki words however, so some links won't be updated to the new name. Changing the case and such will not affect it.

## Possible future enhancements
* More mobile friendly
* Better/more optimised searching
* Dark theme
* Better history viewing
* Image uploading and easy insertion
* More multi-user support