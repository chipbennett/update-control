=== Update Control ===
Contributors: georgestephanis, chipbennett
Tags: automatic updates, updates
Requires at least: 3.7
Tested up to: 4.0
Stable tag: 1.5
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

This Plugin is intentionally very simple. If you want a Plugin with more complexity and its own settings page, you might want to check out [Automatic Updater](http://wordpress.org/plugins/automatic-updater).

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

= What are advanced settings? =

Enable updates for VCS installations

* By default, WordPress will check for the existence of VCS (version control system) files, and if any are found, will not perform automatic updates. Selecting "Enable updates for VCS installations?" will force WordPress to bypass this check, and perform updates regardless of the existence of VCS files.

Update Result Emails

* By default, WordPress sends an update result email for successful, failed, and critically failed updates
* Selectively disable emails for each result type via the appropriate checkboxes

Debug Email

* This email is sent by default when an update is performed on a site that is running a development version of WordPress. Enable this option to override the debug email, and prevent it from being sent.

= Why don't automatic updates happen right away? =

You may find that you receive an update notification, but automatic updates don't happen right away. That's okay! WordPress performs automatic updates according to timing based on certain transient values, and it is possible for the update check to happen some time before the automatic update routine executes. The Plugin doesn't modify the timing of the automatic update routine; rather, it just tells WordPress which update types are enabled.

Also, core now has two separate types of updates: manual updates and automatic updates. When you see the manual upgrade notice, WordPress has received the manual update offer, but has not yet received the automatic update offer. The two offers are fetched based on two different transients, and do not happen at the same time. Automatic core updates are served on a staggered rollout, which means that it may take up to 36 hours for the automatic update to execute after receiving the manual upgrade notice. For Plugins and Themes, you should normally see the automatic update routine execute within 12 hours of receiving the manual upgrade notice.

== Changelog ==

= 1.5 =
* Bugfix. Fixes filtering of debug email sent when using a development version of WordPress.

= 1.4 =
* Bugfix. Fixes disabling all automatic updates not working.

= 1.3.2 =
* Bugfix. Correct handling of VCS check filter.

= 1.3.1 =
* Bugfix. Correct filter name.

= 1.3 =
* Maintenance Update. Add multisite awareness, more FAQs.

= 1.2.1 =
* Make Advanced Settings UI a bit more intuitive.

= 1.2 =
* Feature Update. Add advanced options toggle, and options for VCS check disabling and debug email.

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
