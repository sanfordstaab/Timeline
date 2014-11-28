<?php
    function foo($in) {
        $new = str_replace('%id%', '1', $in);
        $new = str_replace('%desc%', 'bar', $new);
        return $new;
    }
    $s = "&lt;option value=\"%id%\"&gt;%desc%&lt;/option&gt;";
    die(foo($s));
?>
