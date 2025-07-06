## About This Project
Mario Fan Games Galaxy (MFGG) is a Web site for sharing and discussing fangames and indie games, primarily Mario fangames. MFGG has been in continuous operation since July 2001, an impressive run for a fansite. MFGG has been featured on TV and in magazine articles, and it's also been a part of countless YouTube videos and Twitch streams. Several of our members have gone on to become prominent professional video game developers or YouTube creators.

Taloncrossing Site Management System (TCSMS) is the software that MFGG's mainsite runs on. It is optimized for sharing and discussing Mario fangames and resources used in their creation. However, TCSMS could theoretically be revamped as a content management system for other kinds of projects. Some of this project's code dates back to 2005-2006, so be forewarned that some code does not follow modern patterns or best practices. Some code may be disorganized or poorly-optimized.

TCSMS is now designed to run in PHP 8.x, although this is older code retrofitted to run in PHP 8.x and does not take advantage of PHP 8's newer features.

### Installation Steps

1. Set up LAMP stack (PHP 8.2 is recommended)

2. Deploy files to your localhost

3. Run DB Creation Script.sql to install required tables

4. Update database name and password in the following files:

	* settings.php
	* botapi.php
	* component/admin/adm_webhook.php
	* component/admin/adm_lastcommentsframe.php

5. Change any hardcoded instances of "https://www.mfgg.net/" to your localhost

Once the site is running, you will probably want to register a user account or two for testing purposes. You may need to go into the database to promote it to admin.

There will be very little content at first. You may want to create a couple of test submissions in different categories. Then go into the Admin Control Panel, accept the submissions (override the queue if needed), and create a news post.

### Authors

- Retriever II - Original author of TCSMS
- VinnyVideo - Updated TCSMS to run in PHP 8.2
- Sean - Made mainsite themes more mobile-friendly and massively improved database performance
- Hypernova - Added a lot of upgrades in the early 2020's
- Mors - The current theme, and md5 fixes and other upgrades in the early 2020's
- Kritter - The original theme
- HylianDev, Char, Guinea, Techokami, probably other people I'm forgetting - Other contributions
- wtl420 - Creator of the repo