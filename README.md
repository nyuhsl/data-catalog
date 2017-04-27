#NYUHSL Data Catalog

Welcome to the NYU Health Sciences Library's Data Catalog project. Our aim is to encourage the sharing and reuse of research data among insitutions and individuals by providing a simple yet powerful search platform to expose existing datasets to the researchers who can use it. There is a basic backend interface for administrators to manage the metadata which describes these datasets.

##Components
The Data Catalog runs on **Symfony2**, a popular PHP application framework. Installation and management of this package is best performed by a PHP developer familiar with this framework.

The search functionality is powered by **Solr**, which will need to be running and accessible by the server hosting the website. A sample Solr schema is included with this package. The Solr index can be updated regularly by setting up a cron job which calls an update script. A sample update script is also included with this package.

The metadata and some information about users is stored in a database. We used **MySQL** and there's a good chance you will too.

**IMPORTANT NOTE:** This package comes with a very basic form of authentication that should only be used in a local development environment. There are methods in place to use your institution's LDAP server, or you can use Symfony's built-in user management. Please read app/config/common/security.yml for more info.

##Installation
This repository is essentially a Symfony2 distribution (i.e. it is not simply a Symfony "bundle"). As such, you should be able to install this site fairly easily, after configuring it for your environment.

1. Install [Composer](https://getcomposer.org/download/), [Solr version 4](http://archive.apache.org/dist/lucene/solr/4.10.4/), and set up a suitable database software such as MySQL. Create an empty database schema for this application.
2. Clone this repository into a directory your web server can serve.
```
git clone https://github.com/nyuhsl/data-catalog.git
```
3. Run `composer install` to install any dependencies
4. Read `app/config/parameters.yml.example`. Fill in the information about your MySQL server, and the URL where your Solr installation lives. You'll need a version of this in `app/config/dev` and `app/config/prod`. Remember to choose a "secret" according to the documentation [here](http://symfony.com/doc/current/reference/configuration/framework.html#secret). Then read through `app/config/security.yml.example` and copy it to `app/config/common/security.yml`. Please also read the README file in `app/config` which contains more information.
5. [Configure your web server](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html) to work with Symfony. NOTE: You will eventually have to require HTTPS connections on the login and administrative pages (at least), so remember to set up an SSL certificate for your server when you move the site to production. There is some sample code in app/config/common/security.yml that will tell Symfony to require HTTPS connections.
6. [Configure the file
   system](http://symfony.com/doc/current/setup/file_permissions.html). This
means at the very least that `app/config/cache` and `app/config/logs` is
writeable by Apache and by your account.
7. In the root of your Symfony installation, run `php app/console doctrine:schema:update --force`. If you have configured your database correctly, this will set up your database to match the data model used in this application. If you haven't configured it correctly, this will let you know.
8. Copy the example Solr schema ("SolrSchemaSample.xml") from the root site directory to your Solr installation's configuration directory. Copy the default "schema.xml" that came with Solr into "schema.xml.default", and rename this one "schema.xml". Perform any customizations you require, or leave as is.
9. At this point, the site should function, but you won't see any search results because there is nothing in the database, and thus nothing to be indexed by Solr. Click on the "Admin" tab, click "Add a New Dataset" in the sidebar menu, and get going!
10. Once you've added some test data, you'll want to index it in Solr. Navigate to your site's base directory and edit the file "SolrIndexer.py" to specify the URL of your Solr server where indicated. Then, run the script.

###Follow-up Tasks
1. You'll most likely want to regularly re-index Solr to account for datasets you add or edit using the Admin section. There is a script in the root directory called "SolrUpdater.py" which can update a Solr index. You'll probably want to call this script or something similar with a cron job every Sunday or every night or whatever seems appropriate, depending on much updating you do. I recommend weekly, since you can also run this script on-demand from the command line if you want.
2. You'll most likely want to brand the site with your institution's logo or color scheme. Some placeholders have been left in `app/Resources/views/base.html.twig` that should get you started.
3. You'll most likely want to have some datasets to search. Get to it!!

###Licensing
All files in this repository that are NOT components of the main Symfony distribution are Copyright 2016 NYU Health Sciences Library. This application is distributed under the GNU General Public License v3.0. For more information see the `LICENSE` file included in this repository.
