// Function to make the element draggable
function dragV2(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

    // Handle both mouse and touch events
    elmnt.addEventListener("mousedown", dragStart);
    elmnt.addEventListener("touchstart", dragStart);

    function dragStart(e) {
        if (e.type === "mousedown") {
            e.preventDefault();
            // get the mouse cursor position at startup:
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.addEventListener("mouseup", dragEnd);
            document.addEventListener("mousemove", drag);
        } else if (e.type === "touchstart") {
            e.preventDefault();
            // get the touch position at startup:
            var touch = e.touches[0];
            pos3 = touch.clientX;
            pos4 = touch.clientY;
            document.addEventListener("touchend", dragEnd);
            document.addEventListener("touchmove", drag);
        }
    }

    function drag(e) {
        if (e.type === "mousemove") {
            e.preventDefault();
            // calculate the new cursor position:
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
        } else if (e.type === "touchmove") {
            e.preventDefault();
            // calculate the new touch position:
            var touch = e.touches[0];
            pos1 = pos3 - touch.clientX;
            pos2 = pos4 - touch.clientY;
            pos3 = touch.clientX;
            pos4 = touch.clientY;
        }
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function dragEnd() {
        // stop moving when mouse button is released:
        document.removeEventListener("mouseup", dragEnd);
        document.removeEventListener("mousemove", drag);
        // Remove touch event listeners when touch ends
        document.removeEventListener("touchend", dragEnd);
        document.removeEventListener("touchmove", drag);
    }
}