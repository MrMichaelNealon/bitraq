///////////////////////////////////////////////////////////
//
//  The header component.
//
//  CSS styling for the header and related components.
//
[CSS[
    header {
        position: relative;
        float: left;

        box-sizing: border-box;

        padding: 0 2.5vh;

        width: 100vw;
        height: 12vh;

        line-height: 12vh;

        background: #000;
    }

        header h1 {
            position: relative;
            float: left;

            box-sizing: border-box;

            width: auto;
            height: auto;

            color: #FFF;

            font-family: Helvetica, sans-serif;
            cursor: pointer;
        }

        header div {
            position: relative;
            float: right;

            box-sizing: border-box;

            width: auto;
            height: auto;

            color: #FFF;
        }

    #toggle-dash-options {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin-top: 3.5vh;
        margin-right: 1.25vh;

        padding: 0;

        width: 4.75vh;
        height: 4.75vh;

        overflow: hidden;
    }

        .dash-img {
            position: relative;
            float: left;

            margin: 0;
            padding: 0;

            width: 4.75vh;
            height: 4.75vh;
        }

        .toggle-dash-options-overlay {
            position: absolute;

            box-sizing: border-box;

            padding: 0;

            top: -2.25vh;
            left: -2.25vh;

            width: 9vh;
            height: 9vh;

            background: none;

            border-radius: 4.5vh;
            border: 2.25vh solid #000;

            cursor: pointer;
        }

    #toggle-dash-notifications {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin-top: 3.5vh;
        margin-right: 3.25vh;

        padding: 0;

        width: 4.75vh;
        height: 4.75vh;

        overflow: hidden;
        background: #FF8C00;

        cursor: pointer;
    }

    #notifications-counter {
        position: absolute;

        box-sizing: border-box;

        font-family: Helvetica, sans-serif;
        font-weight: bold;
        font-size: 1.5vh;

        top: 2vh;
        left: 2vh;
        width: 2.5vh;
        height: 2.5vh;

        text-align: center;
        line-height: 2.7vh;

        border: 1px solid #FFF;
        background: #A00;
        color: #FFF;
    }

]CSS]

    <header>
        <h1 onclick="window.location.href = '/'">[[APP_TITLE]]</h1>
        <div>

///////////////////////////////////////////////////////////
//
//  Logged out users will see the "Sign in" link, logged
//  in users will see the options link.
//
<?php
    if (\App\Models\Session::loggedIn() === true) {

        $_profile = new \App\User\ProfileController();
        $_profileImage = $_profile->getProfileImage();

        $userInfo = new \App\User\AuthController();
        if (($_notifications = $userInfo->findTableRow([
            'username', '=', $_SESSION[SESSION_USER_NAME]
        ])) === false)
        {
            \App\Models\Messages::pushMessage(MESSAGES_ERROR, "Error retrieving notifications");
            $_notificationList = Array();
        }
        else
        {
            $_notificationList = preg_split('/;/', $_notifications[0]['notifications'], null, PREG_SPLIT_NO_EMPTY);
        }

?>
        <div id="toggle-dash-notifications" onclick="window.location.href = '/notifications'">
            <img class="dash-img" src="[STORE[Images/notificationsIcon.png]]" style="margin-left: -1.5vh; margin-top: -1.5vh; height: calc(100% + 3vh); width: calc(100% + 3vh);">
            <div class="toggle-dash-options-overlay" style="border-color: #000;">
                &nbsp;
            </div>

            <?php if (count($_notificationList) > 0) { ?>
            <div id="notifications-counter">
                <?php 
                    echo count($_notificationList);
                ?>
            </div>
            <?php } ?>
        </div>

        <div id="toggle-dash-options">
            <img class="dash-img" src="<?php echo $_profileImage; ?>">
            <div class="toggle-dash-options-overlay">
                &nbsp;
            </div>
        </div>

        @partial ../Components/dashboard-options.php
<?php
    } else
        echo "<a href=\"/sign-in\">Sign in</a>";
?>

        </div>
    </header>

