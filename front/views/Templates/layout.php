<!DOCTYPE html>
<html lang="en">
    <style>
[[CSS]]
    </style>
    <title>
        [[APP_TITLE]]
    </title>
    <link rel="Stylesheet" type="text/css" href="[PUBLIC[css/main.css]]">
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/ckeditor/ckeditor.js"></script>
    <script src="js/confirmAction.js"></script>
    <body>

        <div id="wrapper">
            <div id="outer">
                <div id="inner">

{{SECTION_BODY}}

                </div>
            </div>
        </div>

        <div id="overlay">
            &nbsp;
        </div>
        <div id="confirm-modal">
            <div id="confirm-action"></div>
            <div id="confirm-header">
                <div id="confirm-title"></div>
                <div title="Cancel action" id="confirm-close">X</div>
            </div>
            <div id="confirm-question"></div>
            <div title="Confirm action" class="confirm-option" id="confirm-confirm"></div>
            <div title="Cancel action" class="confirm-option" id="confirm-cancel"></div>
        </div>
    </body>
</html>
