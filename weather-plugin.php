<?php
 
/*
 
Plugin Name: Weather Plugin
 
Plugin URI: https://github.com/amiealford/weather-plugin
 
Description: Plugin to accompany tutsplus guide to creating plugins, registers a post type.
 
Version: 1.0
 
Author: Amie Alford
 
Author URI: https://github.com/amiealford/
 
License: GPLv2 or later
 
Text Domain: amiealford
 
*/

function weather_plugin_add_scripts(){
	wp_register_script( 'weather_plugin_script', plugins_url( 'script.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'weather_plugin_script' );
}

add_action( 'wp_enqueue_scripts', 'weather_plugin_add_scripts' );

function get_user_ip() { 

    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) { 
        $ip = $_SERVER['HTTP_CLIENT_IP']; 
     }
     
     elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
     }
     
     else { 
         $ip = $_SERVER['REMOTE_ADDR']; 
     } 
     
    //  return $ip;
    return '35.142.172.91';
}

function getLocationFromIp($ip) {
    $apiKey = '*API key here*';
    $response = wp_remote_get('http://api.ipstack.com/' . $ip . '?access_key=' . $apiKey . '&format=1');
    $location = '';

    if ( is_array( $response ) && ! is_wp_error( $response ) ) {
        $location = $response['body']; // use the content
    }


    return $location;
}

function getWeather($lat, $long) {
    $apiKey = '*API key here*';
    var_dump('https://api.openweathermap.org/data/2.5/onecall?' . 'lat=' . $lat . '&lon=' . $long . '&appid='. $apiKey);
    $response = wp_remote_get('https://api.openweathermap.org/data/2.5/onecall?' . 'lat=' . $lat . '&lon=' . $long . '&appid='. $apiKey);
    $body = '';

    if ( is_array( $response ) && ! is_wp_error( $response ) ) {
        $weather = $response['body']; // use the content
    }

    // var_dump($weather);

    return $weather;
}


function add_weather_div() {
    $userIp = get_user_ip();

    $location = getLocationFromIp($userIp);

    var_dump($location);

    $weather = getWeather($location->latitude, $location->longitude);
    ?>

        <script>
            try {
                document.addEventListener("DOMContentLoaded", function(event) {
                    var body = document.getElementsByTagName('body')[0];
                    var weatherPluginDiv = document.createElement('div');

                    weatherPluginDiv.dataset['weather'] = '<?php echo $weather; ?>';

                    body.appendChild(weatherPluginDiv);
                });
            } catch (error) {
                console.error(error);
            }
        </script>

    <?php
}

add_action('wp_footer','add_weather_div');