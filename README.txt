=== AJAX Comments ===
Contributors: DjZoNe, Mike Smullin
Tags: comments, ajax, security
Requires at least: 2.0.3
Tested up to: 2.3.1
Stable tag: 2.08

Post comments quickly without leaving or refreshing the page.

== Description ==

More than that, it checks if all fields filled correctly, and also makes sure to avoid comment duplication, and has flood protection capabilities as well.  

Probably one of the best ways you could spice up your WordPress Blog with AJAX; readers love it! Must see for yourself.

This plugin works well in all major Web browsers, and uses discrete AJAX.
That means if JavaScript disabled, it's using the original comment posting method.

Ajax Comments known to work well, with Authimage plugin, but I rather suggest Akismet, as it is free for personal use.

Features:

    * comment form validation happens server-side without refreshing or leaving the page
    * Script.aculo.us Fade In/Out Effects make readers happy
    * works with AuthImage captcha word verification plug-in to prevent comment spam
    * still works traditionally if browsers don't support JavaScript (or have it disabled)
    * uses existing theme code to match styled comment threads when producing new comments
    * 25-second server timeout ensures readers aren't left hanging
    * works in current versions of Firefox, Internet Explorer, Opera, Netscape, and Safari.

Recommendations:

    * perfectly compliments any well-styled comment form design-don't design without it
    * best when moderation is off (seems more real-time) and AuthImage is installed (self-moderation is the best moderation)


== Frequently Asked Questions ==

= Is there a major difference between version 2.07 and 2.08 =

No. 2.08 is just a bugfix release. The first bug only comes out if you are using non latin1 character set, and has WordPress 2.2 or above.
The other one is only a design kind of bug.

On the other hand it has upgraded version of prototype.js AJAX frame work, and the latest version of script.aculo.us AJAX library.

= So, is it worth to upgrade? =

It depends on your, and your visitors language.
If you are only using latin1 character set, and you don't bother of that tiny design bug I'll have to say no.

== Known bug ==

I've discovered that AJAX Comments 2.0 stops working properly if you 
have ShowOnFrontPage plugin (v0.3.1) installed.

And we got another report, that it also stops, when you have SpamKarma activated.

We are working on a solution. 

== Installation ==

1. Unzip/upload to /plugins directory.
1. Activate via WordPress Plugins tab.

== AuthImage Integration ==

1. Open ajax-comments.php and uncomment lines: 92, 152, 153.
1. Open comments.php from your /themes directory and use the following code (mostly just the ids) for AuthImage to appear in your comments form:
    <code>
    <?php if ( !$user_ID ) : ?>

    <p><img id="auth-image" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/authimage/authimage-inc/image.veriword.php" alt="Verification Image" /></p>
    <p><label for="code">Word Verification (<a href="#" onclick="document.getElementById('auth-image').src+='?'+Math.random();return false" title="Generate another Captcha Word Verification image.">can't read it? try another!</a>)</label></p>
    <p>Please type the letters you see in the picture.</p>
    <p><input name="code" id="code" type="text" class="text" tabindex="5" /></p>

    <?php endif; ?>
    </code>
