# NYUHSL Data Catalog

Welcome to the NYU Health Sciences Library's Data Catalog project. Our aim is to encourage the sharing and reuse of research data among insitutions and individuals by providing a simple yet powerful search platform to expose existing datasets to the researchers who can use it. There is a basic backend interface for administrators to manage the metadata which describes these datasets.

## Components
The Data Catalog runs on **Symfony2**, a popular PHP application framework. Installation and management of this package is best performed by a PHP developer familiar with this framework.

The search functionality is powered by **Solr**, which will need to be running and accessible by the server hosting the website. A sample Solr schema is included with this package. The Solr index can be updated regularly by setting up a cron job which calls an update script. A sample update script is also included with this package.

The metadata and some information about users is stored in a database. We used **MySQL** and there's a good chance you will too.

**IMPORTANT NOTE:** This package comes with a very basic form of authentication that should only be used in a local development environment. There are methods in place to use your institution's LDAP server, or you can use Symfony's built-in user management. Please read `app/config/common/security.yml` for more info.

## Installation
This repository is essentially a Symfony2 distribution (i.e. it is not simply a Symfony "bundle"). As such, you should be able to install this site fairly easily, after configuring it for your environment.

1. Install [Composer](https://getcomposer.org/download/), [Apache Solr](https://lucene.apache.org/solr/guide/6_6/getting-started.html#getting-started) (we have tested on Solr v4 and v6), and set up a suitable database software such as MySQL. Create an empty database schema for this application. Ensure that the PHP modules `php-curl` and `php-dom` are installed on your system. In production, data is cached using the APC extension or, for newer versions of PHP, [APCu](https://pecl.php.net/package/APCu).
2. Clone this repository into a directory your web server can serve.
```
git clone https://github.com/nyuhsl/data-catalog.git
```
3. [Create a Solr core for your project](https://lucene.apache.org/solr/guide/6_6/running-solr.html#RunningSolr-CreateaCore). Your core's name will become part of the URL that goes into the parameters.yml file in the next step. For example, if you create a core called "datacatalog" your Solr URL would look something like "http://localhost:8983/solr/datacatalog". 
4. Read `app/config/parameters.yml.example`. Fill in the information about your MySQL server, and the URL where your Solr installation lives (the `solrsearchr_server` parameter). You'll need a version of this in `app/config/dev` and `app/config/prod`. Remember to choose a "secret" according to the documentation [here](http://symfony.com/doc/current/reference/configuration/framework.html#secret). Then read through `app/config/security.yml.example` and copy it to `app/config/common/security.yml`. Please also read the README file in `app/config` which contains more information.
5. On a command line, navigate to your project's root directory and run `composer install` to install Symfony and any dependencies.
6. [Configure your web server](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html) to work with Symfony. NOTE: You will eventually have to require HTTPS connections on the login and administrative pages (at least), so remember to set up an SSL certificate for your server when you move the site to production. There is some sample code in app/config/common/security.yml that will tell Symfony to require HTTPS connections.
7. [Configure the file
   system](https://symfony.com/doc/2.8/setup/file_permissions.html). This
means at the very least that `app/config/cache` and `app/config/logs` is
writeable by Apache and by your account.
8. To set up the database, there are two options. First, there is a "starter database" prepopulated with several public datasets which can be loaded directly into the empty database schema you created in step 1. We recommend this option. Just extract the file `starterDatabase.sql.tar.gz` which is in the root of this repo, and [import the \*.sql file into your schema](https://stackoverflow.com/a/17666279). However, if you'd prefer to start totally from scratch, navigate to the root of your Symfony installation and run `php app/console doctrine:schema:update --force`. If you have configured your database correctly in parameters.yml, this will set up your empty database to match the data model used in this application. If you haven't configured it correctly, this command will let you know.
9. If using Solr v6+, you will need to switch from the "managed-schema" to use our custom schema, which is defined in `SolrV6SchemaExample.xml`. This involves some minor changes to `solrconfig.xml` as described [here](https://cwiki.apache.org/confluence/display/solr/Schema+Factory+Definition+in+SolrConfig#SchemaFactoryDefinitioninSolrConfig-Classicschema.xml) and [here](http://stackoverflow.com/a/31721587). Then place `SolrV6SchemaExample.xml` in the Solr config directory, named `schema.xml`. Perform any customizations you require, or leave as is.
10. At this point, the site should function, but you won't see any search results because there is nothing in the database, and thus nothing to be indexed by Solr. Click on the "Admin" tab, click "Add a New Dataset" in the sidebar menu, and get going!
11. Once you've added some test data, you'll want to index it in Solr. Navigate to your site's base directory and edit the file `SolrIndexerExample.py` (or `SolrIndexerExample.php` if you only run PHP) to specify the URL of your Solr server where indicated. Then, run the script.

### Follow-up Tasks
1. You'll most likely want to regularly re-index Solr to account for datasets you add, delete, or update using the Admin section. In the root directory of this repo, there are PHP and Python examples of a script which can update a Solr index, called `SolrIndexerExample`. You'll probably want to call this script or something similar with a cron job every Sunday or every night or whatever seems appropriate, depending on much updating you do. I recommend weekly, since you can also run this script on-demand from the command line if you want.
2. You'll most likely want to brand the site with your institution's logo or color scheme. Some placeholders have been left in `app/Resources/views/base.html.twig` that should get you started.
3. In production, the site is configured to use the APC cache, which requires the installation of the APCu PHP module.
4. There are currently three metadata fields ("Study Type", "Subject Gender" and "Subject Sex") which check in the database for the options they should display. When you first load the data entry form, these fields will appear blank until some options are added in their database tables. Please feel free to contact NYUHSL for examples of how to do this. Alternately, if you use the starter database, these fields will be pre-populated.
5. You'll most likely want to have some datasets to search. Get to it!!

## Using the API
The Data Catalog provides an API which can create and retrieve entities in JSON format.

### Listing Entities
Existing datasets and related entities can be retrieved by calling the appropriate endpoints. Each type of entity has a URL which matches its class name. You can use the filenames in the `src/AppBundle/Entity` directory as a reference since they also match the class names. For example, the Dataset entity is defined in `Dataset.php`, so a list of datasets in your catalog can be found at `/api/Dataset/all.json`. Subject Keywords are defined in `SubjectKeyword.php`, so a list of all your subject keywords can be found at `/api/SubjectKeyword/all.json`. 
NOTE: The "all.json" is optional here, so `/api/Dataset` or `/api/SubjectKeyword` would work as well.

A *specific* dataset (or other entity) can be retrieved using its "slug" property (which you'd need to know beforehand). So, the URL `/api/Dataset/ama-physician-masterfile` will return the JSON representation of the AMA Physician Masterfile dataset.

In addition, the Dataset endpoint has an optional `output_format` parameter, which allows you to choose from three different output formats depending on your use case (all are returned as JSON):
- `default` - the default output format can be ingested directly by other data catalogs using this codebase
- `solr` - this format is suitable for indexing by Solr, and is used by our SolrIndexer scripts
- `complete` - this format returns a more complete representation of the dataset, including full information about its related entities
So for example, to retrieve the complete represenation of all your datasets, visit the URL `/api/Dataset/all.json?output_format=complete`

### Ingesting Entities
New entities can also be ingested using the API, but there are some extra steps:
1. __Grant API Privileges__ - Each user wishing to upload via the API must be granted this privilege in the user management area (at `/update/User`). Choose your user in the list and then check the "API User" role. When you save your changes, a unique API key will be generated, which will be used to verify the user's identity. The new key will be displayed the next time you view this form. The key is generated using Symfony's [random_bytes() function](https://symfony.com/doc/2.8/components/security/secure_tools.html#generating-a-secure-random-string) which is cryptographically secure. Please do not generate your own keys (except for testing) and PLEASE enforce HTTPS for all POST requests to the API, as this will keep your unique API key encrypted.
2. __Set X-AUTH-TOKEN Header__ - All POST requests to the API must include the user's API key as the X-AUTH-TOKEN header. Requests with missing API keys, or API keys corresponding to users who no longer have "API User" permissions will be rejected. 
3. __Format your JSON__ - The entities you wish to ingest should be formatted in JSON in a way that Symfony can understand. We have provided a file in the base directory of this project called `JSON_sample.json`. This is a sample Dataset entity showing all the fields that are accepted by the API, and the types of values accepted by those fields. Note that many of the related entities fields (e.g. Subject Keywords) must already exist in the database before they can be applied to a new dataset via the API. For example, if you want to apply the keyword "Adolescent Health" to a dataset, you have to add "Adolescent Health" as a keyword before trying to ingest the dataset. There is more information about this in the `APITester.php` script. In this file you will see a sample PHP array which, like the sample JSON, shows the format required by the API (in case you're starting with your data in PHP). It also contains comments which go into a little more detail which fields require what.
4. __Perform the POST Request__ - The `APITester.php` script is a simple example of how to put together a POST request suitable for our API. Fill in the base URL of your data catalog installation (line 6), set the `$data` variable to contain the data you wish to ingest, and set the X-AUTH-TOKEN header to your API key (line 146). Please again note that most related entities can only be applied to new datasets if their values already exist in the database!

Luckily, these other entities can also be ingested via the API. Just like how we got a list of Subject Keywords by going to `/api/SubjectKeyword`, we can add new keywords by performing a POST request to `/api/SubjectKeyword`. 

The API uses Symfony's form system to validate all incoming data, so the field names in your JSON should match the field names specified in each entity's form class. These files are located in `src/AppBundle/Form/Type`. Any fields that are required in the form class (or by database constraints) must be present in your JSON.

For example, if we check `src/AppBundle/Form/Type/SubjectKeywordType.php`, we can see which fields are required and what they should be called. Two fields are defined in this file, named "keyword" and "mesh_code". The MeSH code is set to `'required'=>false`. So, a new Subject Keyword can be added by submitting a POST request to `api/SubjectKeyword` with the body:
```
{
  "keyword": "Test keyword"
}
```
If we want to add the MeSH code as well, the request body would look like:
```
{
  "keyword": "Test keyword",
  "mesh_code": "the mesh code"
}
```



## Licensing
All files in this repository that are NOT components of the main Symfony distribution are Copyright 2016 NYU Health Sciences Library. This application is distributed under the GNU General Public License v3.0. For more information see the `LICENSE` file included in this repository.














