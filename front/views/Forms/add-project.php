<?php
    if (\App\Models\Session::loggedIn() === false)
    {
        header("Location: /");
        exit;
    }

    $_projects = new \App\User\ProjectController();
    $_allProjects = $_projects->getAll();

    if (count($_allProjects) >= 5)
    {
        if ($_SESSION[SESSION_ACCT_STATUS] == SESSION_ACCT_BASIC)
        {
            \App\Models\Messages::pushMessage(MESSAGES_ERROR, "Limit of 5 projects for Basic account holders");
            header("Location: /dashboard");
            exit;
        }
    }

    if (! isset($_SESSION['project-name']))
        $_SESSION['project-name'] = '';
    if (! isset($_SESSION['project-repo']))
        $_SESSION['project-repo'] = '';
    if (! isset($_SESSION['project-desc']))
        $_SESSION['project-desc'] = '';

    if (! isset($_SESSION['project-version']))
        $_SESSION['project-version'] = '';
    if (! isset($_SESSION['project-build']))
        $_SESSION['project-build'] = '';
    if (! isset($_SESSION['project-release']))
        $_SESSION['project-release'] = '';

    if (! isset($_SESSION['project-type']))
        $_SESSION['project-type'] = 'CLI-Application';
    if (! isset($_SESSION['project-class']))
        $_SESSION['project-class'] = 'Public';

    if (! isset($_SESSION['project-tags']))
        $_SESSION['project-tags'] = '';
?>

[CSS[
    .div-form-half {
        position: relative;
        float: left;

        padding: 1vh 2.5vh;

        box-sizing: border-box;

        width: 100%;
        height: auto;
    }

    .div-form-half input[type=text] {
        margin-bottom: 3vh;
    }

    #input-string {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 0;
        padding: 0;

        width: auto;

        font-size: 20px;
        font-weight: bold;

        line-height: 5vh;

        color: #0A0;
    }

    #input-complete {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 0;
        padding: 0;

        width: auto;

        font-size: 20px;
        font-weight: bold;

        line-height: 5vh;

        color: #A00;
    }

    @media only screen and (min-width: 640px) {
        .div-form-half {
            width: 50%;
        }
    }

    .div-form-half input[type=text] {
        text-align: left;
    }

    .form-messages {
        position: relative;
        float: right;

        width: 100%;
        height: auto;
    }
]CSS]

<?php
    if (isset($_SESSION['project-edit']) && $_SESSION['project-edit'] === true)
        echo "<h2>Edit Project</h2>";
    else
        echo "<h2>Add Project</h2>";
?>

    <div class="project-manager">
        <div class="form-messages">
            @partial ../Components/error-messages.php
        </div>

