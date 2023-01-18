=== Error Log Monitor, Activity Logs, User Activity Tracking from Logtivity ===

Contributors: logtivity, stevejburge
Tags: activity log, error logs, event monitoring, user activity, 
Requires at least: 4.7
Tested up to: 6.0
Stable tag: 2.1.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Logtivity is the error log and activity log service for WordPress agencies. Logtivity is a unified platform that tracks activity and errors across all your WordPress sites.

== Description ==

<strong>When you set up Logtivity to monitor your clients' sites, you can relax. We track everything that happens on the sites you maintain, and Logtivity also sends you alerts for important events!</strong>

Logtivity is a unified platform that tracks activity and errors across all your WordPress sites. We record everything that happens, and then send you alerts for important events! You can install Logtivity’s plugin and then use our service to keep a close eye on everything that happens on your clients’ sites.

Your clients never have to know that you’re using Logtivity. You can either show a white label version of the activity logs, or you can hide Logtivity entirely.

### WordPress Error Logs ###

Logtivity records all PHP errors on your sites, including Errors, Warnings, and Notices.

You can see the errors, how often they occur, and when they last occurred. It doesn’t matter where your site is hosted. Our logs will record the errors and point you to the file that’s causing problems.

We’ll notify you as soon as an error occurs, allowing you to jump on it as soon as possible rather than waiting for a user to report it.

