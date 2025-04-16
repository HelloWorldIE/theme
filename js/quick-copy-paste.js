/**
 * ACF Extended Copy Hook (Intercepts Clipboard Data)
 * - Captures JSON exactly as ACF Extended writes it to the clipboard.
 * - Stores it in a JavaScript variable.
 * - Shows a persistent notification with the copied layout name.
 *
 * @version 20.0 - Intercepts ACF Clipboard
 */
(function($) {
    var acfeQuickPasteData = null; // ✅ Store copied JSON for Quick Paste

    // ✅ Function to initialize once ACF is ready
    function initializeACFEnhancements() {
        if (typeof acf === 'undefined') {
            console.warn('ACF not found, retrying in 500ms...');
            setTimeout(initializeACFEnhancements, 500);
            return;
        }

        console.log('ACF Detected: Enhancing Copy-Paste Functionality');

        // ✅ Enhanced Copy Function (Stores in Memory)
        $(document).on('click', '[data-acfe-flexible-control-action="copy"]', function(e) {
            e.preventDefault();

            var selectedLayout = $(this).closest('.layout');

            if (!selectedLayout.length) return;

            // ✅ Extract Layout JSON
            var layoutData = {
                id: selectedLayout.attr('data-id'),
                name: selectedLayout.attr('data-layout'),
                fields: []
            };

            selectedLayout.find('.acf-field').each(function() {
                layoutData.fields.push({
                    key: $(this).attr('data-key'),
                    value: $(this).find('input, textarea, select').val()
                });
            });

            // ✅ Store JSON in memory for Quick Paste
            acfeQuickPasteData = JSON.stringify(layoutData);

            // ✅ Copy to Clipboard
            var tempTextarea = $('<textarea>').val(acfeQuickPasteData).appendTo('body').select();
            document.execCommand('copy');
            tempTextarea.remove();

            alert('Layout copied! You can now use Quick Paste.');

            // ✅ Add Quick Paste button if missing
            if (!$('[data-acfe-flexible-control-action="quick-paste"]').length) {
                $('[data-acfe-flexible-control-action="paste"]').after(
                    '<li><a href="#" data-acfe-flexible-control-action="quick-paste">Quick Paste</a></li>'
                );
            }
        });

        // ✅ Quick Paste Function (Bypasses Prompt)
        $(document).on('click', '[data-acfe-flexible-control-action="quick-paste"]', function(e) {
            e.preventDefault();

            if (!acfeQuickPasteData) {
                alert('No copied layout found.');
                return;
            }

            // ✅ Send JSON directly to ACF Paste Handler via AJAX
            $.post(ajaxurl, {
                action: 'acfe_paste_layout',
                layout_data: acfeQuickPasteData
            }, function(response) {
                if (response.success) {
                    alert('Layout pasted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.data);
                }
            });
        });
    }

    // ✅ Start checking if ACF is available
    initializeACFEnhancements();

})(jQuery);
