jQuery(document).ready(function ($) {

    // Function to generate a unique LOID
    function generateLOID() {
        var d = new Date();
        return 'loid-' +
            d.getFullYear() +
            ('0' + (d.getMonth() + 1)).slice(-2) +
            ('0' + d.getDate()).slice(-2) + '-' +
            ('0' + d.getHours()).slice(-2) +
            ('0' + d.getMinutes()).slice(-2) +
            ('0' + d.getSeconds()).slice(-2) +
            d.getMilliseconds() + '-' +
            Math.random().toString(36).substr(2, 4);
    }

    // Update the LOID for a learning objective, if necessary
    function updateLOID($learningObjectiveRow) {
        var loidField = $learningObjectiveRow.find('.acf-field[data-name="loid"] input');
        if (!loidField.val()) {
            loidField.val(generateLOID());
        }
    }

    // Create and update checkboxes
    function updateCheckboxes() {
        $(".lo-checkboxes").remove();
        $('.acf-field[data-name="section_learning_objectives"]').each(function () {
            var $sectionField = $(this);
            var selectedLOIDs = ($sectionField.find('input').val() || '').split(',');
            var checkboxesHtml = '<ol>';

            $('#learning-objectives-jump .acf-repeater .acf-row:not(.acf-clone)').each(function (index) {
                var loid = $(this).find('.acf-field[data-name="loid"] input').val();

                // Check if LOID exists before generating checkbox
                if (loid) {
                    var title = $(this).find('.acf-field[data-name="learning_objective"] input').val() || 'No Title';
                    var isChecked = selectedLOIDs.includes(loid);

                    checkboxesHtml += '<li><label id="' + loid + '"><input type="checkbox" class="section-lo-checkbox" value="' + loid + '"' +
                        (isChecked ? ' checked' : '') + '> ' + title + '</label></li>';
                }
            });

            checkboxesHtml += '</ol>';
            $sectionField.before('<div class="acf-field lo-checkboxes"><div class="acf-label"><label for="">Section Learning Objectives</label></div>' + checkboxesHtml + '</div>');
        });
    }

    // Update the section_learning_objectives field based on selected checkboxes
    function updateSectionLearningObjectives() {
        $('.acf-field[data-name="section_learning_objectives"]').each(function () {
            var selectedLOIDs = [];
            $(this).prev('.lo-checkboxes').find('.section-lo-checkbox:checked').each(function () {
                selectedLOIDs.push($(this).val());
            });
            $(this).find('input').val(selectedLOIDs.join(','));
        });
    }

    // Simplify the event listener to trigger on any interaction with the repeater section
    $(document).on('input', '#learning-objectives-jump .acf-repeater .acf-field[data-name="learning_objective"] input', function () {
        var $row = $(this).closest('.acf-row'); // Get the closest row for the input
        var loidField = $row.find('.acf-field[data-name="loid"] input');

        // Only generate a LOID if the user has typed something and the LOID is missing
        if (!loidField.val() && $(this).val().length > 0) {
            updateLOID($row);
        }
        updateCheckboxes();
    });


    // Event listener for checkbox changes
    $(document).on('change', '.section-lo-checkbox', function () {
        updateSectionLearningObjectives();
    });

    // Initial setup
    updateCheckboxes();

});
