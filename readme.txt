=== XO10 - WooCommerce Categories widget ===
Contributors: saltern
Donate link: http://www.cancer.org
Tags: woocommerce, widget, product categories, category images, category thumbnails, category icons, xo10
Requires at least: 3.9
Tested up to: 4.2
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows WooCommerce product category images to be displayed in a WordPress widget.  

== Description ==

The default WooCommerce Product Categories widget is unable to
display product category images. This plugin creates a new widget that
adds the following features that the default plugin lacks.

* display category as text only, text with image, or image only.
* positions of text-image and post counts can be changed.
* specify the size of the category thumbnail to be displayed.
* specify an *optional* CSS ID if you need to style the list differently from other lists.
* specify one or more CSS classes to make use of your theme's existing style.

This plugin requires **PHP 5.3 or later** and you can find the [documentation](http://cartible.com/projects/xo10-woocommerce-categories-widget/) and more stuff on WooCommerce on our site.

*Image credits: [Photo](https://flic.kr/p/bEiYBV) by Ivan / [CC
BY](https://creativecommons.org/licenses/by/2.0/)*



== Installation ==

Follow these steps:

1. Upload `xo10-wc-categories-widget.php` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to 'Appearance > Widgets' and find the widget 'XO10 - WooCommerce Categories' and place it in your sidebar.


== Frequently Asked Questions ==

= How can I style the list or images to look a certain way? =

You would need to know how your *theme* works and how to use *CSS* in order to style
the category images to look a certain way. For example, to display the images as a grid.

= Why aren't images or post counts displayed when categories are displayed as a DropDown? =

The default WooCommerce Product Categories widget does not do that
also. For the time-being, we won't be implementing this feature
because it requires extra Javascript and other usability
considerations for mobile users.

= Where is the documentation for this plugin? =

Documentation for this plugin can be found at [cartible.com](http://cartible.com/projects/xo10-woocommerce-categories-widget/).

= Where is the PO or MO file for translation? =

I foresee the plugin undergoing quite a lot of changes for some time
before it is stable. That's because I have some features that are
already done but not included in this version of the plugin yet. So
I'll add the PO/MO file when things are more stable.

= Is the plugin's code on GitHub? =

Not at the moment but it will definitely be there. I need some time to test and
clean up the code before I put them there.

= What does the XO10 mean? =

To me, it means lots of things but I'll talk about them another day. For now, just take
it that it sounds like my WordPress username - *saltern*. And is also what I use
as a unique namespace prefix for all plugins that I write.


== Screenshots ==

1. Widget admin settings.
2. Widget display (depends on your theme)
3. Change of positions to post counts, text, image. (display will depend on theme)


== Changelog ==

= 1.2 =
* Code is updated to work properly with WooCommerce 2.3.x.

= 1.1 =
* Feature: Category name and thumbnail positions can be switched. As requested by *marco*.
* Feature: Post counts can be shown on the extreme left or right.
* Tweak: Added requirement for PHP 5.3 or later in plugin description.
* Tweak: Changed the plugin text domain.

= 1.0 =
* Initial release.


== Upgrade Notice ==

= 1.2 =
*  No new features added. Only source code updated.

= 1.1 =
* Make sure the "Text/Image display" field value is correct after upgrade. Change the value and save the widget again if necessary.

= 1.0 =
* N.A.
