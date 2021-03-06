=== Plugin Name ===
Contributors: Clay Lua
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=i_ah_yong%40hotmail%2ecom&lc=MY&item_name=Coffee&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: hungred, image, fit, resize,image resize, custom resize, hungred image fit, post, picture, shrink,smaller,picture resize, picture
Requires at least: 2.7
Tested up to: 3.2.1
Stable tag: 0.7.2

This plugin is created by Clay Lua. Please visit the plugin page for more information.

== Description ==
This is a very small and lightweight plugin that automatically adjust your images on your post with a maximum width. This is to eliminate the problem where your images are too large and overlap other parts of your website layout.
Any image that are greater than the maximum width is being adjust according to its ratio aspect. Those images that are smaller than the maximum width is being ignored
On the other hand, resized images also have its tag enhanced to have 'title' and 'alt' attributes for better SEO.

A control panel for adjusting the maximum width size is provided.

For version 0.7 onwards, there is a few new features.
	1. User now can resize post images with specific tag
	2. User can remove resize capability of a particular post by placing "noresize" on the alt attribute of the img tag (<img>)
	3. User can resize a particular post by placing "resize"  on the alt attribute of the img tag (<img>) for user to have absolute control over resizing of image.
	4. Resize method has changed to an optimum one where resizing will not delay your website due to the waiting time for image detail using PHP getimagesize function.

== Installation ==

   1. Download the latest version of the Hungred Image Fit to your computer.
   2. With an FTP program, access your site��s server.
   3. Upload (copy) the Plugin file(s) or folder to the /wp-content/plugins folder.
   4. In your WordPress Administration Panels, click on Plugins from the menu.
   5. You should see your Hungred Post Thumbnal Plugin listed. If not, with your FTP program, check the folder to see if it is installed. If it isn��t, upload the file(s) again. If it is, delete the files and upload them again.
   6. To turn the WordPress Plugin on, click Activate which is located around the Hungred Post Thumbnail Plugin.
   7. Check your Administration Panels or WordPress blog to see if the Plugin is working.

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==
= 0.1 =
* initial version
= 0.2 =
* Update links and content of this documentation
= 0.3 =
* Fixed Links
= 0.4 =
* Function displaying error problem
= 0.5 =
* Improve Interface
* Added News
= 0.5.1 =
* Fixed plugin style that affect other style
= 0.5.2 =
* Fixed and enhance styling
= 0.6.0 =
* Structure Change
* Improve Logic
* Fixed Image already resize to a smaller size than original but plugin resize was used instead
* Fixed regular express miss image issue.
* Change font type to prevent mac user who use firefox or chrome browser having problem viewing the page
= 0.6.1 =
* Fixed after update did not display properly on the admin page.
= 0.7.0 =
* Optimize the code.
* Added new function that will resize image that has a specific tag (tagging)
* Added new function that will prevent any image from being resize
* Added new function that will resize any particular image although it does not have a specific tag.
* Update new resize function method that won't affect site loading time.
* Remove majority operation to reduce the number of codes being executed.
= 0.7.1 =
* fixed access problem on admin page for wordpress 3.0
= 0.7.2 =
* updated timthumb plugin
* fixed issue where smaller image is resize although it is already smaller
