<?php
/*
Plugin Name: Vegan slogans
Plugin URI: https://yourls.org/
Description: This plugin replaces the URL generation method with a custom one using vegan slogans, e.g. ANimaLS-fEEl-pAiN
Version: 1.0
Author: veganDE
Author URI: https://reddit.com/user/veganDE
*/

// No direct call
if(!defined('YOURLS_ABSPATH')) die();

yourls_add_filter('random_keyword', 'vegan_get_random_slogan');
yourls_add_filter('get_next_decimal', 'vegan_next_decimal');
yourls_add_filter('get_shorturl_charset', 'vegan_shorturl_charset');


// Don't increment sequential keyword tracker
function vegan_next_decimal($next) {
    return ($next - 1);
}

// We need the uppercase letters and a hyphen in order for this to work
function vegan_shorturl_charset($in) {
    return '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-';
}

class SloganGenerator {

    public static $default_slogans = array(
        "animals feel pain",
        "meat is murder",
        "dairy is murder",
        "you are not a baby cow bro",
        "animals are individuals not ingredients",
        "animals are friends not food",
        "milk is for babies",
        "there is no excuse for animal abuse",
        "animal agriculture is killing our planet",
        "your choice has a victim",
        "how can you love animals and eat them",
        "animal lovers dont eat animals",
        "choose fries not lives",
        "end speciesism"
    );

    var $slogans;
    var $use_numbers;
    var $char_sets;

    function __construct($slogans, $use_numbers) {
        $this->slogans = explode("\n", $slogans);
        $this->use_numbers = $use_numbers;
        $this->char_sets = $this->get_char_sets();
    }

    function get_char_sets() {
        $char_sets = array();
        $chars = str_split("abcdefghijklmnopqrstuvwxyz");
        $additions = array();
        if($this->use_numbers) {
            $additions = array("o" => "0", "i" => "1", "s" => "5", "z" => "2");
        }
        foreach($chars as $char) {
            $char_set = array($char);
            if($char != strtoupper($char)) $char_set[] = strtoupper($char);
            if(array_key_exists($char, $additions)) $char_set[] = $additions[$char];
            $char_sets[] = $char_set;
        }
        return $char_sets;
    }

    function get_char_subs($char) {
        foreach($this->char_sets as $char_set) {
            if(in_array($char, $char_set)) {
                return $char_set;
            }
        }
        // return $char otherwise
        return array($char);
    }

    function sub_char($char) {
        $char_set = $this->get_char_subs($char);
        return $char_set[array_rand($char_set)];
    }

    function randomize_slogan($slogan) {
        $chars = str_split($slogan);
        $randomized_slogan = '';
        foreach($chars as $char) {
            $randomized_slogan .= $this->sub_char($char);
        }
        return $randomized_slogan;
    }

    function get_random_slogan() {
        return str_replace(' ', '-', $this->randomize_slogan($this->slogans[array_rand($this->slogans)]));
    }
}

function vegan_get_random_slogan() {
    $generator = new SloganGenerator(
        yourls_get_option('vegan_slogans', implode("\n", SloganGenerator::$default_slogans)),
        yourls_get_option('vegan_slogans_use_numbers', true)
    );
    return $generator->get_random_slogan();
}

// Hook the admin page into the 'plugins_loaded' event
yourls_add_action('plugins_loaded', 'vegan_slogans_add_page');

function vegan_slogans_add_page() {
    yourls_register_plugin_page('vegan_slogans', 'Vegan slogans', 'vegan_slogans_do_page');
}

// Display admin page
function vegan_slogans_do_page() {
    if(isset($_POST['slogans_form'])) {
        vegan_slogans_process();
    }

    vegan_slogans_form();
}

// Display form to administrate slogans list
function vegan_slogans_form() {
    $nonce = yourls_create_nonce('form_nonce');
    $liste_charset_display = htmlspecialchars(yourls_get_option('vegan_slogans', implode("\n", SloganGenerator::$default_slogans)));
    $use_numbers_display = yourls_get_option('vegan_slogans_use_numbers', true) ? "checked" : "";
    echo <<<HTML
        <h2>Vegan slogans</h2>
        <form method="post">
        <input type="hidden" name="nonce" value="$nonce" />
        <p>
            <label for="slogans_form">List of vegan slogans:</label>
            <br>
            <textarea rows=10 cols=50 id="slogans_form" name="slogans_form">$liste_charset_display</textarea>
        </p>
        <p>
            <input type="checkbox" id="use_numbers_form" name="use_numbers_form" $use_numbers_display>
            <label for="use_numbers_form">Allow substitution with numbers (o => 0, i => 1, s => 5, z => 2): </label>
        </p>       
        <p><input type="submit" value="Save settings"></p>
        </form>
HTML;
}

function vegan_slogans_process() {
    // Check nonce
    yourls_verify_nonce('form_nonce');
    
    $slogans = $_POST['slogans_form'] ;
    $use_numbers = $_POST['use_numbers_form'] ;

    if(yourls_get_option('vegan_slogans') !== false) {   
        yourls_update_option('vegan_slogans', $slogans);
    } else {
        yourls_add_option('vegan_slogans', $slogans);
    }

    if(yourls_get_option('vegan_slogans_use_numbers') !== false) {   
        yourls_update_option('vegan_slogans_use_numbers', $use_numbers);
    } else {
        yourls_add_option('vegan_slogans_use_numbers', $use_numbers);
    }

}
