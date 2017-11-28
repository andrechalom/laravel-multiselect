$(document).ready(function(){
    /** For use in multiple selectors. */
    // Original: http://odyniec.net/articles/multiple-select-fields/
    // The elements are tied by the NAME, CLASS AND ID attributes, use them as
    // span: ID specialist-span
    // select: ID specialist-ms CLASS .multiselect
    // inputs: ID specialist[] NAME specialist[] CLASS .multiselector
    $("select.multiselect").change(function()
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
    // chainable custom autocomplete with some global defaults
    $.fn.lmsAutocomplete = function(url, params) {
        // forces the tied-together names (input, span, hidden inputs)
        var name = $(this).attr('id');
        name = name.substring(0, name.length-3);
        // provides some default parameters
        var autocomplete_params = {
            serviceUrl: url,
            onSelect: function (suggestion) {
                var $span = $("#" + name + "-span");
                if ( $(this).val() === "") {
                    return;
                }
                if ($span.find('input[value=' + suggestion.data + ']').length == 0) {
                    $span.append('<span class="multiselector" onclick="$(this).remove();">' +
                        '<input type="hidden" name="' + name + '[]" value="' +
                        suggestion.data + '" /> ' +
                        suggestion.value + '</span>');
                }
            },
            minChars: 3,
            onSearchStart: function() {
                $(".minispinner").remove();
                $(this).after("<div class='spinner minispinner'></div>");
            },
            onSearchComplete: function() {
                $(".minispinner").remove();
            }
        };
        // Merges the received params
        if (typeof params === "object") {
            // this is probably intended:
            if (typeof params.noSuggestionNotice !== "undefined") {
                params.showNoSuggestionNotice = true;
            }
            autocomplete_params = Object.assign(autocomplete_params, params);
        }
        if (!$.fn.devbridgeAutocomplete) {
            throw 'Library laravel-multiselect is using autocomplete, but devbridgeAutocomplete is not available!';
        }
        return this.devbridgeAutocomplete(autocomplete_params);
    };
});
