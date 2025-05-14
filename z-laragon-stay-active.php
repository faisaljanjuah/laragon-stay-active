<?php
/**
 * Plugin Name: Z Laragon Stay Active
 * Description: Prevent Laragon from sleeping by pinging the server periodically.
 * Version: 1.0
 * Author: Faisal K
 */

if (!defined('ABSPATH')) exit; // Prevent direct access

// Register the AJAX handler
add_action('wp_ajax_z_laragon_stay_active', function () {
    wp_send_json_success('pong'); // Minimal response
});

// Enqueue the ping script in both frontend and admin footer
function lsa_enqueue_ping_script() {
    ?>
    <script>
        (function keepLaragonAwake() {
            setInterval(function () {
                fetch('<?php echo admin_url('admin-ajax.php?action=z_laragon_stay_active'); ?>&lsa_ping=' + new Date().getTime())
                    .then(response => {
                        if (!response.ok) {
                            console.warn('Laragon ping failed');
                        }
                    })
                    .catch(err => {
                        console.error('Laragon ping error:', err);
                    });
            }, 60000); // Every 60 seconds
        })();
    </script>
    <?php
}
add_action('wp_footer', 'lsa_enqueue_ping_script');      // Frontend
add_action('admin_footer', 'lsa_enqueue_ping_script');   // Backend
