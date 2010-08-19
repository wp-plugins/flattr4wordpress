<?php

/*  Copyright 2010 Axiom7 Systems (email: Flattr4WordPress@axiom7.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
Plugin Name: Flattr4WordPress
Plugin URI: http://axiom7.com
Description: This plugin implements a Flattr shortcode.
Version: 1.1
Author: Axiom7 Systems (http://axiom7.com)
Author URI: http://axiom7.com
License: GPL2
Text Domain: axiom7_Flattr4WordPress
*/


//{{{ Internationalization
load_plugin_textdomain('axiom7_Flattr4WordPress',
                       NULL,
                       dirname(plugin_basename(__FILE__)));
//}}}


//{{{ Options Management
function axiom7_Flattr4WordPress_OutputOptionsEditingPage() {

    $title          = __('Flattr4WordPress Settings', 'axiom7_Flattr4WordPress');
    $userIdLabel    = __('Your Flattr User ID:', 'axiom7_Flattr4WordPress');
    $userId         = get_option('axiom7_Flattr4WordPress_UserId');
    $buttonLabel    = __('Save Changes', 'axiom7_Flattr4WordPress');

    $optionsEditingPage = <<<END
<div class="wrap">
    <h2>{$title}</h2>
    <form action="" method="post" name="form1">
        <p>{$userIdLabel}
           <input name="axiom7_Flattr4WordPress_UserId" size="20" type="text" value="{$userId}" /></p>
        <p class="submit">
            <input class="button-primary" type="submit" value="{$buttonLabel}" />
        </p>
    </form>
</div>
END;

    echo $optionsEditingPage;
}

function axiom7_Flattr4WordPress_OutputUpdateConfirmation() {

    $message = __('Settings saved.', 'axiom7_Flattr4WordPress');

    $updateConfirmation = <<<END
<div class="updated">
    <p><strong>{$message}</strong></p>
</div>
END;

    echo $updateConfirmation;
}

function axiom7_Flattr4WordPress_OutputOptions() {

    if (!current_user_can('manage_options')) {

        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    if (isset($_POST['axiom7_Flattr4WordPress_UserId'])) {
        $userId = $_POST['axiom7_Flattr4WordPress_UserId'];

        update_option('axiom7_Flattr4WordPress_UserId', $userId);

        axiom7_Flattr4WordPress_OutputUpdateConfirmation();
    }

    axiom7_Flattr4WordPress_OutputOptionsEditingPage();
}

function axiom7_Flattr4WordPress_AddOptionPage() {

  add_options_page('Flattr4WordPress Settings',
                   'Flattr4WordPress',
                   'manage_options',
                   'axiom7_Flattr4WordPress',
                   'axiom7_Flattr4WordPress_OutputOptions');
}

add_action('admin_menu',
           'axiom7_Flattr4WordPress_AddOptionPage');
//}}}


//{{{ Flattr Shortcode
/*
    Javascript API

    If you e.g. host your own blog and can't find a plugin or extension for your system you can use the auto submit feature. Use the following code, you need to add a few parameters (bold = required). This will submit the "Things" to Flattr when people start to Flattr them.

    <script type="text/javascript">
        var flattr_btn = 'compact';
        var flattr_uid = 'your user id';
        var flattr_tle = 'the entry title';
        var flattr_dsc = 'the entry description, please be as thorough as possible';
        var flattr_cat = 'category';
        var flattr_lng = 'language';
        var flattr_tag = 'tag1, tag2, tag3';
        var flattr_url = 'http://www.example.com';
        var flattr_hide = 'true';
    </script>
    <script src="http://api.flattr.com/button/load.js" type="text/javascript"></script>
    The parameters are:

    flattr_btn - (Optional) This is the type of button you would like to display. To get the compact button use 'compact' otherwise the default (large) button is displayed. (When using the compact button its best to place this parameter first as the button then will be compact even if the later parameters messes up something.)

    flattr_uid - This is your personal user id. Log in to see it if it's not shown above.

    flattr_tle - This is the title of the thing you want to submit. This is typically the title of your blog entry or software name.

    flattr_dsc - This is the full excerpt of the content. Some blog text or information about your song you've written or so forth.

    flattr_cat - This is the flattr category the content belongs to. You can choose between the following: text, images, video, audio, software, rest.

    flattr_lng - Language of the submitted thing. List of available languages »

    flattr_tag - (Optional) This is the tags of the thing, to help people finding your content easier on the Flattr website. If you want to use multiple tags, separate using a normal comma (,) sign.

    flattr_url - (Optional) This is the URL of the thing, if this is not always the same on your site. Maybe you have multiple domains with the same content. This is to lock the content to always be recognized as the same content for Flattr.

    flattr_hide - (Optional) Use this to hide the thing from listings on flattr.com. The value 'true' will hide the thing.
*/

function handleFlattrShortcode($atts, $content = NULL, $code = '') {
   // $atts    ::= array of attributes
   // $content ::= text within enclosing form of shortcode element
   // $code    ::= the shortcode found, when == callback name
   // examples: [my-shortcode]
   //           [my-shortcode/]
   //           [my-shortcode foo='bar']
   //           [my-shortcode foo='bar'/]
   //           [my-shortcode]content[/my-shortcode]
   //           [my-shortcode foo='bar']content[/my-shortcode]

   if (is_feed()) {

       return '';
   }

   if (!($uid = get_option('axiom7_Flattr4WordPress_UserId'))) {

       return '<p>'
                . __('Flattr4WordPress: Flattr User ID is missing.', 'axiom7_Flattr4WordPress')
                . '</p>';
   }

   extract(shortcode_atts(array(
                                'btn'   => 'large',
                                'tle'   => get_the_title(),
                                'dsc'   => get_the_excerpt(),
                                'cat'   => 'rest',
                                'lng'   => get_locale(),
                                'tag'   => 'axiom7',
                                'url'   => get_permalink(),
                                'hide'  => 'false'),
                          $atts));

   $flattrJavaScript = <<<END
<script type="text/javascript">
    var flattr_btn  = '{$btn}';
    var flattr_uid  = '{$uid}';
    var flattr_tle  = '{$tle}';
    var flattr_dsc  = '{$dsc}';
    var flattr_cat  = '{$cat}';
    var flattr_lng  = '{$lng}';
    var flattr_tag  = '{$tag}';
    var flattr_url  = '{$url}';
    var flattr_hide = '{$hide}';
</script>
<script src="http://api.flattr.com/button/load.js" type="text/javascript"></script>
<br style="clear:right;margin-bottom:1em;margin-top:1em" />
END;

    return $flattrJavaScript;
}

add_shortcode('flattr',
              'handleFlattrShortcode');
//}}}

?>