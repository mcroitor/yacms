# _yacms_

Yet another CMS, the 4th iteration.

Rewritten, uses microservices. Default database is sqlite.

## Structure:

<pre>yacms
+-- <b>core</b> - default functionality
|   +-- <i>database.class.php</i> - defines object for DB access
|   +-- <i>logger.class.php</i> - common logger class
|   +-- <i>page.class.php</i> - page rendering
|   +-- <i>template.class.php</i> - simple template filling
|   +-- <i>user.class.php</i> - user and role management
+-- <b>help</b> - documentation
+-- <b>install</b> - installation scripts
|   +-- <i>config.sample.php</i> - configuration file sample
|   +-- <i>index.php</i> - installation script
|   +-- <i>sqlite.sql</i> - initial structure of tables
|   +-- <i>stages.php</i> - installation staging
+-- <b>libs</b> - external libraries
|   +-- <i>api.js</i> - simple ajax requests
+-- <b>modules</b> - extentions
+-- <b>themes</b> - themes
|   +-- <b>default</b> - default theme
+-- <i>index.php</i> - entry point
+-- <i>config.php</i> - configuration file
</pre>