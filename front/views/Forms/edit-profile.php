<?php

    if (\App\Models\Session::loggedIn() === false)
    {
        \App\Models\Messages::pushMessage(MESSAGES_ERROR, "Permission denied");
        header("Location: /");
        exit;
    }


    if ($_SESSION['user_id'] !== $_SESSION[SESSION_USER_ID])
    {
        \App\Models\Messages::pushMessage(MESSAGES_ERROR, "!!!Permission denied");
        header("Location: /dashboard");
        exit;
    }


    if ($_SESSION['img'] === "")
        $_SESSION['img'] = "[STORE[Images/index.png]]";

?>

[CSS[
    #profile-form {
        position: relative;
        float: left;

        clear: left;

        box-sizing: border-box;

        margin: 1.5vw;
        padding: 1.5vh;

        width: 60%;
        height: auto;

        background: #FFF;
    }

    #profile-image-form {
        position: relative;
        float: right;

        box-sizing: border-box;

        margin: 1.5vw;
        padding: 1.5vh;

        width: 30%;
        height: auto;

        background: #FFF;
    }

        #profile-user-info {
            position: relative;
            float: left;

            box-sizing: border-box;

            margin: 0vw;
            padding: 0vw;

            width: 100%;
            height: auto;
        }

            #profile-form input[type="text"] {
                text-align: left;
                width: 100%;
            }

            #profile-form label {
                width: 100%;
            }

            #profile-form textarea {
                margin-bottom: 2.5vh;
                width: 100%;
                height: 15vh;
            }

    #profile-image {
        position: relative;
        float: right;

        margin: 0vh;
        padding: 0vh;

        width: 100%;
    }

        #profile-image img {
            width: 100%;
            height: auto;

            cursor: pointer;
        }

    @media only screen and (min-width: 640px) {
        #profile-form {
            width: 64vw;
        }

        #profile-image-form {
            width: 29vw;
        }
    }
]CSS]

    <h2>Edit Profile</h2>

    <form id="profile-form" method="POST" action="/edit-profile/<?php echo $_SESSION[SESSION_USER_NAME] . '/' . $_SESSION[SESSION_USER_ID]; ?>">
    
        @partial ../Components/error-messages.php
        @partial ../Components/notifications.php

        <div id="profile-user-info">

            <label for="first-name">First Name</label>
            <input id="first-name" type="text" name="first-name" value="<?php echo $_SESSION['first_name']; ?>">
    
            <label for="last-name">Last Name</label>
            <input id="last-name" type="text" name="last-name" value="<?php echo $_SESSION['last_name']; ?>">
        
            <label for="gender">Gender</label>
            <select id="profile-gender" name="profile-gender">
                <option id="gen-male" value="Male">Male</option>
                <option id="gen-female" value="Female">Female</option>
                <option id="gen-other" value="Other">Other</option>
            </select>

            <label for="location">Location</label>
            <input id="location" type="text" name="location" value="<?php echo $_SESSION['loc']; ?>">
        
            <label for="company">Company</label>
            <input id="company" type="text" name="company" value="<?php echo $_SESSION['company']; ?>">
        
            <label for="biography">Biography</label>
            <textarea id="biography" type="text" name="biography"><?php echo $_SESSION['bio']; ?></textarea>

            <input id="submit-profile-form" type="submit" style="display: none">
        </div>

    </form>

    <form id="profile-image-form" enctype="multipart/form-data" method="POST" action="/image-upload/<?php echo $_SESSION[SESSION_USER_NAME] . '/' . $_SESSION[SESSION_USER_ID]; ?>">
        <input id="file-upload" type="file" name="img" style="display: none;">

        <div id="profile-image">
            <img title="Change image" src="<?php echo $_SESSION['img']; ?>">
        </div>

        <input id="upload-first-name" style="display: none;" type="text" name="upload-first-name" value="<?php echo $_SESSION['first_name']; ?>">
        <input id="upload-last-name" style="display: none;" type="text" name="upload-last-name" value="<?php echo $_SESSION['last_name']; ?>">
        <select id="upload-profile-gen" style="display: none;" name="upload-profile-gen">
            <option id="gen-male" value="Male">Male</option>
            <option id="gen-female" value="Female">Female</option>
            <option id="gen-other" value="Other">Other</option>
        </select>
        <input id="upload-location" style="display: none;" type="text" name="upload-location" value="<?php echo $_SESSION['loc']; ?>">
        <input id="upload-company" style="display: none;" type="text" name="upload-company" value="<?php echo $_SESSION['company']; ?>">
        <textarea id="upload-biography" style="display: none;" type="text" name="upload-biography"><?php echo $_SESSION['bio']; ?></textarea>

        <input id="upload-file-submit" type="submit" style="display: none;">
    </form>


    @partial ../Components/edit-profile-buttons.php


    <script>
        var     __gen = "<?php echo $_SESSION['gen']; ?>";

        $(document).ready(function() {

            $("#profile-image").on("click", function() {
                $("#file-upload").trigger("click");
            });

            $("#file-upload").on("change", function() {
                $("#upload-first-name").val($("#first-name").val());
                $("#upload-last-name").val($("#last-name").val());
                $("#upload-profile-gen").val($("#profile-gender").val());
                $("#upload-company").val($("#company").val());
                $("#upload-location").val($("#location").val());
                $("#upload-biography").val($("#biography").val());

                $("#upload-file-submit").trigger("click");
            })

            if (__gen == "Male")
                $("#gen-male").attr("selected", "selected");
            if (__gen == "Female")
                $("#gen-female").attr("selected", "selected");
            if (__gen == "Other")
                $("#gen-other").attr("selected", "selected");
        
            $("#submit-profile").on("click", function() {
                $("#submit-profile-form").trigger("click");
            });
        });
    </script>

