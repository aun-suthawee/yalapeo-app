/**
 * Academic Results JavaScript
 * Handle academic results form interactions and validations
 */

(function($) {
    'use strict';

    // Convert CE year to BE year
    function toBuddhistYear(ceYear) {
        return parseInt(ceYear) + 543;
    }

    // Convert BE year to CE year
    function toChristianYear(beYear) {
        return parseInt(beYear) - 543;
    }

    // Format year display to Buddhist Era
    function formatYearDisplay() {
        // Convert year selects
        $('select[name="year"]').each(function() {
            const $select = $(this);
            const originalYear = parseInt($select.val());
            
            // Update option text to show BE year
            $select.find('option').each(function() {
                const ceYear = parseInt($(this).val());
                const beYear = toBuddhistYear(ceYear);
                $(this).text(beYear);
            });
        });

        // Convert year display in table
        $('.year-display').each(function() {
            const ceYear = parseInt($(this).data('year'));
            if (ceYear) {
                const beYear = toBuddhistYear(ceYear);
                $(this).text(beYear);
            }
        });

        // Convert year in page header
        $('.current-year').each(function() {
            const ceYear = parseInt($(this).data('year'));
            if (ceYear) {
                const beYear = toBuddhistYear(ceYear);
                $(this).text(beYear);
            }
        });
    }

    // Score input validation
    function setupScoreValidation() {
        $('input[type="number"][name*="score"]').on('input', function() {
            const value = parseFloat($(this).val());
            const $input = $(this);
            const $feedback = $input.siblings('.invalid-feedback');

            // Remove existing error state
            $input.removeClass('is-invalid');
            if ($feedback.length === 0) {
                $input.after('<div class="invalid-feedback"></div>');
            }

            // Validate range
            if (value < 0) {
                $input.addClass('is-invalid');
                $input.siblings('.invalid-feedback').text('คะแนนต้องไม่น้อยกว่า 0');
            } else if (value > 100) {
                $input.addClass('is-invalid');
                $input.siblings('.invalid-feedback').text('คะแนนต้องไม่เกิน 100');
            } else {
                $input.removeClass('is-invalid');
            }

            // Calculate average if tab has multiple scores
            calculateTabAverage($(this).closest('.tab-pane'));
        });
    }

    // Calculate average score for a tab
    function calculateTabAverage($tab) {
        const scores = [];
        
        $tab.find('input[type="number"][name*="score"]').each(function() {
            const value = parseFloat($(this).val());
            if (!isNaN(value) && value >= 0 && value <= 100) {
                scores.push(value);
            }
        });

        // Update or create average display
        let $averageAlert = $tab.find('.average-alert');
        
        if (scores.length > 0) {
            const average = scores.reduce((a, b) => a + b, 0) / scores.length;
            const averageText = average.toFixed(2);

            if ($averageAlert.length === 0) {
                $averageAlert = $('<div class="alert average-alert mt-3"></div>');
                $tab.append($averageAlert);
            }

            $averageAlert.html(`<strong>คะแนนเฉลี่ย:</strong> ${averageText} คะแนน`);
            $averageAlert.show();
        } else {
            $averageAlert.hide();
        }
    }

    // Tab completion indicator
    function updateTabIndicators() {
        $('.nav-tabs .nav-link').each(function() {
            const $tab = $(this);
            const tabId = $tab.attr('data-bs-target');
            const $tabPane = $(tabId);
            
            if ($tabPane.length) {
                let hasData = false;
                
                $tabPane.find('input[type="number"][name*="score"]').each(function() {
                    const value = parseFloat($(this).val());
                    if (!isNaN(value) && value > 0) {
                        hasData = true;
                        return false; // break
                    }
                });

                // Update badge
                let $badge = $tab.find('.badge');
                if (hasData) {
                    if ($badge.length === 0) {
                        $tab.append('<span class="badge bg-success ms-2"><i class="fas fa-check"></i></span>');
                    }
                } else {
                    $badge.remove();
                }
            }
        });
    }

    // Smooth scroll to top when switching tabs
    function setupTabScrolling() {
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
            $('html, body').animate({
                scrollTop: $('.nav-tabs').offset().top - 20
            }, 300);
        });
    }

    // Auto-save draft (optional)
    function setupAutoSave() {
        let autoSaveTimeout;
        
        $('input[type="number"][name*="score"], textarea[name="notes"]').on('input', function() {
            clearTimeout(autoSaveTimeout);
            
            autoSaveTimeout = setTimeout(function() {
                // Show saving indicator
                showSavingIndicator();
            }, 2000);
        });
    }

    function showSavingIndicator() {
        // Optional: Show a "Draft saved" indicator
        console.log('Auto-save triggered');
    }

    // Confirm before delete
    function setupDeleteConfirmation() {
        $('form[action*="destroy"]').on('submit', function(e) {
            const confirmed = confirm('ต้องการลบข้อมูลผลสัมฤทธิ์ทางการศึกษาหรือไม่?\n\nการลบจะไม่สามารถกู้คืนได้');
            if (!confirmed) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Filter change handler
    function setupFilterHandlers() {
        // Auto-submit form when filter changes
        $('select[name="year"], select[name="filter"]').on('change', function() {
            $(this).closest('form').submit();
        });
    }

    // Fade in animation for table rows
    function animateTableRows() {
        $('.schools-table tbody tr').each(function(index) {
            $(this).css({
                'opacity': '0',
                'transform': 'translateY(20px)'
            }).delay(index * 50).animate({
                'opacity': '1'
            }, {
                duration: 300,
                step: function(now) {
                    $(this).css('transform', `translateY(${20 * (1 - now)}px)`);
                }
            });
        });
    }

    // Toast notification (optional)
    function showToast(message, type = 'success') {
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type} border-0" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        $('body').append(toastHtml);
        const $toast = $('.toast').last();
        const toast = new bootstrap.Toast($toast[0]);
        toast.show();
        
        $toast.on('hidden.bs.toast', function() {
            $(this).remove();
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        // Format year displays to Buddhist Era
        formatYearDisplay();

        // Setup form interactions
        if ($('.academic-results-form').length) {
            setupScoreValidation();
            setupTabScrolling();
            setupAutoSave();
            setupDeleteConfirmation();
            
            // Initial calculations
            $('.tab-pane').each(function() {
                calculateTabAverage($(this));
            });
            
            updateTabIndicators();
            
            // Update indicators on input
            $('input[type="number"][name*="score"]').on('input', updateTabIndicators);
        }

        // Setup index page interactions
        if ($('.academic-results-index').length) {
            setupFilterHandlers();
            // animateTableRows(); // Uncomment if you want animation
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert:not(.alert-info)').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    });

})(jQuery);
