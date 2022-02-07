/*
* jQuery Watermark Plugin v1.0
* http://www.mdameer.com/
*
* Copyright 2012, Mohammad Dameer
*/

(function ($) {
    var WatermarkElement = function (element, options) {
        var settings = $.extend({}, $.fn.watermark.defaults, options);

        // To save focus state for watermark element
        var isFocused = false;

        // Some shortcuts
        var _this = this;
        var $this = $(this);
        var $element = $(element);

        // Clone original element to a new one to use it for watermark operations
        var $temp = $element.clone();
        $temp.insertBefore($element);

        // Change id, name attributes for watermark element, to avoid conflict with postback data
        $temp.attr("id", $temp.attr("id") + "_text");
        $temp.attr("name", $temp.attr("name") + "_text");

        // Hide original element and remove tabIndex for it
        $element.css({ width: 1, height: 1, overflow: "hidden", border: 0, padding: 0, backgroundColor: "transparent", margin: "-18px 0 0 -1px", visibility: "hidden" });
        $element.attr("tabIndex", -1);

        // Do all watermark operations
        this.check = function () {
            // If empty or has the watermark text
            if ($temp.val() == settings.watermarkText || $temp.val() == "") {
                // If type is password and not focused
                if ($temp.attr("type").toLowerCase() == "password" && !isFocused) {
                    // Change watermark element type to "text".
                    // And to do that we must:
                    // 1. Remove it from it's parent.
                    $temp.remove();
                    // 2. Recreate it from the same html code, but change the type attribute.
                    var $wrap = $temp.wrap("<span>").parent();
                    $temp = $($wrap.html().replace(/type=["']?password["']?/i, 'type="text"'));
                    // 3. Add it in the same place.
                    $temp.insertBefore($element);
                    // 4. Reinitialize it's event handlers.
                    _this.initializeHandlers();
                }
                // If type is password for the original element and not password for the watermark element and is focused
                else if ($element.attr("type").toLowerCase() == "password" && $temp.attr("type").toLowerCase() != "password" && isFocused) {
                    // Change watermark element type to "password".
                    // And to do that we must:
                    // 1. Remove it from it's parent.
                    $temp.remove();
                    // 2. Recreate it from the same html code, but change the type attribute.
                    var $wrap = $temp.wrap("<span>").parent();
                    // If there is no type attribute (IE6, IE7,IE8).
                    if ($wrap.html().toLowerCase().search(/type/i) == -1) {
                        $temp = $(document.createElement($wrap.html()));
                        $temp.attr("type", "password");
                        //alert($temp.wrap("<span>").parent().html());
                    }
                    // For all browsers
                    else {
                        $temp = $($wrap.html().replace(/type=["']?text["']?/i, 'type="password"'));
                    }
                    // 3. Add it in the same place.
                    $temp.insertBefore($element);
                    // 4. Set Focus.
                    $temp.focus();
                    $temp.focus();
                    // 5. Reinitialize it's event handlers.
                    _this.initializeHandlers();
                    // 6. Finish the execution for this method.
                    return;
                }
                // If watermark element has focus
                if (isFocused) {
                    // Set watermark element value to empty.
                    $temp.val("");
                    // Set original element value to empty.
                    $element.val("");
                    // Remove the watermark CSS Class
                    $temp.removeClass(settings.watermarkClassName);
                }
                // If watermark element does not has focus
                else {
                    // Set watermark element value to the watermark text.
                    $temp.val(settings.watermarkText);
                    // Set original element value to empty.
                    $element.val("");
                    // Add the watermark CSS Class
                    $temp.addClass(settings.watermarkClassName);
                }
            }
            // If the watermark element not empty
            else {
                // Copy the value to the original element
                $element.val($temp.val());
            }
        };

        // Set focus to watermark element.
        $element.focus(function () {
            $temp.focus();
        });

        // Initialize event handlers on watermark element
        this.initializeHandlers = function () {
            $temp.focus(function () {
                isFocused = true;
                _this.check();
            }).blur(function () {
                isFocused = false;
                _this.check();
            }).change(function () {
                isFocused = true;
                _this.check();
            }).keydown(function () {
                isFocused = true;
                _this.check();
            }).keypress(function () {
                isFocused = true;
                _this.check();
            }).keyup(function () {
                isFocused = true;
                _this.check();
            });
        };

        // Get or Set the value
        this.val = function (value) {
            if (value || value == "") {
                $temp.val(value);
                $temp.removeClass(settings.watermarkClassName);
                _this.check();
            }
            value = $element.val();
            return value;
        };

        // Get watermark element
        this.getWatermarkElement = function () {
            return $temp;
        };

        // Get original element
        this.getElement = function () {
            return $element;
        };

        // Get or Set watermark text after initializtion
        this.watermarkText = function (value) {
            if (value) {
                if ($temp.val() == settings.watermarkText) {
                    $temp.val("");
                }
                settings.watermarkText = value;
                $temp.removeClass(settings.watermarkClassName);
                _this.check();
            }
            value = settings.watermarkText;
            return value;
        };

        // Get or Set watermark Css Class after initializtion
        this.watermarkClassName = function (value) {
            if (value) {
                $temp.removeClass(settings.watermarkClassName);
                settings.watermarkClassName = value;
                _this.check();
            }
            value = settings.watermarkClassName;
            return value;
        };

        this.initializeHandlers();
        this.check();
    };

    // Create watermark object with it's options
    $.fn.watermark = function (options) {
        return this.filter('input[type=text],input[type=password],input[type=search],input:not([type]),textarea').each(function (key, value) {
            if ($(this).data('watermarkElement'))
                return $(this).data('watermarkElement');
            var watermarkElement = new WatermarkElement(this, options);
            $(this).data('watermarkElement', watermarkElement);
        });
    };

    // default options
    $.fn.watermark.defaults = {
        watermarkText: "",
        watermarkClassName: "watermark"
    };
})(jQuery);