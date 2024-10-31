=== Photo roll ===
Contributors: joe9sig
Tags: instagram, insta, gallery, feed, importer, widget
Requires at least: 4.0
Tested up to: 5.3.2
Requires PHP: 5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin gives chance to share your instagram photos on your website. Just fill in your profile name and enjoy your pics. No token required.

== Description ==
I\'ve built this plugin because i wanted to share photos from my instagram profile with vistors of my website. I just couldn\'t find good enough plugin that people could use with absolutley no technical knowlege.
So this a result of solvnig my own problem. Hope that this pease of code give you opportunities of improving your visitors feeling

All you need to know is your profile name. Save it on plugin settings page and then plugin will simply retrieve from remote service your last instagram photos.
Those photos will be saved in your media library as attachments.
You can do with them whatever your want, but this plugin gives you [ig_instantgram_gallery] shortcode, which you can place in your editor as well

Below you can find plugin requirements:

* php version >= 5.3
* php-curl module


== Installation ==
1. Upload \"instantgram\" to the \"/wp-content/plugins/\" directory.
1. Activate the plugin through the \"Plugins\" menu in WordPress.
1. Place [ig_instantgram_gallery] shortcode in your wyswig editor.

== Frequently Asked Questions ==
= My photos are being retrived right after publication on instagram =
Yes, this is correct behavior. This plugin uses wp cron mechanisem and your photos area being downloaded with couple of hours delay

= How can i show my instagram photos on page =
Just place [ig_instantgram_gallery] shortcode in your editor and save changes. Plugin will automaticaly replace this shortcode with html gallery.

== Changelog ==
= 1.0.0 =
* Initial release.