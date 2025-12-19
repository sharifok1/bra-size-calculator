/**
 * Bra Size Calculator
 * Rules based on provided Bangla calculation method
 * Supports inch & cm
 */

(function ($) {

    "use strict";

    /**
     * Convert CM to Inch
     */
    function cmToInch(cm) {
        return cm / 2.54;
    }

    /**
     * Show modal (global so inline onclick can call close function)
     */
    window.showInvalidInputModal = function () {
        $('#braErrorModal').css('display', 'flex');
    };

    window.closeBraModal = function () {
        $('#braErrorModal').hide();
    };

    /**
     * Optional: close modal when clicking outside content
     */
    $(document).on('click', '#braErrorModal', function (e) {
        if ($(e.target).is('#braErrorModal')) {
            window.closeBraModal();
        }
    });

    /**
     * Calculate Band Size (Bangla Rule)
     */
    function calculateBandSize(underbust) {
        let band;

        if (underbust % 2 === 0) {
            band = underbust + 4;
        } else {
            band = underbust + 5;
        }

        // Enforce band size range
        if (band < 28 || band > 44) {
            return null;
        }

        return band;
    }

    /**
     * Get Cup Size (Bust - Band)
     */
    function getCupSize(bust, band) {
        const diff = bust - band;

        // Custom negative handling
        if (diff === 0 || diff === -1) {
            return 'AA';
        }

        if (diff === -2) {
            return 'AAA';
        }

        // Too small / invalid measurement
        if (diff < -2) {
            return 'INVALID';
        }

        const cupMap = {
            1: 'A',
            2: 'B',
            3: 'C',
            4: 'D',
            5: 'DD/E',
            6: 'F',
            7: 'G',
            8: 'H'
        };

        if (diff > 8) {
            return 'INVALID';
        }

        return cupMap[diff] || 'INVALID';
    }

    /**
     * custom round value function
     * <= .5  => ceil
     * >  .5  => floor
     */
    function customRound(value) {
        const decimal = value - Math.floor(value);

        if (decimal <= 0.5) {
            return Math.ceil(value);
        }

        return Math.floor(value);
    }

    /**
     * Main Calculator Function
     */
    function calculateBraSize(underbust, bust, unit) {

        // Input validation
        if (
            underbust === '' ||
            bust === '' ||
            isNaN(underbust) ||
            isNaN(bust)
        ) {
            // ❌ No modal for empty input
            return {
                success: false,
                message: 'দয়া করে সঠিক মাপ দিন'
            };
        }

        // Convert cm → inch
        if (unit === 'cm') {
            underbust = cmToInch(Number(underbust));
            bust = cmToInch(Number(bust));
        } else {
            underbust = Number(underbust);
            bust = Number(bust);
        }

        // Band size
        const band = calculateBandSize(customRound(underbust));
        if (!band) {
            window.showInvalidInputModal();
            return { success: false };
        }

        // Cup size
        const cup = getCupSize(customRound(bust), band);
        if (cup === 'INVALID') {
            window.showInvalidInputModal();
            return { success: false };
        }

        return {
            success: true,
            size: band + cup
        };
    }

    /**
     * Unit toggle click (PREFIX FIX)
     */
    $('.bscp-unit-toggle button').on('click', function () {
        $('.bscp-unit-toggle button').removeClass('active');
        $(this).addClass('active');

        // Reset result UI
        $('.bscp-result').hide();
        $('#bra-bscp-result').text('');
    });

    /**
     * Calculate button click (PREFIX FIX)
     */
    $('#bscp_calculate').on('click', function () {

        // Always reset UI first
        $('.bscp-result').hide();
        $('#bra-bscp-result').text('');

        const underbust = Number($('#underbust').val());
        const bust = Number($('#overbust').val());
        const unit = $('.bscp-unit-toggle button.active').data('unit') || 'in';

        const result = calculateBraSize(underbust, bust, unit);

        // Invalid input → modal already shown
        if (!result.success) {
            if (result.message) {
                $('#bra-bscp-result').text(result.message);
                $('.bscp-result').show();
            }
            return;
        }

        // Valid result
        $('#bra-bscp-result').text(result.size);
        $('.bscp-result').show();
    });

})(jQuery);
