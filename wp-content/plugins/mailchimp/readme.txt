=== MailChimp List Subscribe Form ===
Contributors: mc_jesse
Tags: mailchimp, email, newsletter, signup, marketing, plugin, widget
Requires at least: 2.3
Tested up to: 2.9
Stable tag: 1.1.8
Author URI: http://www.mailchimp.com/api/
Plugin URI: http://www.mailchimp.com/plugins/mailchimp-wordpress-plugin/

The MailChimp plugin allows you to quickly and easily add a signup form for your MailChimp list.

== Description ==

The MailChimp plugin allows you to quickly and easily add a signup form for your MailChimp list as a widget
on your Wordpress 2.3 or higher site.

Not sure what [MailChimp](http://www.mailchimp.com/features/full_list/) is or if it will be helpful? Signup up for a 
[FREE Trial Account](http://www.mailchimp.com/signup/) and see for yourself!

After Installation, the setup page will guide you through entering your Login informaiton, selecting your List from our Service,
selecting options for the Merge Fields and Interest Groups you have setup, and then add the Widget to your site. The 
time from starting installation to have the form on your site should be less than 5 minutes - absolutely everything
can be done via the Wordpress Setting GUI - no file editing at all!

You can also visit our [homepage for the plugin](http://www.mailchimp.com/plugins/mailchimp-wordpress-plugin/), but if you are reading this,
you probably don't need to.

== Installation ==

This section describes how to install the plugin and get started using it.

= Version 2.3 =
1. Unzip our archive and upload the entire `mailchimp` directory to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Options and look for "MailChimp Setup" in the menu at the top
1. Enter your MailChimp Username & Password and let the plugin verify them
1. Select One of your lists to have your visitors subscribe to.
1. (optionally) Turn on or off the Monkey Rewards option
1. (optionally) Turn your Merge Vars and Interest Groups `on` and `off`
1. Finally, go to Presentation->Widgets and enable the `MailChimp` widget
1. And you are DONE!

= Version 2.5+ =
1. Unzip our archive and upload the entire `mailchimp` directory to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Settings and look for "MailChimp Setup" in the menu
1. Enter your MailChimp Username & Password and let the plugin verify them
1. Select One of your lists to have your visitors subscribe to.
1. (optionally) Turn on or off the Monkey Rewards option
1. (optionally) Turn your Merge Vars and Interest Groups `on` and `off`
1. Finally, go to Manage->Widgets and enable the `MailChimp` widget
1. And you are DONE!

= Advanced =
If you have a custom coded sidebar or something else special going on where you can't simply enable the widget
through the Wordpress GUI, all you need to do is:

If you are using Wordpress v2.5 or higher, you can use the short-code:
` [mailchimpsf_widget] `

If you are adding it inside a php code block, pop this in:

` mailchimpSF_display_widget(); `

Or, if you are dropping it in between a bunch of HTML, use this:

`<?php mailchimpSF_display_widget(); ?>`

Where ever you want it to show up. 

Note: in some environments you will need to install the Exec_PHP plugin to use that method of display. It can be found here:
http://wordpress.org/extend/plugins/exec-php/



== Internationalization (i18n) ==
Currently we have the plugin configured so it can be easily translated and the following languages supported:

* en_US - English in the U.S.
* fr_FR - French in France (thanks to [Maxime Toulliou](http://www.maximetoulliou.com/) for contributing )
* it_IT - Italian in Italy (thanks to [Stefan Des](http://www.stefandes.com) for contributing )
* ko_KR - Korean ( thanks to 백선기 (SK Baek)  for contributing )
* ru_RU - Russian in the Russian Federation ( thanks to [Илья](http://fatcow.com) for contributing )
* sv_SE - Swedish in Sweden ( thanks to [Sebastian Johnsson](http://www.agiley.se/) for contributing )

If your language is not listed above, feel free to create a translation. Here are the basic steps:

1. Copy "mailchimp_i18n-en_US.po" to "mailchimp_i18n-LANG_COUNTRY.po" - fill in LANG and COUNTRY with whatever you use for WPLANG in wp-config.php
2. Grab a transalation editor. [POedit](http://www.poedit.net/) works for us
3. Translate each line - if you need some context, just open up mailchimp.php and search for the line number or text
4. Send it to us - api@mailchimp.com - and we'll test it and include it with our next release


== Frequently Asked Questions ==

= What in the world is MailChimp? =

Good question! [MailChimp](http://www.mailchimp.com/features/full_list/) is full of useful, powerful email marketing features that are easy to use and even a little fun (that's right---we said fun), whether you're an email marketing expert, or a small business just getting started.

To learn more, just check out our site: [MailChimp](http://www.mailchimp.com/features/full_list/)


= Wait a minute, you want me to pay to try this? =

*Absolutely not!* We welcome you to come signup for a [FREE Trial Account](http://www.mailchimp.com/signup/) and
see if you find it useful.

= I want this in my language, do you have a translation? =
Maybe! Look in the /po/ directory in our plugin package and see if your language is in there. If it is, great! If it is not, feel from to create one. Here are the basic steps:
1. Copy "mailchimp_i18n-en_US.po" to "mailchimp_i18n-LANG_COUNTRY.po" - fill in LANG and COUNTRY with whatever you use for WPLANG in wp-config.php
2. Grab a transalation editor. [POedit](http://www.poedit.net/) works for us
3. Translate each line - if you need some context, just open up mailchimp.php and search for the line number or text
4. Send it to us - api@mailchimp.com - and we'll test it and include it with our next release



== Screenshots ==

1. Entering your MailChimp login info
2. Selecting your MailChimp list
3. Configuring your Signup Form display format (optional)
4. Configuring extra fields on your Signup Form (optional)
5. An example Signup Form Widget

