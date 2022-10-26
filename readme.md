=== Activity Logs, Alert Notifications, User Activity Tracking from Logtivity ===

Contributors: logtivity, stevejburge
Tags: activity log, logging, event monitoring, user activity, easy digital downloads, edd, formidable, formidable pro
Requires at least: 4.7
Tested up to: 6.0
Stable tag: 2.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Logtivity is the best activity log and monitoring plugin for WordPress agencies. Keep an accurate activity log of everything happening on your WordPress site.

== Description ==

<strong>When you set up Logtivity to monitor your clients' sites, you can relax. We track everything that happens on the sites you maintain, and Logtivity also sends you alerts for important events!</strong>

Logtivity is a centralized platform for activity logs and alerts. You can install Logtivity’s plugin and then user our service to keep a close eye on everything that happens on your clients’ sites.

Your clients never have to know that you’re using Logtivity. You can either show a white label version of the activity logs, or you can hide Logtivity entirely.

### Activity Logs and Alerts for WordPress Agencies ###

[Logtivity](https://logtivity.io) provides the best activity logs for WordPress agencies. You can record all the user activity on your clients' sites. Then you can use the activity log data to send notifications to email or Slack. Plus, you can easily search and export the information. And you can turn the activity log data into beautiful, useful charts. 

If you have customers on your clients' WordPress site, you’ll find Logtivity to be invaluable. Because Logtivity records all the important activity, you can see real customer journeys across the WordPress sites. This can be incredibly helpful for customer support: the activity log will show exactly what a user has done on your site.

To get started, simply install the Logtivity plugin and then connect your site to [Logtivity](https://logtivity.io). You’ll immediately start to see the results. 

### Join Logtivity and Start Your Monitoring ###

> <strong>Logtivity is a SaaS service</strong><br />
> You will need to create a Logtivity account to store your activity logs and create alerts. <a href="https://app.logtivity.io/register" rel="friend" title="Logtivity">Click here to get started with Logtivity!</a>

Yes, Logtivity will keep a record of the activity on your clients' websites. However, that’s just where the magic begins. You can then use the data in Logtivity and do three additional things:

* Send alert notifications to Slack and email.
* Easily export activity log reports to CSV files, no matter how many logs you have.
* Create beautiful activity log charts to visualize the data.

Here’s an introduction to those three options, showing why Logtivity is the best activity log plugin for WordPress agencies:

### Send Email and Slack alerts for user activity

Once your sites are connected to Logtivity, you can set up unlimited alerts for any activity you want to know about as soon as it happens. You can send the alerts to your email inbox or Slack channels.

One Logtivity customer is an agency that employs various writers. So they’ve set up a series of Slack alerts that show when the writers log in. You can use this as a security alert, letting you know every time someone in the Author role logs in.

Another agency customer sends themselves an email every time a plugin or theme is updated. They only run Wordpress updates on Monday, but realized that some plugins will run their own auto-updates. Logtivity allows them to be constantly aware of all site changes. 

Another WordPress agency sends themselves an alert every time a user is added to any of their 100+ sites! They manage a network of portfolio sites that should never have new users. It's important to them to instantly get an alert if a new user is ever created.

eCommerce sites can use Logtivity for convenient notifications and set up alerts for all new, changed, and canceled subscriptions. 

[Click here to see more about alerts](https://logtivity.io/docs/notifications/).

### Export user activity logs to CSV

Logtivity was started because one of our clients had reporting problems due to the amount of data collected on their site. The client was using a plugin that stored data in the WordPress database. Whenever we tried to export large amounts of logs, the site would show 502 errors. We needed to have the information stored separately. Being a dedicated service, Logtivity can optimize for things such as exporting very large numbers of activity logs.

[Click here to see more about activity log exports](https://logtivity.io/docs/export-data/).

### Create beautiful charts from your activity log

The banner on this WordPress.org plugin page shows you what's possible with Logtivity charts. First, I searched my site’s data for file downloads and then clicked the “Convert to Report” button. Logtivity did the rest. With a couple of mouse clicks, I’ve created a chart showing all the daily file downloads.

Logtivity customers can build a whole dashboard full of charts, so you can quickly see the number of logins, downloads, payments and anything else that's important to you.

[Click here to see more about activity log charts](https://logtivity.io/docs/reports/).

### What user activities does Logtivity record?

Logtivity records all core WordPress actions. In addition to support for the WordPress core, Logtivity records events for many plugins and themes.

[Click here to see more about what Logtivity records](https://logtivity.io/docs/what-logs/).

### Activity logs for WordPress user logins

It’s important to have an activity log on your WordPress site. You need user tracking because you need to know many people are visiting, making purchases, and logging in to your site. Yes, you can track visitors with Google Analytics, and you can track purchases with your payment gateway, but it’s harder to track WordPress-specific information such as log ins. Using Logtivity, you can get an overview of who is logging in to your site, and how many people are logging in every day.

If Logtivity is active on your site, you can go to the “Logs” screen and search for all the “User Logged in” events. You can use the search boxes to drill down for more specific user tracking information. For example, you can use the “Context” box to search for a particular user role. You can use this to search for all "Administrator" logins or all "Editor" logins.

You can also use the search option as a security log and look for nefarious patterns in the audit logs. For example, you can search by IP address to see if one person is using multiple logins. Or you can search by username to see if one account is being shared by different people.

[Click here to see more about activity logs for user logins](https://logtivity.io/track-user-logins-wordpress/).

== Screenshots ==

1. The best activity log for WordPress sites
2. See your clients' site logs inside WordPress
3. Get alerts for all key WordPress events
4. Create charts from your activity logs
5. Export large data files from your activity logs

== Installation ==
#### From your WordPress dashboard
Visit 'Plugins > Add New'
Search for 'logtivity’
Activate Logtivity from your Plugins page.

#### From WordPress.org
Download logtivity.
Upload the 'logtivity' directory to your '/wp-content/plugins/' directory, either through the UI (Plugins > Add new) or via SFTP or example.
Activate Logtivity from your Plugins page.

#### Once Activated
Visit 'Tools > Logtivity' to view the settings page.
Enter your Logtivity API key, configure your options and your event monitoring will start!

== Frequently Asked Questions ==

= What plugins do Logtivity activity logs support? =

Logtivity has some support for most WordPress plugins. If the plugin uses post types, we record when most post types are updated, created or deleted.

We currently have integrations with Easy Digital Downloads, Memberpress and Download Monitor. These integrations allow you to track things like Memberpress subscription creations, changes, or cancellations and also track when files are downloaded through Download Monitor.

We are working on more detailed event monitor support for some key plugins, including eCommerce plugins such as WooCommerce and Easy Digital Downloads.

Please contact us for specific details on any plugin that you are using.

= Can I log custom events? =

Yes, our user activity tracking plugin provides a flexible API to log and store custom events with Logtivity. An example of logging a custom event is below. This example is recording information from Stripe.com for a customer:

`
Logtivity::log()
	->setAction('My Custom Action')
	->addMeta('Meta Label', $meta)
	->addMeta('Another Meta label', $someOtherMeta)
	->addUserMeta('Stripe Customer ID', $stripeCustomerId)
	->send();
`

[Click here to see more about custom activity logs](https://logtivity.io/docs/custom-events/).

= Is Logtivity a GDPR-compliant activity log? =

Yes, Logtivity gives you complete control over the user tracking information recorded in the audit logs. You can choose your GDPR settings. You can decide to only log a profile link, user ID, username, IP address, or nothing at all. 

* Inside your WordPress site, go to “Tools” and then “Logtivity”.
* You can uncheck the boxes on this screen to stop Logtivity from recording personal user trackking data.

Here's an overview of the key GDPR-compliant settings:

* Store User ID: If you check this box, when logging an action, we will include the users User ID in the logged action.
* Store Users Profile Link: If you check this box, when logging an action, we will include the users profile link in the logged action.
* Store Users Username: If you check this box, when logging an action, we will include the users username in the logged action.
* Store Users IP Address: If you check this box, when logging an action, we will include the users IP address in the logged action.

[Click here for more about the GDPR and activity logs](https://logtivity.io/docs/personal-data/).

= Can I disable all activity logs and only store custom logs? =

Yes! You can easily disable all event monitoring that this plugin provides so that you can only store the user tracking audit logs that matter to you manually. You can also disable built in logs on an individual basis via the filter example below:

`
add_action('wp_logtivity_instance', function($Logtivity_Logger) {

	if (strpos($Logtivity_Logger->action, 'Page was updated') !== false) {
		$Logtivity_Logger->stop();
	}

});
`
[Click here for more about disabling activity logs](https://logtivity.io/docs/select-events/).

= Can I rename the activity logs? =

Yes, it is possible to rename the events that are stored in Logtivity’s audit logs. For example, you can add this code to your site’s functions.php file. This code will result in an event “File Downloaded” being logged as “Resource Downloaded”.

`
add_action('wp_logtivity_instance', function($Logtivity_Logger) {
    if (strpos($Logtivity_Logger->action, 'File Downloaded') !== false) {
        $Logtivity_Logger->setAction('Resource Downloaded');
    }
});
`
[Click here for more about renaming activity logs](https://logtivity.io/docs/rename-events/).

= Can I export the activity logs? =

Yes, Logtivity makes it easy to export your user activity data into a CSV file. Follow these steps for your audit log export:

* To get started, visit the “Logs” area inside [https://app.logtivity.io](https://app.logtivity.io).
* You can either search for a specific result, or you can use export all your event monitoring logs.
* When you see the logs you want to export, click “Actions” and then “Export to CSV”.
* Logtivity will show you the following message: “Export scheduled”.
* When the export file is ready, Logtivity will send you an email with a download link to the CSV file.

[Click here to see more about exporting activity logs](https://logtivity.io/docs/export-data/).

= Can I send activity log notifications to Slack channels? =

Yes, You can use Logtivity to send alerts to your Slack account. Follow these steps to connect Logtivity to a channel in your Slack account:

* In the Logtivity app, click on the name of your team.
* Click "Alert Channels".
* Check the "Slack" box.
* Enter the name of your Slack channel.
* Click the "Add Channel" button.
* You will now see a screen where Slack asks you to allow Logtivity access. Click the "Allow" button.
* You will be redirected back to the Logtivity app.
* Any alert for this Logtivity team will now be sent to your Slack account.

[Click here to see more about activity logs and Slack notifications](https://logtivity.io/docs/slack/).

= How does Logitivity compare to other activity log plugins? =

There are some good WordPress activity log plugins including WP Activity Log, WP User Activity, User Activity Log, Activity Log, WP Cerber, Jetpack activity log, User Activity Tracking and Log, WP Stream, Simple History, Aryo Activity Log and more.

Logtivity is different from those plugins in several important ways:

* It is a hosted service, so you don't have to store all the data in your site's database. This can really slow down your site! Logtivity allows you to store much more data, and export it more easily.
* Deeper integration with key plugins such as Easy Digital Downloads and MemberPress.
* Visualize your data with Logtivity's charts.

= Logitivity branding information =

Logtivity is the official brandname. When writing about this activity plugin, please make sure to uppercase the L:

* Logtivity (correct)
* logtivity (incorrect)
* Logtivity.io (incorrect)
* Log tivity, loggtivity, logg tivity (all incorrect)

== Changelog ==

= 2.0 =

_Release Date – Wednesday 26th October 2022_

* Add error logging
* Refine Option meta update logging by only logging updates done via a POST request. This avoids logging of less useful updates such as plugins setting last_check/synced timestamps that don't need to be logged.

= 1.20.1 =

_Release Date – Friday 17th June 2022_

* Fix minor layout issue in log modal.
* Disable some unuseful Option Updated logs.

= 1.20.0 =

_Release Date – Friday 17th June 2022_

* Add integration with Code Snippets plugin.

= 1.19.0 =

_Release Date – Thursday 16th June 2022_

* Start logging after plugins_loaded hook to ensure everything has loaded first.

= 1.18.0 =

_Release Date – Monday 13th June 2022_

* Add logging of Option Updates.
* Allow wildcards when disabling specific logs.

= 1.17.1 =

_Release Date – Saturday 4th June 2022_

* Fix occasional User Logged Out logging unnecessarily.
* Hide latest response info when debug mode is off.

= 1.17.0 =

_Release Date – Friday 13th May 2022_

* Added ability to globally disable logs across all sites from the Logtivity dashboard.
* Added ability to white label the Logtivity plugin across all sites from the Logtivity dashboard.

= 1.16.0 =

_Release Date – Monday 2nd May 2022_

* Add support for WP All Import

= 1.15.0 =

_Release Date – Tuesday 19th April 2022_

* Add ability for admins to disable individual actions from being logged.

= 1.14.0 =

_Release Date – Sunday 10th April 2022_

* Add logging of post meta changes.

= 1.13.0 =

_Release Date – Saturday 26th March 2022_

* Add logging of Term Created and Deleted.

= 1.12.0 =

_Release Date – Thursday 24th March 2022_

* Fix unexpected ) error on old vesions of php.
* Add logging of Term Updated.

= 1.11.1 =

_Release Date – Sunday 20th February 2022_

* Fix md5 hash of site_url relative to previous release of detecting url change.

= 1.11.0 =

_Release Date – Sunday 20th February 2022_

* Disable logging if site_url changes and show notice to admin to stop accidental logs coming from dev/staging environments.

= 1.10.0 =

_Release Date – Monday 14th February 2022_

* Add ability to register site with Logtivity from within the plugin by running a code snippet.

= 1.9.2 =

_Release Date – Sunday 23rd January 2022_

* Allow the Logtivity config to be set via filters.

= 1.9.1 =

_Release Date – Wednesday 22nd December 2021_

* Fix bug where logs weren't displaying when Logtivity debug mode was set to false.

= 1.9.0 =

_Release Date – Wednesday 22nd December 2021_

* Moved settings page to top level menu item to allow for sub menu.
* Added new logs page in wp-admin to allow viewing logs inside WordPress.

= 1.8.2 =

_Release Date – Thursday 10th December 2021_

* Fix unnecessary encoding of hyphens being passed to the context field in the API.

= 1.8.1 =

_Release Date – Thursday 9th December 2021_

* Fix conflict between Logtivity and Jetpack Widget Visibility where updating widget visibility in the customiser would fail.

= 1.8.0 =

_Release Date – Wednesday 8th December 2021_

* Added Formidable Pro integration.
* Added CSS tweaks to Logtivity Settings page.

= 1.7.1 =

_Release Date – Sunday 7th November 2021_

* Change action that Logout log hooks into to ensure user_id is attached to log.

= 1.7.0 =

_Release Date – Wednesday 17th August 2021_

* Improvements to Settings page.
* Log EDD License Renewed
* Log EDD License Renewal Notification Unsubscribed
* Log EDD License Status Changed to [new_status]
* Log EDD License Upgraded
* Log EDD Site Deactivated
* Log EDD Site Added
* Log EDD Subscription Renewed
* Log EDD Subscription Created
* Log EDD Subscription Updated
* Log EDD Subscription [status] eg. Expired, Cancelled etc.
* Log EDD Subscription Deleted
* Log EDD Payment Method Updated

= 1.6.1 =

_Release Date – Wednesday 4th August 2021_

* Begin adding support for the Easy Digital Downloads Software Licensing Addon.
* Log License Created events.
* Log License Activated events.
* Log License Activation Failed events.
* Log License Deactivated events.
* Don't log new comments when they are marked as spam.

= 1.6.0 =
* Added initial Easy Digital Downloads core integration.
* Track when Core Settings are updated.
* Track when Permalinks are updated.
* Track when Memberpress Transactions are Created/Updated.
* Track when Memberpress Emails are sent.
* Track when a Memberpress User Profile is updated.
* Track when Memberpress Settings are Updated.
* Track WordPress comments CRUD.

= 1.5.0 =
* Renamed Download Monitor Action name to File Downloaded.
* Added Request URL as log meta.
* Added Request Type as log meta.

= 1.4.0 =
* Removed deprecated async method from Logtivity_Logger class.
* Added API key verification when updating Logtivity settings.

= 1.3.1 =
* Fix user info not always being picked up on User login action.
* Fix 0 being logged for username when not logged in.
* Fixed duplicate logs being recorded when Updating a post in Gutenberg.

= 1.3.0 =
* Added revision link to Post Updated logs.
* Added Role to Content parameter for User Logged In and User Logged Out.

= 1.2.0 =
* Added context parameter to API calls to separate out Actions from Titles.

= 1.1.0 =
* Add logging when updating menus.
* Add logging when updating widgets.
* Fix spelling mistake in postPermanentlyDeleted method.

= 1.0 =
* Fix php warning when tracking a logout event.
