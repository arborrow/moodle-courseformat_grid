Grid Course Format
============================
A topics based format that uses a grid of user selectable images to popup a light box of the section.

Required version of Moodle
==========================
This version works with Moodle version 2013051400.00 release 2.5 (Build: 20130514) and above until the next release.

Please ensure that your hardware and software complies with 'Requirements' in 'Installing Moodle' on
'docs.moodle.org/25/en/Installing_Moodle'.

Installation
============
1. Ensure you have the version of Moodle as stated above in 'Required version of Moodle'.  This is essential as the
   format relies on underlying core code that is out of my control.
2. Put Moodle in 'Maintenance Mode' (docs.moodle.org/en/admin/setting/maintenancemode) so that there are no
   users using it bar you as the administrator - if you have not already done so.
3. Copy 'grid' to '/course/format/' if you have not already done so.
4. Login as an administrator and follow standard the 'plugin' update notification.  If needed, go to
   'Site administration' -> 'Notifications' if this does not happen.
5. Put Moodle out of Maintenance Mode.

Uninstallation
==============
1. Put Moodle in 'Maintenance Mode' so that there are no users using it bar you as the administrator.
2. It is recommended but not essential to change all of the courses that use the format to another.  If this is
   not done Moodle will pick the last format in your list of formats to use but display in 'Edit settings' of the
   course the first format in the list.  You can then set the desired format.
3. In '/course/format/' remove the folder 'grid'.
4. In the database, remove the row with the 'plugin' of 'format_grid' in the 'config_plugins' table and drop the
   'format_grid_icon' and 'format_grid_summary' tables.
5. Put Moodle out of Maintenance Mode.

Reporting Issues
================
Before reporting an issue, please ensure that you are running the latest version for your release of Moodle.  The primary
release area is located on https://moodle.org/plugins/view.php?plugin=format_grid.  It is also essential that you are
operating the required version of Moodle as stated at the top - this is because the format relies on core functionality that
is out of its control.

All 'Grid format' does is integrate with the course page and control it's layout, therefore what may appear to be an issue
with the format is in fact to do with a theme or core component.  Please be confident that it is an issue with 'Grid format'
but if in doubt, ask.

We operate a policy that we will fix all genuine issues for free.  Improvements are at my discretion.  We am happy to make bespoke
customisations / improvements for a negotiated fee. 

When reporting an issue you can post in the course format's forum on Moodle.org (currently 'moodle.org/mod/forum/view.php?id=47'), 
on Moodle tracker 'tracker.moodle.org' ensuring that you chose the 'Non-core contributed modules' and 'Course Format: Grid'
for the component or contact us direct (details at the bottom).

It is essential that you provide as much information as possible, the critical information being the contents of the format's 
version.php file.  Other version information such as specific Moodle version, theme name and version also helps.  A screen shot
can be really useful in visualising the issue along with any files you consider to be relevant.

Files
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

Roadmap
=============
1. Improved instructions including Moodle docs.
2. User definable grid row icon numbers - https://moodle.org/mod/forum/discuss.php?d=196716
3. CONTRIB-3500 - Gridview course format more accessible.
4. CONTRIB-4099 - Grid format does not allow the user to set the size of the image / box.
5. Use of crowd funding facility to support development.

History
=============

18th December 2012 - Version 2.4.0.1 - Alpha version, not for production servers.
Change by G J Barnard
  1. First alpha release for Moodle 2.4

18th December 2012 - Version 2.4.0.2 - Alpha version, not for production servers.
Change by G J Barnard
  1. Second alpha release for Moodle 2.4

21st December 2012 - Version 2.4.0.3 - Beta version, not for production servers.
Change by G J Barnard
  1. Hopefully eliminated BOM issue (http://docs.moodle.org/24/en/UTF-8_and_BOM) that was causing the failure of the images to display.

3rd January 2013 - Version 2.4.0.4 - Beta version, not for production servers.
Change by G J Barnard
  1. Fixed issue where the grid did not function in 'One section per page mode' on the course settings.

9th January 2013 - Version 2.4.0.5 - Beta version, not for production servers.
Change by G J Barnard
  1. Fixed issue in editimage.php where the GD library needs to be used for image conversion for transparent PNG's.
  2. Perform a 'Purge all caches' under 'Home -> Site administration -> Development -> Purge all caches' after this is installed.

12th January 2013 - Version 2.5.0.1 - Alpha version, not for production servers.
1. Migrated code to Moodle 2.5 development version.

21st January 2013 - Version 2.4.0.2 - Alpha version, not for production servers.
Change by G J Barnard
  1. Changes to 'renderer.php' because of MDL-36095 hence requiring Moodle version 2012120301.02 release 2.4.1+ (Build: 20130118) and above.

24th February 2013 - Version 2.4.1 - Stable version.
Change by G J Barnard
  1. Changes because of MDL-37901.
  2. Invisible section fix for Tim Wilde - https://moodle.org/mod/forum/discuss.php?d=218505#p959249.
  3. This version considered 'Stable' from feedback of Theo Konings on CONTRIB-3534.

13th April 2013 - Version 2.5.0.1 - Beta version.
Change by G J Barnard
  1. First 'Beta' release for Moodle 2.5 Beta.

12th May 2013 - Version 2.5.0.2 - Beta
Change by G J Barnard
  1. Removed '.jumpmenu' from styles.css because of MDL-38907.
  2. Added automatic 'Purge all caches' when upgrading.  If this appears not to work by lack of display etc. then perform a
     manual 'Purge all caches' under 'Home -> Site administration -> Development -> Purge all caches'.
  3. Changes for MDL-39542.

14th May 2013 Version 2.5.1 - Stable
Change by G J Barnard
  1.  First stable version for Moodle 2.5 stable.

30th June 2013 Version 2.5.2 - Stable
Change by G J Barnard
  1.  Code refactoring to reduce and separate the format as a separate entity.

Authors
-------
J Ridden - Moodle profile: https://moodle.org/user/profile.php?id=39680 - Web: http://www.moodleman.net
G J Barnard - Moodle profile: moodle.org/user/profile.php?id=442195 - Web profile: about.me/gjbarnard