[Click here to see more about error logs](https://logtivity.io/features/error-logs/).

### WordPress Activity Logs ###

Logtivity provides the best activity logs for WordPress agencies. You can record all the user activity on your clients' sites. Then you can use the activity log data to send notifications to email or Slack. Plus, you can easily search and export the information. And you can turn the activity log data into beautiful, useful charts. 

If you have customers on your clients' WordPress site, you’ll find Logtivity to be invaluable. Because Logtivity records all the important activity, you can see real customer journeys across the WordPress sites. This can be incredibly helpful for customer support: the activity log will show exactly what a user has done on your site.

To get started, simply install the Logtivity plugin and then connect your site to [Logtivity](https://logtivity.io). You’ll immediately start to see the results. 

[Click here to see more about activity logs](https://logtivity.io/features/event-tracking/).

### Instant Alerts for WordPress Sites  ###

With Logtivity alerts, you can keep an eye on all your clients’ sites. You can set up flexible alerts for single sites or all your clients’ sites.

These notifications can go directly to your email inbox or to Slack channels.

If you have many sites, you can set up global alerts. For example, even if you have 100 sites, you only need to configure the alert once.

One Logtivity customer chooses to receive an email every time a plugin or theme is updated. Another WordPress agency has a Slack alert for every time an administrator logs in.

[Click here to see more about alerts](https://logtivity.io/features/instant-alerts/).

### Charts from Your Activity Logs

Logtivity is a WordPress activity log with a big difference. You can track all the activity on your clients’ sites, and you can also turn that information into beautiful and useful charts.

Displaying data in charts gives you a helpful and organized overview of your clients’ key metrics. You can use these charts to show logins, purchases, subscriptions, cancellations, downloads, or any other key events. If it happens in WordPress site, Logtivity can turn it into a bar chart or a line chart.

You can also customize the date range for charts. Your charts have advanced date ranges, so you can zoom in to view any time period.

[Click here to see more about charts](https://logtivity.io/features/customisable-reporting-dashboard/).

### Large Activity Log Exports ###

Normal WordPress activity plugins can not handle large amounts of data.

Logtivity is able to handle exports for even the biggest WordPress sites! If your clients’ site uses Logtivity, you can export millions and millions of logs.

In the image next to this text, you can see over a dozen CSV files. Each of these files is a Logtivity export that contains 100,000 logs. This export has 13 files, so it’s over 1,300,000 million logs in total.

Logtivity is the activity log solution for large WordPress sites!

[Click here to see more about log exports](https://logtivity.io/features/large-exports/).

### View Activity Logs Inside WordPress ###

Logtivity has a central dashboard where you can see the logs for all your clients’ WordPress sites.

Plus, you and your clients can also view and search the logs from inside each WordPress site.

The image on this screen shows what you’ll see inside WordPress after installing the Logtivity plugin.

All of the activity log data is visible and searchable in the WordPress admin area. And if you want more information on any specific log entry, you can click the “View” button next to each log.

[Click here to see more about the WordPress integration](https://logtivity.io/features/easy-wordpress-integration/).

### Logtivity has a White Label Mode for Agencies ###

The most frequent users of Logtivity are WordPress agencies and maintenance services who want to keep an eye on lots of websites.

Agencies and maintenance services often white label the services they use, and so we’ve made this possible for Logtivity also.

There’s a “White Label Mode” in Logtivity, so you can provide the smoothest experience possible for clients. You can remove all the references to Logtivity from the WordPress admin area.

[Click here to see more about the White Label mode](https://logtivity.io/features/white-label/).

### Join Logtivity and Start Your Monitoring ###

> <strong>Logtivity is a SaaS service</strong><br />
> You will need to create a Logtivity account to store your activity logs and create alerts. <a href="https://app.logtivity.io/register" rel="friend" title="Logtivity">Click here to get started with Logtivity!</a>

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

= What user activities does Logtivity record? =

Logtivity records all core WordPress actions. In addition to support for the WordPress core, Logtivity records events for many plugins and themes.

[Click here to see more about what Logtivity records](https://logtivity.io/docs/what-logs/).

= Does Logtivity have activity logs for WordPress user logins? =

It’s important to have an activity log on your WordPress site. You need user tracking because you need to know many people are visiting, making purchases, and logging in to your site. Yes, you can track visitors with Google Analytics, and you can track purchases with your payment gateway, but it’s harder to track WordPress-specific information such as log ins. Using Logtivity, you can get an overview of who is logging in to your site, and how many people are logging in every day.

If Logtivity is active on your site, you can go to the “Logs” screen and search for all the “User Logged in” events. You can use the search boxes to drill down for more specific user tracking information. For example, you can use the “Context” box to search for a particular user role. You can use this to search for all "Administrator" logins or all "Editor" logins.

You can also use the search option as a security log and look for nefarious patterns in the audit logs. For example, you can search by IP address to see if one person is using multiple logins. Or you can search by username to see if one account is being shared by different people.

[Click here to see more about activity logs for user logins](https://logtivity.io/track-user-logins-wordpress/).

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

= How does Logitivity compare to other management services? =

There are some hosted solutions such as ManageWP, MainWP, WPMU Dev, Jetpack, WP Umbrella, WP Remote, InfiniteWP, CMS Commander, iControlWP and more. Logtivity is different because it is focused on more advanced tools for agencies. With Logtivity you have very detailed activity logs and incredibly detailed error logs. We're committed to building expert levels tools to help WordPress professionals.

= Logitivity branding information =

Logtivity is the official brandname. When writing about this activity plugin, please make sure to uppercase the L:

* Logtivity (correct)
* logtivity (incorrect)
* Logtivity.io (incorrect)
* Log tivity, loggtivity, logg tivity (all incorrect)

== Changelog ==

= 2.1.1 =

_Release Date – Wednesday 18th January 2023_

* Fix undefined variable $meta in _logs-loop.php admin page when not connected to Logtivity.

= 2.1.0 =

_Release Date – Tuesday 6th December 2022_

* Add ability to hide Logtvity from the WP UI using global settings.
* Check for new global settings every 10 minutes.
* When registering a site dynamically, pull in the site name.

= 2.0.1 =

_Release Date – Monday 7th November 2022_

* Wrap better messaging on logs page when there are no results.
* Refine Option Updated meta sent.
* Add wrap error logging in try catch just incase of issue during logging.

= 2.0 =

_Release Date – Wednesday 26th October 2022_

* Add error logging.
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
