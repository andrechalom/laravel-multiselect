$(document).ready(function(){
    /** For use in multiple selectors. */
    // Original: http://odyniec.net/articles/multiple-select-fields/
    // The elements are tied by the NAME, CLASS AND ID attributes, use them as
    // span: ID specialist-span
    // select: ID specialist-ms CLASS .multiselect
    // inputs: ID specialists[] NAME specialist[] CLASS .multiselector
    $(".multiselect").change(function()
        {
            var $name = $(this).attr('id');
            $name = $name.substring(0, $name.length-3);
            var $span = $("#" + $name + "-span");
            if ( $(this).val() === "") {
                return;
            }
            if ($span.find('input[value=' + $(this).val() + ']').length == 0) {
                $span.append('<span class="multiselector" onclick="$(this).remove();">' +
                    '<input type="hidden" name="' + $name + '[]" value="' +
                    $(this).val() + '" /> ' +
                    $(this).find('option:selected').text() + '</span>');
            }
        });
});
