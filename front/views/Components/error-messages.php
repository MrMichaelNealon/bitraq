[CSS[
    .error-message {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 0 0 1vh 0;
        padding: 1vw;

        width: 100%;
        height: auto;

        color: #FFF;
        background: #A00;
    }
]CSS]

    <div class="messages">
<?php
    foreach ($this->_data[MESSAGES_ERROR] as $msg)
    {
        echo "<div class=\"error-message\">$msg</div>";
    }
?>
    </div>
