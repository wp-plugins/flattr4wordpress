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


//{{{ Initialization
function axiom7_Flattr4WordPress_Initialize() {

    wp_enqueue_script('flattr',
                      'http://api.flattr.com/js/0.5.0/load.js');
}

add_action('init', 'axiom7_Flattr4WordPress_Initialize');
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
function axiom7_Flattr4WordPress_HandleFlattrShortcode($atts,
                                                       $content = NULL,
                                                       $code = '') {

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
                                 'btn'  => 'default',
                                 'tle'  => get_the_title(),
                                 'dsc'  => get_the_excerpt(),
                                 'cat'  => 'rest',
                                 'lng'  => get_locale(),
                                 'tag'  => 'axiom7',
                                 'url'  => get_permalink(),
                                 'hdn'  => 0),
                           $atts));

    $flattrButtonDefinition = <<<END
<div style="margin-bottom:1em;margin-top:1em"><a class="FlattrButton" href="{$url}" rev="flattr;button:{$btn};category:{$cat};hidden:{$hdn};language:{$lng};tags:{$tag};uid:{$uid}" style="display:none" title="{$tle}" lang="{$lng}">{$dsc}</a></div>
END;

    return $flattrButtonDefinition;
}

add_shortcode('flattr',
              'axiom7_Flattr4WordPress_HandleFlattrShortcode');

function axiom7_Flattr4WordPress_SetUpFlattr() {

    echo <<<END
<script type="text/javascript">
/* <![CDATA[ */
    FlattrLoader.setup();
/* ]]> */
</script>
END;
}

add_action('wp_footer', 'axiom7_Flattr4WordPress_SetUpFlattr');
//}}}

?>