<?php if (isset($_SESSION['project-edit']) && $_SESSION['project-edit'] === true) { ?>
        <form method="POST" action="/update-project">
<?php } else { ?>
        <form method="POST" action="/add-project">
<?php } ?>
            <div class="div-form-half">
                <label for="project-name">Project Name</label>
                <input type="text" name="project-name" required value="<?php echo $_SESSION['project-name']; ?>">

                <label for="project-repo">Project Repository</label>
                <input type="text" name="project-repo"value="<?php echo $_SESSION['project-repo']; ?>">

                <label for="project-desc">Project Description</label>
                <textarea type="text" id="project-desc" name="project-desc" style="width: 100%; height: 18.5vh;"><?php echo $_SESSION['project-desc']; ?></textarea>
            </div>

            <div class="div-form-half">
                <label for="project-version">Project Version</label>
                <input type="text" name="project-version" value="<?php echo $_SESSION['project-version']; ?>" style="text-align: center; width: 14vw; margin-right: 2.5vw;" placeholder="Version">
                <input type="text" name="project-build" value="<?php echo $_SESSION['project-build']; ?>" style="text-align: center; width: 13vw; margin-right: 2.5vw;" placeholder="Build">
                <input type="text" name="project-release" value="<?php echo $_SESSION['project-release']; ?>" style="text-align: center; width: 13vw; margin-right: 0vw;" placeholder="Release">

                <label for="project-type">Project Type</label>
                <select name="project-type" id="project-type">
                    <option id="select-type-CLI-Application" value="CLI-Application">CLI Application</option>
                    <option id="select-type-Desktop-Application" value="Desktop-Application">Desktop Application</option>
                    <option id="select-type-Full-Stack-Web-Application" value="Full-Stack-Web-Application">Full Stack Web Application</option>
                    <option id="select-type-Back-End-Web-Application" value="Back-End-Web-Application">Back End Web Application</option>
                    <option id="select-type-Front-End-Web-Application" value="Front-End-Web-Application">Front End Web Application</option>
                </select>

                <label for="project-class">Project Class</label>
                <select name="project-class" id="project-class">
                    <option id="select-class-Public" value="Public">Public</option>
                    <option id="select-class-Protected" value="Protected">Protected</option>
                    <option id="select-class-Private" value="Private">Private</option>
                </select>

                <label for="tags">Languages/Technologies</label>
                <div contenteditable id="project-tag" name="project-tag" style="position: relative; float: left; margin-bottom: 1.5vh; padding: 0 2px; box-sizing: border-box; color: #FFF; border: 2px solid #FF8C00; height: 5vh; width: 14.5vw; color: rgb(220, 0, 0);">
                    <div contenteditable id="input-string"></div>
                    <div id="input-complete"></div>
                </div>
                <div id="tags" class="tags">
                    &nbsp;
                </div>

                <input id="project-tags" name="project-tags" type="text" style="visibility: hidden;">
                <input id="submit-add-project" type="submit" style="visibility: hidden;">
            </div>
        </form>
    </div>

    @partial ../Components/add-project-buttons.php
    @partial ../Components/error-messages.php

    <script>
<?php 
    $_techList = new \App\User\TechController;
    echo $_techList->getTechListJavascript();

    echo "const __mode = \"{$_SESSION[SESSION_ACCT_STATUS]}\";" . PHP_EOL;

?>

        CKEDITOR.replace('project-desc');
        
        var __addedTags = [];

        var __projectType = "CLI-Application";
        var __projectClass = "Public";

<?php
    if (isset($_SESSION['project-tags']) && $_SESSION['project-tags'] !== "")
    {
        $_tagList = preg_split('/;/', $_SESSION['project-tags'], null, PREG_SPLIT_NO_EMPTY);

        foreach ($_tagList as $tagIndex=>$tag)
        {
            echo "__addedTags[$tagIndex] = \"$tag\";" . PHP_EOL;
        }

        if (isset($_SESSION['project-type']))
            echo "__projectType = \"" . str_replace(' ', '-', $_SESSION['project-type']) . "\";" . PHP_EOL;
        if (isset($_SESSION['project-class']))
            echo "__projectClass = \"" . $_SESSION['project-class'] . "\";" . PHP_EOL;
    }
