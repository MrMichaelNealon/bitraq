<?php

    $_report = $this->_data['report'];

    if (\App\Models\Session::loggedIn() === false)
    {
        \App\Models\Messages::pushMessage(MESSAGES_ERROR, "Permission denied");
        header("Location: /");
        exit;
    }

    if ($this->_data['username'] !== $_SESSION[SESSION_USER_NAME])
    {
        \App\Models\Messages::pushMessage(MESSAGES_ERROR, "Permission denied");
        header("Location: /");
        exit;
    }

    if (! isset($_SESSION['report-reply']))
        $_SESSION['report-reply'] = '';

?>

[CSS[
    #report-reply-form {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 1.5vw;
        padding: .5vw;

        width: 97vw;
        height: 64vh;

        background: #FFF;
    }

        #report-reply {
            position: absolute;

            clear: both;

            width: 100%;
            height: 65vh;

            border: 1px solid #F00;
        }
]CSS]

    <h2>Submit reply for report <em><?php echo $_report['title']; ?></em></h1>

<?php
    if (! isset($_SESSION['edit-report-reply'])) {
?>
    <form id="report-reply-form" method="POST" action="/submit-report-reply/<?php echo $this->_data['username'] . '/' . $_report['project_id'] . '/' . $_report['id']; ?>">
<?php
    } else {
?>
    <form id="report-reply-form" method="POST" action="/edit-report-reply/<?php echo $this->_data['username'] . '/' . $this->_data['reply-id']; ?>">
<?php
    }
?>
        <textarea id="report-reply" name="report-reply" required><?php echo $_SESSION['report-reply']; ?></textarea>

        <input id="submit-solution-form" type="submit" style="display: none;">

    </form>

    <div class="buttons">
<?php
    if (isset($_SESSION['edit-report-reply'])) {
?>
        <a id="submit-solution" href="#">
            Save
        </a>
<?php } else { ?>
        <a id="submit-solution" href="#">
            Submit
        </a>
<?php } ?>

        <a href="/view-project-report/<?php echo $_report['project_id'] . '/' . $_report['id']; ?>">
            Cancel
        </a>
    </div>

    <script>
        var     __edit = <?php if (isset($_SESSION['edit-report-reply'])) { echo '"true"'; } else { echo '"false"'; } ?>

        CKEDITOR.replace('report-reply', {
            position: 'relative',
            float: 'left',
            width: '95vw',
            height: '55vh'
        });

        $(document).ready(function() {
            $("#submit-solution").on("click", function() {
            //    if (__edit === "false")
                $("#submit-solution-form").trigger("click");
            //    else {
            //        alert("EDIT");
            //    }
            });
        });
    </script>
