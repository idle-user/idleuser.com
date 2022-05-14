setInterval(countdown, 1000);

function countdown() {
    var countdown_elements = $("text[name='countdown']");
    for (i = 0; i < countdown_elements.length; i++) {
        $current_value = countdown_elements[i].getAttribute('value');
        $current_text = countdown_elements[i].textContent;
        $new_value = $current_value - 1;
        $new_text = formatTime(Math.abs($new_value));
        $expired = $new_value < 0;

        if ($expired)
            $new_text = "Ended " + $new_text + " ago";
        else if ($new_value == 0)
            $new_text = "Just ended";
        else
            $new_text = $new_text + " left";

        if ($current_text != $new_text) {
            countdown_elements[i].textContent = $new_text;
        }
        countdown_elements[i].textContent = $new_text;
        countdown_elements[i].setAttribute('value', $new_value);
    }
}

function formatTime(seconds) {
    var d = Math.floor(seconds / 86400);
    var h = Math.floor(seconds / 3600) % 24;
    var m = Math.floor(seconds / 60) % 60;
    var s = seconds % 60;
    if (d > 0) {
        if (d == 1)
            return d + ' day';
        return d + ' days';
    }
    if (h > 0) {
        if (h == 1)
            return h + ' hour';
        return h + ' hours';
    }
    if (m > 0) {
        if (m == 1)
            return m + ' minute';
        return m + ' minutes';
    }
    if (s > 0) {
        if (s == 1)
            return s + ' second';
        return s + ' seconds';
    }
    return 0;
    //if (h < 10) h = "0" + h;
    //if (m < 10) m = "0" + m;
    //if (s < 10) s = "0" + s;
    //return h + ":" + m + ":" + s;
}