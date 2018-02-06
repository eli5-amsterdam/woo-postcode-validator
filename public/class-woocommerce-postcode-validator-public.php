<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.eli5.io
 * @since      1.0.0
 *
 * @package    Woocommerce_Postcode_Validator
 * @subpackage Woocommerce_Postcode_Validator/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Postcode_Validator
 * @subpackage Woocommerce_Postcode_Validator/public
 * @author     Kishan Chamman <kishan@eli5.io>
 */
class Woocommerce_Postcode_Validator_Public
{
    // The Geodata API URL constants
    const GEODATA_SUGGESTION_URL = 'https://geodata.nationaalgeoregister.nl/locatieserver/v3/suggest';
    const GEODATA_LOOKUP_URL = 'https://geodata.nationaalgeoregister.nl/locatieserver/v3/lookup';

    // The postcode constant
    const GEODATA_TYPE_POSTCODE = 'postcode';

    // The constant for a two second timeout
    const TIMEOUT_TWO_SECONDS = 2;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version     The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woocommerce-postcode-validator-public.css', [], $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woocommerce-postcode-validator-public.js', ['jquery'], $this->version, false);
    }

    /**
     * Get the postcode validation / suggestion call.
     *
     * @since    1.0.0
     */
    public function get_woocommerce_postcode_validation()
    {
        // Only start if an AJAX request
        if (wp_doing_ajax()) {
            $postcode = sanitize_text_field($_GET['postcode']);

            // Empty response as a start state
            $jsonResponse = [];

            // Set up the GET request Suggestion
            $queryString = '?q=' . urlencode($postcode);
            $response = wp_remote_get(self::GEODATA_SUGGESTION_URL . $queryString, ['timeout' => self::TIMEOUT_TWO_SECONDS]);
            if (!is_wp_error($response) && is_array($response)) {
                // Get the response as an array
                $responseArray = json_decode($response['body'], true);

                // Check if the response has data
                if (array_key_exists('response', $responseArray) && array_key_exists('docs', $responseArray['response'])) {
                    // Filter results by type, postcode
                    $postCodeArray = array_values(array_filter($responseArray['response']['docs'], function ($suggestionItem) {
                        return $suggestionItem['type'] === self::GEODATA_TYPE_POSTCODE;
                    }));

                    // If a postcode result exists, do another call to fetch the actual street_name and city_name
                    if (!empty($postCodeArray) && count($postCodeArray) >= 1) {
                        $lookupId = $postCodeArray[0]['id'];

                        // Set up the GET request for the Lookup
                        $queryString = '?id=' . urlencode($lookupId);
                        $response = wp_remote_get(self::GEODATA_LOOKUP_URL . $queryString, ['timeout' => self::TIMEOUT_TWO_SECONDS]);
                        if (!is_wp_error($response) && is_array($response)) {
                            // Get the response as an array
                            $responseArray = json_decode($response['body'], true);

                            // Check if the response has data
                            if (array_key_exists('response', $responseArray) && array_key_exists('docs', $responseArray['response']) && count($responseArray['response']['docs']) >= 1) {
                                $jsonResponse = [
                                    'street_name' => $responseArray['response']['docs'][0]['straatnaam'],
                                    'city_name'   => $responseArray['response']['docs'][0]['woonplaatsnaam'],
                                    'postal_code' => $responseArray['response']['docs'][0]['postcode'],
                                ];
                            }
                        }
                    }
                }
            }

            // Return response data as JSON
            if (!empty($jsonResponse)) {
                wp_send_json_success($jsonResponse, 200);
            } else {
                return wp_send_json_error([], 400);
            }
        }

        exit();
    }

    /**
     * Add the AJAX url to the head to do the call from the frontend.
     *
     * @return void
     */
    public function add_ajax_url()
    {
        echo '<script type="text/javascript">';
        echo 'var woocommerce_postcode_validator_ajax_url = "' . admin_url('admin-ajax.php') . '";';
        echo '</script>';
    }
}
