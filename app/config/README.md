This is the main configuration directory for Symfony, and you'll need to do some customization here.

First, check out "parameters.yml.example". This file contains database connection strings, Solr and LDAP configuration settings, and opportunities to name and brand your site. You probably don't want to change most of the Solr settings, but be sure to give Symfony the URL of you Solr server.

You'll need to rename this file to "parameters.yml" and put a copy in both the dev/ and prod/ directories -- this way Symfony can connect to your Dev or Prod databases where appropriate.

The next file to look at is "security.yml.example". This tells Symfony how to protect certain areas of your site and how to handle authentication. This example file is meant for use in **DEV ONLY**. It contains a default admin account (with a very insecure password) that you can use right away to start entering datasets, creating real users, etc.

This file need only exist in the common/ directory, as "security.yml".

This example file also does not require HTTPS connections anywhere, but we suggest requiring HTTPS for every route once you get your certificate set up.

There are some commented-out lines that can be used to set up LDAP authentication if you want to use it. Users must be created in this website's Admin section first, which is checked before going to the LDAP server -- i.e., it isn't enough to exist in your institution's LDAP, you must also grant access to specific users in this application.

Symfony also has a robust built-in user management and authentication system if your institution doesn't use LDAP or you just don't want to.
