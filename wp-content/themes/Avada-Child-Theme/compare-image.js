window.ProphecyCream = window.ProphecyCream || {};
window.ProphecyCream.ImageCompare = (function(JQuery) {

    jQuery(document).ready(function() {
        alert('go');
        var dragging = false,
            scrolling = false,
            resizing = false;
        //cache jQuery objects
        var imageComparisonContainers = jQuery('.cd-image-container');
        alert(imageComparisonContainers.length)
        //check if the .cd-image-container is in the viewport 
        //if yes, animate it
        checkPosition(imageComparisonContainers);
        jQuery(window).on('scroll', function() {
            if (!scrolling) {
                scrolling = true;
                (!window.requestAnimationFrame)
                    ? setTimeout(function() { checkPosition(imageComparisonContainers); }, 100)
                    : requestAnimationFrame(function() { checkPosition(imageComparisonContainers); });
            }
        });

        //make the .cd-handle element draggable and modify .cd-resize-img width according to its position
        imageComparisonContainers.each(function() {
            var actual = jQuery(this);
            drags(actual.find('.cd-handle'), actual.find('.cd-resize-img'), actual, actual.find('.cd-image-label[data-type="original"]'), actual.find('.cd-image-label[data-type="modified"]'));
        });

        //upadate images label visibility
        jQuery(window).on('resize', function() {
            if (!resizing) {
                resizing = true;
                (!window.requestAnimationFrame)
                    ? setTimeout(function() { checkLabel(imageComparisonContainers); }, 100)
                    : requestAnimationFrame(function() { checkLabel(imageComparisonContainers); });
            }
        });

        function checkPosition(container) {
            container.each(function() {
                var actualContainer = jQuery(this);
                if (jQuery(window).scrollTop() + jQuery(window).height() * 0.5 > actualContainer.offset().top) {
                    actualContainer.addClass('is-visible');
                }
            });

            scrolling = false;
        }

        function checkLabel(container) {
            container.each(function() {
                var actual = jQuery(this);
                updateLabel(actual.find('.cd-image-label[data-type="modified"]'), actual.find('.cd-resize-img'), 'left');
                updateLabel(actual.find('.cd-image-label[data-type="original"]'), actual.find('.cd-resize-img'), 'right');
            });

            resizing = false;
        }

        //draggable funtionality - credits to http://css-tricks.com/snippets/jquery/draggable-without-jquery-ui/
        function drags(dragElement, resizeElement, container, labelContainer, labelResizeElement) {
            dragElement.on("mousedown touchstart", function(e) {
                dragElement.addClass('draggable');
                resizeElement.addClass('resizable');

                var pageX = e.pageX;
                if (typeof e.pageX === 'undefined') {
                    pageX = e.originalEvent.touches[0].pageX;
                }
                var dragWidth = dragElement.outerWidth(),
                    xPosition = dragElement.offset().left + dragWidth - pageX,
                    containerOffset = container.offset().left,
                    containerWidth = container.outerWidth(),
                    minLeft = containerOffset + 10,
                    maxLeft = containerOffset + containerWidth - dragWidth - 10;

                dragElement.parents().on("mousemove touchmove", function(e) {
                    if (!dragging) {
                        dragging = true;
                        (!window.requestAnimationFrame)
                            ? setTimeout(function() { animateDraggedHandle(e, xPosition, dragWidth, minLeft, maxLeft, containerOffset, containerWidth, resizeElement, labelContainer, labelResizeElement); }, 100)
                            : requestAnimationFrame(function() { animateDraggedHandle(e, xPosition, dragWidth, minLeft, maxLeft, containerOffset, containerWidth, resizeElement, labelContainer, labelResizeElement); });
                    }
                }).on("mouseup touchend", function(e) {
                    dragElement.removeClass('draggable');
                    resizeElement.removeClass('resizable');
                });
                e.preventDefault();
            }).on("mouseup touchend", function(e) {
                dragElement.removeClass('draggable');
                resizeElement.removeClass('resizable');
            });
        }

        function animateDraggedHandle(e, xPosition, dragWidth, minLeft, maxLeft, containerOffset, containerWidth, resizeElement, labelContainer, labelResizeElement) {
            var pageX = e.pageX;
            if (typeof e.pageX === 'undefined') {
                pageX = e.originalEvent.touches[0].pageX;
            }
            var leftValue = pageX + xPosition - dragWidth;
            //constrain the draggable element to move inside his container
            if (leftValue < minLeft) {
                leftValue = minLeft;
            } else if (leftValue > maxLeft) {
                leftValue = maxLeft;
            }

            var widthValue = (leftValue + dragWidth / 2 - containerOffset) * 100 / containerWidth + '%';

            jQuery('.draggable').css('left', widthValue).on("mouseup touchend", function() {
                jQuery(this).removeClass('draggable');
                resizeElement.removeClass('resizable');
            });

            jQuery('.resizable').css('width', widthValue);

            updateLabel(labelResizeElement, resizeElement, 'left');
            updateLabel(labelContainer, resizeElement, 'right');
            dragging = false;
        }

        function updateLabel(label, resizeElement, position) {
            if (position == 'left') {
                (label.offset().left + label.outerWidth() < resizeElement.offset().left + resizeElement.outerWidth()) ? label.removeClass('is-hidden') : label.addClass('is-hidden');
            } else {
                (label.offset().left > resizeElement.offset().left + resizeElement.outerWidth()) ? label.removeClass('is-hidden') : label.addClass('is-hidden');
            }
        }
    });
})(jQuery);