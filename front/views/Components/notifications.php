[CSS[
    .notification {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 0 0 1vh 0;
        padding: 1vw;

        width: 100%;
        height: auto;

        color: #FFF;
        background: #0A0;
    }
]CSS]

    <div class="messages">
<?php
    foreach ($this->_data[MESSAGES_NOTIFY] as $msg)
    {
        echo "<div class=\"notification\">$msg</div>";
    }
?>
    </div>
