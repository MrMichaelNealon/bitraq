<?php

    if (\App\Models\Session::loggedIn() === false)
    {
        die();
        \App\Models\Messages::pushMessage(MESSAGES_ERROR, "Permission denied");
        header("Location: /");
        exit;
    }

    $_notifications = preg_split('/;/', $this->_data['notifications'], null, PREG_SPLIT_NO_EMPTY);

?>


[CSS[
    #notifications {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 1.5vw;

        width: 97vw;
        height: auto;

        background: none;
    }

        .notification {
            position: relative;
            float: left;

            box-sizing: border-box;

            margin-bottom: 2.5vh;
            padding: .5vw;

            width: 95vw;
            height: auto;

            background: #FFF;
        }

            .notification h1 {
                position: relative;
                float: left;

                width: 100%;
                height: auto;

                text-align: center;

                margin-bottom: .5vh;

                color: #1E90FF;

                font-family: Helvetica, sans-serif;
                font-weight: bold;
                font-size: 22px;

                cursor: pointer;
            }

            .notification img {
                position: relative;
                float: left;

                box-sizing: border-box;

                width: 100%;
                height: auto;
            }

            .notification em {
                color: #000;
            }

            .notification a {
                position: relative;
                float: right;

                width: auto;
                height: 2.5vh;

                padding: 1.5vh 0 1.5vh 2vw;

                font-family: Helvetica, sans-serif;
                font-weight: bold;
                font-size: 14px;
            }


    @media only screen and (min-width: 640px) {
        .notification {
            width: calc(50% - 0.75vw);
            margin-right: 1.5vw;
        }

        .notification:nth-child(even) {
            margin-right: 0;
        }

        .notification a {
            padding-bottom: 1.5vh;
        }
    }

    @media only screen and (min-width: 1000px) {
        .notification {
            width: calc(25% - 1.50vw);
            margin-right: 2vw;
        }

        .notification:nth-child(even) {
            margin-right: 2vw;
        }

        .notification:nth-child(4n) {
            margin-right: 0;
        }

        .notification h1 {
            font-size: 18px;
        }

        .notification a {
            padding-bottom: 0vh;
        }
            }
        ]CSS]

    <h2>You have <?php echo count($_notifications); ?> notifications</h2>

    <div id="notifications">

<?php

    foreach ($_notifications as $index=>$notification)
    {
        $notice = preg_split('/:/', $notification, null, PREG_SPLIT_NO_EMPTY);

        if (substr($notice[0], 0, 8) === "Solution") {
            $_notifyHeader = "Proposed solution";
            echo $notice[0];
        }

        if ($notice[0] == "FriendRequest") {
            $_notifyHeader = "Friend Request";
            $_user = new \App\User\AuthController();

            if (($_userInfo = $_user->findTableRow([
                'username', '=', $notice[1]
            ])) === false)
            {
                \App\Models\Messages::pushMessage(MESSAGES_ERROR, "SQL Error");
                $_userImage = __buildpath(Array(PATH_ROOT, "store", "Images", "index.png"));
            }
            else
            {
                $profile = new \App\User\ProfileController();

                if (($_userProfile = $profile->findTableRow([
                    'user_id', '=', $_userInfo[0]['id']
                ])) === false)
                {
                    $_userImage = __buildpath(Array(PATH_ROOT, "store", "Images", "index.png"));
                }
                else
                {
                    if ($_userProfile[0]['img'] === "")
                        $_userImage = __buildpath(Array(PATH_ROOT, "store", "Images", "index.png"));
                    else
                        $_userImage = $_userProfile[0]['img'];
                }
            }
        }

        if ($_notifyHeader === "Friend Request")
        {
            echo '
                <div id=\"notification-' . $notice[1] . ' class="notification">
                    <h1 onclick="window.open(\'/view-profile/' . $notice[1] .'/' . $_userInfo[0]['id'] . '\', \'_blank\')" title="View profile (opens in a new window/tab)">' . $_notifyHeader . ' <em>(' . $notice[1] . ')</em></h1>
                    <img src="' . $_userImage . '">
                    <a href="/accept-friend-request/' . $_SESSION[SESSION_USER_NAME] . '/' . $notice[1] . '/' . $index . '">Accept</a>
                    <a href="/reject-friend-request/' . $_SESSION[SESSION_USER_NAME] . '/' . $notice[1] . '/' . $index . '">Reject</a>
                </div>
            ';
        }
    }

?>
    </div>