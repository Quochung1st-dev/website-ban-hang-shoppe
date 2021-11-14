=== Font Awesome 4 Menus ===
Contributors: New Nine
Author URI: https://www.newnine.com
Tags: menus, font awesome, navigation, responsive, nav menu, wp_nav_menu
Requires at least: 3
Tested up to: 4.6.1
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to add Font Awesome 4 icons to your WordPress menus or anywhere on your site. No programming necessary!

== Description ==

Add Font Awesome 4 icons to your WordPress menus and site without touching a single line of code! With this plugin, just add fa-(icon name) as a class/classes to your menu and the plugin will pull that out, put the icon before or after your link text, and wrap your link text in a span so you can show or hide it as you see fit.

In Font Awesome 4 Menus, we have added the ability to add multiple Font Awesome classes for multiple effects (eg, fa-home fa-fw fa-2x will add a fixed-width home icon at two times the normal size).

In addition, we have given you shortcodes to add icons to your posts and pages, as well as shortcodes to take advantage of the new stacked feature of Font Awesome 4.

== Installation ==

Use the WordPress installer; or, download and unzip the files and put the folder `n9m-font-awesome-4` into the `/wp-content/plugins/` directory.

Then, activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Where can I find all of the available icons? =

Head over to [the Font Awesome website](http://fontawesome.io/icons/ "the Font Awesome website") to find a full list of icons available. Don't forget to check out the [examples page](http://fontawesome.io/examples/ "examples page") to see how you can mix and match icons for new effects.

= How to I add an icon to my menu? =

Go to Appearance -> Menus, select which menu item to which you want to add the icon, and add the icon class(es) under 'CSS Classes (optional)'. (eg, to add the home icon to your 'Home' link, enter "fa-home" (without quotes) as a class. To make it spin, add "fa-home fa-spin" as your classes.) Save your menu and voila!

Want to add an icon to a post or page? Use the shortcodes available:

* Add a single home icon: [fa class="fa-home"]
* Add a stacked Twitter icon: [fa-stack][fa class="fa-square-o fa-stack-2x"][fa class="fa-twitter fa-stack-1x"][/fa-stack]

You can also use [any of the Font Awesome icons and options](http://fontawesome.io/icons/ "any of the Font Awesome icons and options") with the i class in your theme:

* Add a home icon: `<i class="fa fa-home"></i>`
* Add a Facebook icon: `<i class="fa fa-facebook"></i>`

= How to I place the icon after my menu text? =

Add "fa-after" (excluding the quotes) as one of your classes and the icon will show up after your text.

= Why don't I see an option to add classes? =

Under Appearance -> Menus, click 'Screen Options' (top right of screen) and make sure that 'CSS Classes' is checked. If not - check it!

= Can I hide the text and just show the icons for my menu? =

Yes. Font Awesome menus adds a space between the icon and the text, and wraps that portion in a span with a class of "fontawesome-text". To hide the text and just show the icon, you can put `.fontawesome-text {display: none;}` in your stylesheet.

We have also added an administrative option under Settings->Font Awesome so you can delete the space before or after the text and really take full control of your style and layout.

= Can I use a CDN? Or, my theme uses Font Awesome. Can I prevent it from loading twice? =

Yes! Go to Settings->Font Awesome and choose how you want Font Awesome 4 loaded. You can use the default (locally from the plugin), you can use the official MaxCDN version, you can set your own url or CDN path, or you can tell the plugin NOT to load the stylesheet at all but still enjoy the Font Awesome Menus.

= Will this bloat or slow down my WordPress? =

No. On your site, Font Awesome 4 Menus will load one of four ways depending on how you set it up:

* loads one minified stylesheet (27kb) to load the fonts, or
* loads the fonts from the official Font Awesome 4 CDN at MaxCDN, or
* loads the fonts from a custom url or CDN you choose, or
* doesn't load the fonts at all if you choose this option.

We use it on mobile-first responsive sites ([and our own site](https://www.newnine.com "WordPress experts Chicago")) all the time without any noticeable performance drag.

= What happens to my menus if I deactivate/uninstall this? =

Your site will be fine. Where you used Font Awesome 4 Menus, those menu items will just have additional classes (fa-whatever) that you can erase or ignore (or style differently).

If you uninstall the plugin, Font Awesome 4 Menus cleans up after itself by removing the site option and user meta that it set in your database. No bloat here and we clean up after ourselves!

== Upgrade Notice ==

= 4.7.0 =
Updates to Font Awesome 4.7 with 41 shiny new icons!

= 4.6.3.3 =
Fixes an error experienced by some users when menu items are not arrays

= 4.6.3.2 =
Fixes an error experienced by some users from `nav_menu_css_class()` complaining about parameters

= 4.6.3.1 =
Code optimizations for the menu and shortcodes. (Don't worry - these are non-breaking changes.) Housekeeping in the README and plugin settings, and a new function to fully clean up and leave no footprint if you delete this plugin. No bloat here!

= 4.6.3.0 =
* Update to Font Awesome 4.6.3

= 4.6.1.0 =
Update to Font Awesome 4.6.1

= 4.5.0.0 =
Update to Font Awesome 4.5

= 4.4.0.0 =
Update to Font Awesome 4.4

= 4.3.0.3 =
Fixes a 404 error due to the missing .woff2 version of the font

= 4.3.0.2 =
Fixes a 404 error due to the missing .woff2 version of the font

= 4.3.0.1 =
Update to Font Awesome 4.3 which includes another 40 icons

= 4.2.0.1 =
Update to Font Awesome 4.2 which includes another 40 icons

= 4.1.0.1 =
This upgrade allows you to load Font Awesome from a CDN or prevent double-loading of the stylesheet. It also adds the ability to remove the space in your menus for finer style control.

== Changelog ==

= 4.7.0 =
Updates to Font Awesome 4.7 with 41 shiny new icons!

= 4.6.3.3 =
* Adds fix to errors generated when menu items are not arrays

= 4.6.3.2 =
* Adds fix to errors generated by `nav_menu_css_class()` by removing unused parameters

= 4.6.3.1 =
* Updates and optimizes the menu code and shortcodes to help speed up your site (because we love you)
* Adds new uninstall hook to ensure no footprint left behind if you uninstall :( Font Awesome 4 Menus
* Housekeeping on old links in the readme and settings to reflect Font Awesome's new website URL

= 4.6.3.0 =
* Update to Font Awesome 4.6.3

= 4.6.1.0 =
* Update to Font Awesome 4.6.1

= 4.5.0.0 =
* Update to Font Awesome 4.5

= 4.4.0.0 =
* Update to Font Awesome 4.4

= 4.3.0.3 =
* Bug fix

= 4.3.0.2 =
* Bug fix

= 4.2.0.1 =
* Added 40 new icons
* Updated MaxCDN to 4.2.0

= 4.1.0.1 =
* Added MaxCDN as an optional stylesheet source
* Added the ability to set your own Font Awesome stylesheet url
* Added the ability to prevent Font Awesome 4 Menus from loading the stylesheet if your theme or another plugin already does
* Added an administration screen
* Added an uninstall function to clean up after itself upon uninstalling the plugin
* Updated the readme to reflect the changes in this update