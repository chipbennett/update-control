=== Update Control ===
Contributors: georgestephanis, chipbennett
Tags: automatic updates, updates
Requires at least: 3.7
Tested up to: 3.7
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This adds some options to your Settings > General page that let you tweak auto-updates.

== Description ==

This plugin adds some options to your Settings > General page, letting you specify how auto-upgrades should function, without the need to specify constants or add filters by hand.

== Installation ==

Installation using "Add New Plugin"

1. From your Admin UI (Dashboard), use the menu to select Plugins -> Add New
2. Search for 'Update Control'
3. Click the 'Install' button to open the plugin's repository listing
4. Click the 'Install' button

Activiation and Use

1. Activate the plugin through the 'Plugins' menu in WordPress
2. From your Admin UI (Dashboard), use the menu to select Settings -> General
3. Configure settings, and save

== Frequently Asked Questions ==

= Why doesn't this Plugin have its own settings page? =

Plugin settings can be found under Settings -> General.

Not having a separate settings page is a decision based on UI philosophy. The Plugin simply isn't complex enough to warrant a separate settings page. Perhaps a different admin page, such as Dashboard -> Updates, would be more appropriate; however, core does not provide a way to hook into that page to add settings sections.

= How do I use this Plugin in a multisite network? =

Activate the Plugin on the main network site, and configure options via Settings -> General.

= What do "Minor", "Major", and "Development" core updates mean? =

* "Minor": minor versions will be updated automatically. Minor versions in WordPress are "X.Y.Z". A minor-version update is a change from "X.Y.Z" to "X.Y.Z+1". This is the default core behavior.
* "Major": major versions will be updated automatically. Major versiosn in WordPress are "X.Y". A major-version update is a change from "X.Y" to "X.Y+1".
* "Development": development versions will be updated automatically. Development versions, also referred to as "bleeding-edge nightlies", are daily updates in the current development branch. While usually quite stable, this option should be used only if you're comfortable with using potentially unstable, development software. This option should only be used on production sites if your name is "Otto".

= Which setting should I use? =

Core Updates:

* If you want minor versions to update automatically (default WordPress core behavior): leave "Automatic Core Update Level?" set to "Minor".
* If you want both minor and major versions to update automatically: set "Automatic Core Update Level?" to "Major".
* If you want bleeding-edge nightlies: set "Automatic Core Update Level?" to "Development"
* If you want to disable core updates altogether: set "Automatic Updates Enabled?" to "no"

Plugin/Theme/Translation Updates:

* If you disable updates, Plugin, Theme, and Translation updates will also be disabled
* Separately enable Plugin, Theme, and Translation updates via the appropriate checkboxes

= What are advanced options? =

Disable VCS Check

* By default, WordPress will check for the existence of VCS (version control system) files, and if any are found, will not perform automatic updates. Selecting "Disable VCS Check?" will force WordPress to bypass this check, and perform updates regardless of the existence of VCS files.

Update Result Emails

* By default, WordPress sends an update result email for successful, failed, and critically failed updates
* Selectively disable emails for each result type via the appropriate checkboxes

Debug Email

* Enable this option to enable the debug email. This email is sent after ever occurrence of an attempted update, for core, Plugins, Themes, and translation files; and whether the attempt succeeds, fails, or fails critically.

== Changelog ==

= 1.1.3 =
* Bugfix. Declare static functions to eliminate e-strict PHP notice.

= 1.1.2 =
* Bugfix. Use selected() instead of checked() in select form fields.

= 1.1.1 =
* Bugfix. Correct variable name typo

= 1.1 =
* Update for WordPress 3.7 final, add update email options.

= 1.0 =
* Initial Release.
