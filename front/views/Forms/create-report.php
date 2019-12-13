<?php
    if (\App\Models\Session::loggedIn() === false)
    {
        header("Location: /");
        exit;
    }

    if (! isset($_SESSION['report-title']))
        $_SESSION['report-title'] = '';
    if (! isset($_SESSION['report-body']))
        $_SESSION['report-body'] = '';
    if (! isset($_SESSION['report-replicate']))
        $_SESSION['report-replicate'] = '';
    if (! isset($_SESSION['report-notes']))
        $_SESSION['report-notes'] = '';
?>


[CSS[
    #report-form
    {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 1.5vw;
        padding: 5vh 2.5vh;

        width: 96vw;
        height: auto;

        background: #FFF;
    }

    .div-form-half {
        position: relative;
        float: left;

        padding: 1vh 0;

        box-sizing: border-box;

        width: 100%;
        height: auto;
    }

    #report-form input[type="text"] {
        text-align: left;
    }

    #report-form textarea {
        margin-bottom: 1.5vh;
    }

        .div-form-half:nth-child(odd) {
            padding: 1vh 0 1vh 1.5vw;
        }

        .div-form-half:nth-child(even) {
            padding: 1vh 1.5vw 1vh 0;
        }


    .div-form-half input[type=text] {
        margin-bottom: 3vh;
        text-align: left;
        width: 100%
    }

    #report-form p {
        position: relative;
        float: left;

        width: 100%;
        height: auto;

        margin: 0vh 0vh 2.5vh 0;
    }

    @media only screen and (min-width: 640px) {
        .div-form-half {
            width: 50%;
        }
    }

]CSS]

<?php if (isset($_SESSION['edit-report'])) { ?>
    <h2>Edit report</h2>
    <form id="report-form" method="POST" action="/edit-report/<?php echo $_SESSION[SESSION_USER_NAME] . '/' . $this->_data['report-project'] . '/' . $this->_data['project-id'] . '/' . $this->_data['report-id']; ?>">
<?php } else { ?> 
    <h2>Create report</h2>
    <form id="report-form" method="POST" action="/submit-report/<?php echo $this->_data['report-id']; ?>">
<?php } ?>

        <?php echo "<p><b>Project: <font style='color: #FF8C00'>" . str_replace('-', '&nbsp;', $this->_data['report-project']) . "</b></font> - <font style='color: #0A0;'><i>" . date('d/m/Y H:i:s') . "</i></font></p>"; ?>

        @partial ../Components/error-messages.php
        @partial ../Components/notifications.php

        <label for="report-title">Title</label>
        <input type="text" name="report-title" required value="<?php echo $_SESSION['report-title']; ?>">
        
        <label for="report-class">Report Class</label>
        <select name="report-status" id="report-class">
            <option id="report-Public" value="Public">Public</option>
            <option id="report-Protected" value="Protected">Protected</option>
            <option id="report-Private" value="Private">Private</option>
        </select>

        <label for="report-body">Bug report</label>
        <textarea id="report-body" name="report-body" required style="width: 100%; height: 25vh;"><?php echo $_SESSION['report-body']; ?></textarea>
        
        <label for="report-replicate">Replicate bug</label>
        <textarea id="report-replicate" name="report-replicate" required style="width: 100%; height: 25vh;"><?php echo $_SESSION['report-replicate']; ?></textarea>
        
        <label for="report-notes">Additional Notes</label>
        <textarea id="report-notes" name="report-notes" style="width: 100%; height: 25vh;"><?php echo $_SESSION['report-notes']; ?></textarea>

        <input id="submit-report-button" type="submit" style="visibility: hidden">
    </form>

    @partial ../Components/add-report-buttons.php

    <script>

CKEDITOR.replace('report-body');
CKEDITOR.replace('report-replicate');
CKEDITOR.replace('report-notes');

    <?php
if (! isset($_SESSION['report-status']))
    $_SESSION['report-status'] = 'Public';

    echo '
        var __report_status = "' . $_SESSION['report-status'] . '";
    ';
    ?>
    
        $(document).ready(function() {
            $("#submit-create-report-form").on("click", function() {
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }

                $("#submit-report-button").trigger("click");
            });

            if (__report_status === "Public")
                $("#report-Public").attr("selected", "selected");    
            if (__report_status === "Protected")
                $("#report-Protected").attr("selected", "selected");     
            if (__report_status === "Private")
                $("#report-Private").attr("selected", "selected");
        });

    </script>