?>

        var __charNo = 0;
        var __stringIn = "";

        $(document).ready(function() {
            function __getSelected()
            {
                $("#select-class-" + __projectClass).attr("selected", "selected");
                $("#select-type-" + __projectType).attr("selected", "selected");
            }

            function __setCaretPosition(elemId, caretPos) {
                var elem = document.getElementById(elemId);

                if(elem != null) {
                    if(elem.createTextRange) {
                        var range = elem.createTextRange();
                        range.move('character', caretPos);
                        range.select();
                    }
                    else {
                        if(elem.selectionStart) {
                            elem.focus();
                            elem.setSelectionRange(caretPos, caretPos);
                        }
                        else
                            elem.focus();
                    }
                }
            }

            function __getInputComplete()
            {
                __techList.forEach(function(value, index)
                {
                    if (__stringIn == "")
                        return;

                    if (__stringIn.toLowerCase() == value.substr(0, __stringIn.length).toLowerCase())
                    {
                        $("#input-string").html(value.substr(0, __charNo));
                        $("#input-complete").html(value.substr(__charNo));
                        return;
                    }
                });
            }

            function __showTags()
            {
                $("#tags").html("");

                __addedTags.forEach(function(tag, index)
                {
                    $("#tags").append("<div id=\"added-tag-" + index + "\" class=\"tag\"></div>");
                    $("#added-tag-" + index).html("\
                        <div id=\"added-tag-text-" + index + "\" class=\"tag-text\">\
                            " + tag + "\
                        </div>\
                        <div id=\"added-tag-delete-" + index + "\"  class=\"tag-remove\">\
                            X\
                        </div>\
                    ");

                    if (__techList.indexOf(tag) === -1)
                    {
                        $("#added-tag-text-" + index).css("background", "rgb(220, 0, 0)");
                        $("#added-tag-delete-" + index).css("background", "rgb(220, 0, 0)");
                    }                       
                    $("#added-tag-delete-" + index).on("click", function() {
                        var     __id = $(this).attr("id").substr(17);
                        
                        __addedTags.splice(index, 1);
                        __showTags();
                    });             
                });
            }

            __getSelected();
            __showTags();

            $("#submit-create-project-form").on("click", function() {
                if (__addedTags.length == 0)
                    $("#project-tags").html("Not specified");
                else
                {
                    __addedTags.forEach(function(tag) {
                        __val = $("#project-tags").val();
                        $("#project-tags").val(__val + tag + ";");
                    });
                }

                $("#submit-add-project").trigger("click");
            });

            $("#project-tag").on("keyup", function(e)
            {
                var     __self = $(this);
                var     key = e.keyCode || e.charCode;

                __showTags();

                if (key == 8 || key == 46)
                {
                    e.preventDefault();
                    __input = $("#input-string").html();

                    $("#input-string").html(__input.substr(0, (__input.length - 1)));
                    $("#input-complete").html("");

                    __charNo--;

                    if (__charNo === 0)
                        __stringIn = "";
                    else
                        __stringIn = __stringIn.substr(0, __charNo);

                    __showTags();
                }

                if (key == 13)
                {
                    e.preventDefault();
                    if (__addedTags.length >= 5)
                        window.alert("Only 5 tags are allowed per project");
                    else {
                        __val = $("#input-string").html() + $("#input-complete").html();

                        if (__addedTags.indexOf(__val) === -1) {
                            // if (__mode === "Basic" && __techList.indexOf(__val) == -1)
                            //     alert("Basic account users may not define new tags");
                            // else
                                __addedTags.push(__val);
                        }

                        __input = $("#input-string").html();

                        $("#input-string").html("");
                        $("#input-complete").html("");

                        __charNo = 0;
                        __stringIn = "";
                    }

                    __showTags();
                }

                __getInputComplete();
                __setCaretPosition("input-string", (__charNo - 1));
            });


            $("#project-tag").on("keydown", function(e)
            {
                var     key = e.keyCode || e.charCode;

                if (key == 9)
                {    
                    e.preventDefault();

                    __inputComplete = $("#input-complete").html();

                    if (__inputComplete.length > 0)
                    {
                        $("#input-complete").html("");
                        $("#input-string").append(__inputComplete);
                        __stringIn = $("#input-string").html();
                        __charNo = __stringIn.length;
                    }
                }
            });


            $("#project-tag").on("keypress", function(e)
            {
                e.preventDefault();

                var     __self = $(this);
                var     key = e.keyCode || e.charCode;

                if (key == 13)
                    return;

                __showTags();

                if (key != 8 && key != 46)
                {
                    __stringIn += String.fromCharCode(key);
                    __charNo++;

                //    $("#project-tag").attr("contenteditable", "false");

                    $("#project-tag").html("\
                        <div id=\"input-string\">" + __stringIn + "</div>\
                        <div id=\"input-complete\"></div>\
                    ");
                }

                __getInputComplete();
                __setCaretPosition("input-string", (__charNo - 1));
            });
        });
    </script>

