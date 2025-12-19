<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRASICAL_Calculator_Frontend {

    public function __construct() {

        // Shortcode
        add_shortcode( 'bra_size_calculator', [ $this, 'brasical_render_calculator' ] );

        // Frontend assets
        add_action( 'wp_enqueue_scripts', [ $this, 'brasical_enqueue_assets' ] );

        // Dynamic CSS from admin settings
        add_action( 'wp_head', [ $this, 'brasical_dynamic_styles' ] );
    }

    /**
     * Enqueue frontend CSS & JS
     */
    public function brasical_enqueue_assets() {

        wp_enqueue_style(
            'bscp-bra-calculator-style',
            BRASICAL_CALC_URL . 'assets/calculator.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'bscp-bra-calculator-script',
            BRASICAL_CALC_URL . 'assets/calculator.js',
            [ 'jquery' ],
            '1.0.0',
            true
        );
    }

    /**
     * Render Calculator Shortcode
     */
    public function brasical_render_calculator() {
        ob_start();
        ?>
        <div class="bra-calculator bscp-bra-calculator" id="braCalculator">

            <h2><?php esc_html_e( 'Bra Size Calculator', 'brasical-bra-size-calculator' ); ?></h2>

            <p class="bra-subtitle bscp-bra-subtitle">
                <?php esc_html_e( 'Find your perfect bra size with accurate measurements', 'brasical-bra-size-calculator' ); ?>
            </p>

            <!-- Unit Toggle -->
            <div class="unit-toggle bscp-unit-toggle">
                <button type="button" class="active" data-unit="in">Inch</button>
                <button type="button" data-unit="cm">Cm</button>
            </div>

            <!-- Fields -->
            <div class="bscp-fields">
                <div class="bscp-field">
                    <label for="underbust"><?php esc_html_e( 'Underbust', 'brasical-bra-size-calculator' ); ?></label>
                    <input
                        type="number"
                        step="0.1"
                        id="underbust"
                        placeholder="34"
                    />
                    <small><?php esc_html_e( 'Measure underneath your bust with a measurement tape.', 'brasical-bra-size-calculator' ); ?></small>
                </div>

                <div class="bscp-field">
                    <label for="overbust"><?php esc_html_e( 'Overbust', 'brasical-bra-size-calculator' ); ?></label>
                    <input
                        type="number"
                        step="0.1"
                        id="overbust"
                        placeholder="41"
                    />
                    <small><?php esc_html_e( 'Take the measurement without wearing a heavy or padded bra.', 'brasical-bra-size-calculator' ); ?></small>
                </div>
            </div>

            <!-- Calculate Button -->
            <button
                type="button"
                id="bscp_calculate"
                class="bscp-bra-calc-btn"
            >
                <?php esc_html_e( 'Calculate Size', 'brasical-bra-size-calculator' ); ?>
            </button>

            <!-- Result -->
            <div class="bscp-result" style="display:none;">
                <h3><?php esc_html_e( 'Your Perfect Bra Size', 'brasical-bra-size-calculator' ); ?></h3>
                <p id="bra-bscp-result"></p>
            </div>

        </div>

        <!-- Error Modal -->
        <div id="braErrorModal" class="bra-modal bscp-bra-modal">
            <div class="bra-modal-content bscp-bra-modal-content">
               <h3>⚠️ <?php esc_html_e( 'Invalid Measurement', 'brasical-bra-size-calculator' ); ?></h3>
                <p>
                    <?php esc_html_e( 'The given measurements are not sufficient to determine an accurate bra size.', 'brasical-bra-size-calculator' ); ?><br>
                    <?php esc_html_e( 'Please take the measurements correctly and try again.', 'brasical-bra-size-calculator' ); ?>
                </p>
                <button type="button" onclick="closeBraModal()">
                    <?php esc_html_e( 'Okay', 'brasical-bra-size-calculator' ); ?>
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Dynamic styles from admin color picker (PREFIXED + SCOPED)
     */
    public function brasical_dynamic_styles() {

        // NOTE: option names MUST match admin file (prefixed)
        $bg       = get_option( 'bscp_btn_bg', '#2db4c8' );
        $bg_hover = get_option( 'bscp_btn_bg_hover', '#249fb1' );
        $text     = get_option( 'bscp_btn_text', '#ffffff' );
        $text_h   = get_option( 'bscp_btn_text_hover', '#ffffff' );
        ?>
        <style>
            /* Calculator buttons */
            #braCalculator .bscp-bra-calc-btn,
            #braCalculator .bscp-unit-toggle button.active {
                background-color: <?php echo esc_attr( $bg ); ?>;
                color: <?php echo esc_attr( $text ); ?>;
            }

            #braCalculator .bscp-bra-calc-btn:hover,
            #braCalculator .bscp-unit-toggle button.active:hover {
                background-color: <?php echo esc_attr( $bg_hover ); ?>;
                color: <?php echo esc_attr( $text_h ); ?>;
            }

            /* Modal button */
            #braErrorModal .bscp-bra-modal-content button {
                background-color: <?php echo esc_attr( $bg ); ?>;
                color: <?php echo esc_attr( $text ); ?>;
            }

            #braErrorModal .bscp-bra-modal-content button:hover {
                background-color: <?php echo esc_attr( $bg_hover ); ?>;
                color: <?php echo esc_attr( $text_h ); ?>;
            }
        </style>
        <?php
    }
}

new BRASICAL_Calculator_Frontend();
