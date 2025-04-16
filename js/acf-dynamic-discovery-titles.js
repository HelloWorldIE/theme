(function ($) {
    // Function to update the layout title
    function updateLayoutTitle($layout) {
        // Find the title elements for topic and subtopic
        var $topicTitleField = $layout.find('[data-name="topic_title"] input');
        var $subtopicTitleField = $layout.find('[data-name="subtopic_title"] input');

        // Update the layout title for topics
        if ($topicTitleField.length && $topicTitleField.val()) {
            $layout.find('.acf-fc-layout-handle').html('<strong>' + $topicTitleField.val() + '</strong>');
        }

        // Update the layout title for subtopics
        if ($subtopicTitleField.length && $subtopicTitleField.val()) {
            $layout.find('.acf-fc-layout-handle').html('<strong>' + $subtopicTitleField.val() + '</strong>');
        }
    }

    // Update all layout titles on page load, including collapsed layouts
    $(document).ready(function () {
        $('.acf-flexible-content .layout').each(function () {
            updateLayoutTitle($(this));
        });
    });

    // When a new flexible content layout is added, update its title
    if (typeof acf !== 'undefined') {
        acf.addAction('append', function ($el) {
            if ($el.hasClass('layout')) {
                updateLayoutTitle($el);
            }
        });

        // Update layout titles when any input field changes within the layout
        acf.addAction('change', function ($input) {
            var $layout = $input.closest('.layout');
            if ($layout.length) {
                updateLayoutTitle($layout);
            }
        });

        // Update layout titles when a layout is expanded or collapsed
        acf.addAction('show_field', function ($el) {
            var $layout = $el.closest('.layout');
            if ($layout.length) {
                updateLayoutTitle($layout);
            }
        });

        acf.addAction('hide_field', function ($el) {
            var $layout = $el.closest('.layout');
            if ($layout.length) {
                updateLayoutTitle($layout);
            }
        });
    }
})(jQuery);
