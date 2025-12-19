<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRASICAL_Calculator_Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'brasical_menu' ] );
        add_action( 'admin_init', [ $this, 'brasical_register_settings' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'brasical_assets' ] );
    }

    /**
     * Admin menu
     */
    public function brasical_menu() {

        add_menu_page(
            esc_html__( 'Bra Size Calculator', 'brasical-bra-size-calculator' ),
            esc_html__( 'Bra Calculator', 'brasical-bra-size-calculator' ),
            'manage_options',
            'brasical-bra-size-calculator',
            [ $this, 'brasical_page' ],
            'dashicons-universal-access',
            58
        );
    }

    /**
     * Register settings
     */
    public function brasical_register_settings() {

        $fields = [
            'bscp_btn_bg',
            'bscp_btn_bg_hover',
            'bscp_btn_text',
            'bscp_btn_text_hover',
        ];

        foreach ( $fields as $field ) {
            register_setting(
                'bscp_bra_calc_settings',
                $field,
                [
                    'sanitize_callback' => 'sanitize_hex_color',
                ]
            );
        }
    }

    /**
     * Admin assets (Color Picker)
     */
    public function brasical_assets( $hook ) {

        if ( $hook !== 'toplevel_page-bra-size-calculator' ) {
            return;
        }

        /* =========================
         * WordPress Color Picker
         * ========================= */
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' ); // <-- THIS WAS MISSING

        /* =========================
         * Admin JS
         * ========================= */
        wp_enqueue_script(
            'bscp-bra-admin-js',
            BRASICAL_CALC_URL . 'assets/admin.js',
            [ 'jquery', 'wp-color-picker' ],
            '1.0.0',
            true
        );
    }

    /**
     * Admin page output
     */
    public function brasical_page() {
        ?>
        <div class="wrap bscp-admin-wrap">

            <h1><?php esc_html_e( 'Bra Size Calculator', 'brasical-bra-size-calculator' ); ?></h1>

            <!-- Shortcode -->
            <div class="bscp-admin-section">
                <h2><?php esc_html_e( 'Shortcode', 'brasical-bra-size-calculator' ); ?></h2>

                <input
                    type="text"
                    class="regular-text bscp-shortcode-input"
                    readonly
                    value="[bra_size_calculator]"
                    onclick="this.select();"
                />
            </div>

            <hr>

            <!-- Button Color Settings -->
            <div class="bscp-admin-section">
                <h2><?php esc_html_e( 'Button Color Settings', 'brasical-bra-size-calculator' ); ?></h2>

                <form method="post" action="options.php">
                    <?php settings_fields( 'bscp_bra_calc_settings' ); ?>

                    <table class="form-table bscp-settings-table">

                        <tr>
                            <th scope="row"><?php esc_html_e( 'Button Background', 'brasical-bra-size-calculator' ); ?></th>
                            <td>
                                <input type="text"
                                       name="bscp_btn_bg"
                                       value="<?php echo esc_attr( get_option( 'bscp_btn_bg', '#2db4c8' ) ); ?>"
                                       class="bscp-color-picker" />
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php esc_html_e( 'Button Hover Background', 'brasical-bra-size-calculator' ); ?></th>
                            <td>
                                <input type="text"
                                       name="bscp_btn_bg_hover"
                                       value="<?php echo esc_attr( get_option( 'bscp_btn_bg_hover', '#249fb1' ) ); ?>"
                                       class="bscp-color-picker" />
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php esc_html_e( 'Button Text Color', 'brasical-bra-size-calculator' ); ?></th>
                            <td>
                                <input type="text"
                                       name="bscp_btn_text"
                                       value="<?php echo esc_attr( get_option( 'bscp_btn_text', '#ffffff' ) ); ?>"
                                       class="bscp-color-picker" />
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php esc_html_e( 'Button Text Hover Color', 'brasical-bra-size-calculator' ); ?></th>
                            <td>
                                <input type="text"
                                       name="bscp_btn_text_hover"
                                       value="<?php echo esc_attr( get_option( 'bscp_btn_text_hover', '#ffffff' ) ); ?>"
                                       class="bscp-color-picker" />
                            </td>
                        </tr>

                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
        </div>
        <?php
    }
}

new BRASICAL_Calculator_Admin();
