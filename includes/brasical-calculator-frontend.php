<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BRASICAL_Calculator_Frontend {

    public function __construct() {
        // Shortcode
        add_shortcode( 'brasical_size_calculator', [ $this, 'brasical_render_calculator' ] );

        // Frontend assets - ONE HOOK
        add_action( 'wp_enqueue_scripts', [ $this, 'brasical_enqueue_assets' ] );
        
        // REMOVE THIS LINE: add_action( 'wp_head', [ $this, 'brasical_dynamic_styles' ] );
    }

    /**
     * Enqueue frontend CSS & JS
     */
    public function brasical_enqueue_assets() {
        
        // Enqueue main CSS file
        wp_enqueue_style(
            'bscp-calculator-style', // ← FIXED: Consistent handle
            BRASICAL_CALC_URL . 'assets/calculator.css',
            [],
            '1.0.0'
        );

        // Add dynamic colors as inline style
        $this->brasical_dynamic_styles();

        // Enqueue JS
        wp_enqueue_script(
            'bscp-bra-calculator-script',
            BRASICAL_CALC_URL . 'assets/calculator.js',
            [ 'jquery' ],
            '1.0.0',
            true
        );
    }

    /**
     * Dynamic styles - NO <style> TAGS!
     */
    public function brasical_dynamic_styles() {
        // Get color options
        $brasical_btn_bg       = get_option( 'bscp_btn_bg', '#2db4c8' );
        $brasical_btn_bg_hover = get_option( 'bscp_btn_bg_hover', '#249fb1' );
        $brasical_btn_text     = get_option( 'bscp_btn_text', '#ffffff' );
        $brasical_btn_text_hover   = get_option( 'bscp_btn_text_hover', '#ffffff' );
        
        // Create CSS string WITHOUT <style> tags
        $brasical_custom_css = "
            /* Calculator buttons */
            #braCalculator .bscp-bra-calc-btn,
            #braCalculator .bscp-unit-toggle button.active {
                background-color: {$brasical_btn_bg};
                color: {$brasical_btn_text};
            }

            #braCalculator .bscp-bra-calc-btn:hover,
            #braCalculator .bscp-unit-toggle button.active:hover {
                background-color: {$brasical_btn_bg_hover};
                color: {$brasical_btn_text_hover};
            }

            /* Modal button */
            #braErrorModal .bscp-bra-modal-content button {
                background-color: {$brasical_btn_bg};
                color: {$brasical_btn_text};
            }

            #braErrorModal .bscp-bra-modal-content button:hover {
                background-color: {$brasical_btn_bg_hover};
                color: {$brasical_btn_text_hover};
            }
        ";
        
        // Add inline style PROPERLY - handle must match!
        wp_add_inline_style( 'bscp-calculator-style', $brasical_custom_css );
    }

    /**
     * Render Calculator Shortcode (NO CHANGES NEEDED HERE)
     */
    public function brasical_render_calculator() {
        ob_start();
        ?>
        <div class="bra-calculator bscp-bra-calculator" id="braCalculator">

            <h2><?php esc_html_e( 'Bra Size Calculator', 'bra-size-calculator' ); ?></h2>

            <p class="bra-subtitle bscp-bra-subtitle">
                <?php esc_html_e( 'Find your perfect bra size with accurate measurements', 'bra-size-calculator' ); ?>
            </p>

            <!-- Unit Toggle -->
            <div class="unit-toggle bscp-unit-toggle">
                <button type="button" class="active" data-unit="in">Inch</button>
                <button type="button" data-unit="cm">Cm</button>
            </div>

            <!-- Fields -->
            <div class="bscp-fields">
                <div class="bscp-field">
                    <label for="underbust"><?php esc_html_e( 'Underbust', 'bra-size-calculator' ); ?></label>
                    <input
                        type="number"
                        step="0.1"
                        id="underbust"
                        placeholder="34"
                    />
                    <small><?php esc_html_e( 'Measure underneath your bust with a measurement tape.', 'bra-size-calculator' ); ?></small>
                </div>

                <div class="bscp-field">
                    <label for="overbust"><?php esc_html_e( 'Overbust', 'bra-size-calculator' ); ?></label>
                    <input
                        type="number"
                        step="0.1"
                        id="overbust"
                        placeholder="41"
                    />
                    <small><?php esc_html_e( 'Take the measurement without wearing a heavy or padded bra.', 'bra-size-calculator' ); ?></small>
                </div>
            </div>

            <!-- Calculate Button -->
            <button
                type="button"
                id="bscp_calculate"
                class="bscp-bra-calc-btn"
            >
                <?php esc_html_e( 'Calculate Size', 'bra-size-calculator' ); ?>
            </button>

            <!-- Result -->
            <div class="bscp-result" style="display:none;">
                <h3><?php esc_html_e( 'Your Perfect Bra Size', 'bra-size-calculator' ); ?></h3>
                <p id="bra-bscp-result"></p>
            </div>

        </div>

        <!-- Error Modal -->
        <div id="braErrorModal" class="bra-modal bscp-bra-modal">
            <div class="bra-modal-content bscp-bra-modal-content">
               <h3>⚠️ <?php esc_html_e( 'Invalid Measurement', 'bra-size-calculator' ); ?></h3>
                <p>
                    <?php esc_html_e( 'The given measurements are not sufficient to determine an accurate bra size.', 'bra-size-calculator' ); ?><br>
                    <?php esc_html_e( 'Please take the measurements correctly and try again.', 'bra-size-calculator' ); ?>
                </p>
                <button type="button" onclick="closeBraModal()">
                    <?php esc_html_e( 'Okay', 'bra-size-calculator' ); ?>
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

new BRASICAL_Calculator_Frontend();