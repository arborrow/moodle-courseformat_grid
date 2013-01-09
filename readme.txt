GRID COURSE FORMAT
============================
Package tested in: Moodle 2.4 2012120300.00 (Build: 20121203)


ALPHA DEVELOPMENT VERSION - NOT FOR PRODUCTION SITES - Please place comments / feedback on:
http://tracker.moodle.org/browse/CONTRIB-3769 - preferred.
or
http://moodle.org/mod/forum/discuss.php?d=207059

QUICK INSTALL
==============
Download zip package, extract the grid folder and upload this folder into course/format/.

NOTE: If you have previously installed a development version and get the latest code you MUST:
Remove the row with the 'plugin' of 'format_grid' in the 'config_plugins' table and drop the 'format_grid_icon'
and 'format_grid_summary' tables in the database before clicking on 'notifications'.

If upgrade fails, please perform the actions as detailed in the note above.

ABOUT
=============
Developed by:
Information in: 

FILES
--------------

* grid/format.php

  Code that actually displays the course view page.

* grid/config.php

  Configuration file, mainly controlling default blocks for the format.

* grid/lang/en/format_grid.php
* grid/lang/ru/format_grid.php
* grid/lang/es/format_grid.php
* grid/lang/fr/format_grid.php

  Language file containing language strings for grid format.

  Note that existing formats store their language strings in the main
  moodle.php, which you can also do, but this separate file is recommended
  for contributed formats.

  Of course you can have other folders as well as just English and Russian
  if you want to provide multiple languages.

* grid/db/install.xml

  Database table definitions.

* grid/db/upgrade.php

  Database upgrade script.

* grid/version.php

  Required for using database tables. The file provides information 
  about plugin version (update when tables change) and required Moodle version.

* grid/styles.css

  The file include in the CSS Moodle generates.

* grid/backup/moodle2/backup_format_grid_plugin.class.php
  grid/backup/moodle2/restore_format_grid_plugin.class.php

  Backup and restore run automatically when backing up the course.
  You can't back up the course format data independently.

ROADMAP
=============
1. Improved instructions.
2. User definable grid row icon numbers - https://moodle.org/mod/forum/discuss.php?d=196716

HISTORY
=============

18th December 2012 - Version 2.4.0.1 - Alpha version, not for production servers.
1. First alpha release for Moodle 2.4

18th December 2012 - Version 2.4.0.2 - Alpha version, not for production servers.
1. Second alpha release for Moodle 2.4

21st December 2012 - Version 2.4.0.3 - Beta version, not for production servers.
1. Hopefully eliminated BOM issue (http://docs.moodle.org/24/en/UTF-8_and_BOM) that was causing the failure of the images to display.

3rd January 2013 - Version 2.4.0.4 - Beta version, not for production servers.
1. Fixed issue where the grid did not function in 'One section per page mode' on the course settings.

9th January 2013 - Version 2.4.0.5 - Beta version, not for production servers.
1. Fixed issue in editimage.php where the GD library needs to be used for image conversion for transparent PNG's.
2. Perform a 'Purge all caches' under 'Home -> Site administration -> Development -> Purge all caches' after this is installed.