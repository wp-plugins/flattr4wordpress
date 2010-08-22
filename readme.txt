=== Flattr4WordPress ===
Contributors: Axiom7 Systems
Donate link: http://axiom7.com/
Tags: pie, sharing
Requires at least: 2.5.0
Tested up to: 3.0.1
Stable tag: 1.2

This plugin implements a Flattr shortcode. It comes with its own
options page under the Settings menu and has been translated into
French.


== Description ==

(See also: http://axiom7.com/?p=33)

This plugin adds a "flattr" shortcode to your WordPress installation.

You can use it like this:

[flattr]
[flattr tag="video games, video, Halo"]
[flattr cat="video"]

Or with all parameters specified:

[flattr btn="compact|large" tle="<your title>" dsc="<your description"
cat=<the category of your thing> lng="<the language of your thing>
tag="<your tags>" url="<the URL of your thing>" hdn="0|1"]

To learn what each attribute entails, please refer to the following
list:

btn - (Optional) This is the type of button you would like to display.
To get the compact button use 'compact' otherwise the default (large)
button is displayed. (When using the compact button its best to place
this parameter first as the button then will be compact even if the
later parameters messes up something.)

tle - This is the title of the thing you want to submit. This is
typically the title of your blog entry or software name.

dsc - This is the full excerpt of the content. Some blog text or
information about your song you've written or so forth.

cat - This is the flattr category the content belongs to. You can
choose between the following: text, images, video, audio, software,
rest.

lng - Language of the submitted thing.

tag - (Optional) This is the tags of the thing, to help people finding
your content easier on the Flattr website. If you want to use multiple
tags, separate using a normal comma (,) sign.

url - (Optional) This is the URL of the thing, if this is not always
the same on your site. Maybe you have multiple domains with the same
content. This is to lock the content to always be recognized as the
same content for Flattr.

hdn - (Optional) Use this to hide the thing from listings on
flattr.com. The value 1 will hide the thing.


== Installation ==

1. Upload the Flattr4WordPress archive to the "/wp-content/plugins/"
directory.
1. Unzip the Flattr4WordPress archive.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Visit the Flattr4WordPress Settings page (under the Settings menu)
and enter your Fattr User ID.


== Frequently Asked Questions ==

= Why did you make it? =

I am learning about WordPress plugins and how to develop them.
Flattr is an excellent idea and so...this plugin was born.

= What's coming next? =

A future version could include Twitter-integration, to notify your
followers when you Flattr a thing.
Being able to receive events from the Flattr frame would make that
possible, but using the upcoming REST API might work better.


== Screenshots ==

None at this time.


== Changelog ==

= 1.2 =

* Updated to use the brand new 0.5.0 Flattr API

= 1.1 =

* Some minor clean-up

= 1.0 =

* First release


== Upgrade Notice ==

Simply overwrite old files with new files.

