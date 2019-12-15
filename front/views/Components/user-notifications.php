<?php

    if (\App\Models\Session::loggedIn() === false)
    {
        \App\Models\Messages::pushMessage(MESSAGES_ERROR, "Permission denied");
        header("Location: /");
        exit;
    }

    $_notifications = preg_split('/;/', $this->_data['notifications'], null, PREG_SPLIT_NO_EMPTY);
    $_solutions = new \App\User\SolutionController();

    if (($solutionList = $_solutions->findTableRow([
        'project_owner', '=', $_SESSION[SESSION_USER_NAME]
    ])) === false)
    {
        \App\Models\Messages::pushMessage(MESSAGES_ERROR, "SQL Error");
        header("Location: /");
        exit;
    }

    $_solutionCount = 0;

    foreach ($solutionList as $solution)
    {
        if ($solution['status'] === '')
            $_solutionCount++;
    }
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

            .notify-inner {
                position: relative;
                float: left;

                box-sizing: border-box;

                margin: 1.5vh 0 1.5vh 0;
                padding: 0;
            }

                .notify-inner a {
                    position: relative;
                    float: left;

                    box-sizing: border-box;

                    margin: 0;
                    padding: 0;
                }

        .solution {
            position: relative;
            float: left;

            box-sizing: border-box;

            margin: 0;
            padding: 1.5vh .5vw 7vh .5vw;

            width: 96vw;
            height: auto;

            background: #FFF;
        }

            .solution h1 {
                position: relative;
                float: left;

                width: 100%;
                height: auto;

                text-align: left;

                margin-bottom: 1vh;

                color: #FF8C00;

                font-family: Helvetica, sans-serif;
                font-weight: bold;
                font-size: 22px;

                cursor: pointer;
            }

            .solution p {
                position: relative;
                float: left;

                width: 100%;
                height: auto;

                text-align: left;

                margin: 0 .5vh;
                padding: 0px;

                color: #222;

                font-family: Helvetica, sans-serif;
                font-weight: bold;
                font-size: 14px;

                z-index: 2002;
            }

            .solution img {
                position: relative;
                float: left;

                clear: both;

                box-sizing: border-box;

                margin: 0;
                padding: 0;

                width: 100%;
                height: auto;
            }

            .solution-info {
                position: relative;
                float: right;

                display: none;

                box-sizing: border-box;

                margin: -1vh 0 0 0;
                padding: 0;

                width: 100%;
                height: auto;

                background: #FFF;
                
                overflow: hidden;
                white-space: nowrap;
            }

                .solution-options {
                    position: absolute;

                    bottom: 3.5vh;
                    right: 0vw;
                    width: calc(100% - 1.5vw);
                    height: 5vh;

                    background: #FFF;
                }

            .solution a {
                position: relative;
                float: left;

                width: auto;
                height: 2.5vh;

                padding: 0;

                font-family: Helvetica, sans-serif;
                font-weight: bold;
                font-size: 14px;
            }

                .sub-header-section {
                    position: relative;
                    float: left;

                    box-sizing: border-box;

                    margin-right: .5vh 2.5vw .5vw 0;
                    padding: 0px;

                    width: auto;
                    height: auto;
                }
                
                .sub-header-section-inner {
                    position: relative;
                    float: left;

                    box-sizing: border-box;

                    padding-right: 1.5vw;

                    width: auto;
                    height: auto;
                }

        .buttons a {
            font-size: 14px !important;
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

        .solution a {
            padding-bottom: 1.5vh;
        }

        .solution-info {
            display: block;
        }

        .solution img {
            width: 40%;
            height: auto;
        }

        .solution-info {
            padding: .5vw;

            width: 60%;
            height: auto;
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

        .solution img {
            width: 25%;
            height: auto;
        }

        .solution-info {
            width: 75%;
            height: auto;
        }
    }
]CSS]

    <h2>You have <?php echo (count($_notifications) + $_solutionCount); ?> notifications</h2>

    <div id="notifications">

<?php
    foreach ($_notifications as $index=>$notification)
    {
        $notice = preg_split('/:/', $notification, null, PREG_SPLIT_NO_EMPTY);

        $_notifyHeader = false;

        if ($notice[0] == "FriendRequest") {
            $_notifyHeader = "Friend Request";
        }

        if (trim($notice[0]) == 'Solution')
        {
            if ($notice[1] == 'Accepted')
            {
                $_notifyHeader = "Congratulations!";
                $_notifyMessage = "Your solution was accepted by " . $notice[3] . "";
            }
            else {
                $_notifyHeader = "Unfortunately...";
                $_notifyMessage = "Your solution was rejected by " . $notice[3] . "";
            }

            $notice[1] = $notice[3];
        }
    
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

        if ($_notifyHeader === "Congratulations" || $_notifyHeader === "Unfortunately...")
        {
            echo '
                <div id=\"notification-' . $notice[1] . '" class="notification">
                    <h1 style="cursor: default; text-align: left;">' . $_notifyHeader . '</h1>
                    <div class="notify-inner">
                        Your solution was rejected by
                    </div>
                    <div class="notify-inner" style="margin-left: .5vw;">
                        <a href="#" onclick="window.open(\'/view-profile/' . $notice[1] .'/' . $_userInfo[0]['id'] . '\', \'_blank\')" title="View profile (opens in a new window/tab)">' . $notice[1] . '</h1>
                    </div>
                    <a href="/acknowledge/' . $_SESSION[SESSION_USER_NAME] . '/' . $index . '">Acknowledge</a>
                </div>
            ';
        }
    }
    
    foreach ($solutionList as $solutionIndex=>$solution)
    {         

        if ($solution['status'] !== '')
            continue;

        $_project = new \App\User\ProjectController();
        $_report = new \App\User\ReportController();
        $_user = new \App\User\AuthController();
        
        if (($_projectInfo = $_project->findTableRow([
            'id', '=', $solution['project_id']
        ])) === false)
        {
            \App\Models\Messages::pushMessage(MESSAGES_ERROR, "SQL Error");
            $_userImage = __buildpath(Array(PATH_ROOT, "store", "Images", "index.png"));
        }

        if (($_reportInfo = $_report->findTableRow([
            'id', '=', $solution['report_id']
        ])) === false)
        {
            \App\Models\Messages::pushMessage(MESSAGES_ERROR, "SQL Error");
            $_userImage = __buildpath(Array(PATH_ROOT, "store", "Images", "index.png"));
        }

        if (($_userInfo = $_user->findTableRow([
            'username', '=', $solution['username']
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

        echo '
            <div id=\"solution-' . $solution['id'] . '" class="solution">
                <h1 onclick="window.open(\'/view-profile/' . $_userInfo[0]['id'] .'/' . $_userInfo[0]['id'] . '\', \'_blank\')" title="View profile (opens in a new window/tab)">Solution posted by <em>' . $solution['username'] . '</em></h1>
                <div class="sub-header-section">
                    <div class="sub-header-section-inner">
                        Project
                    </div>
                    <div class="sub-header-section-inner">
                        <a href="#">' . $_projectInfo[0]['name'] . '</a>
                    </div>
                </div>
                <div class="sub-header-section">
                    <div class="sub-header-section-inner">
                        Report
                    </div>
                    <div class="sub-header-section-inner">
                        <a href="#">' . $_reportInfo[0]['title'] . '</a>
                    </div>
                </div>   
                <img src="' . $_userImage . '">
                <div id="solution-info-' . $solution['id'] . '" class="solution-info">
                    ' . $solution['body'] . '
                </div>
                <div class="solution-options">
                    <div class="buttons">
                        <a href="/accept-solution/' . $_SESSION[SESSION_USER_NAME] . '/' . $solution['id'] . '">Accept</a>
                        <a href="#">View</a>
                        <a href="/reject-solution/' . $_SESSION[SESSION_USER_NAME] . '/' . $solution['id'] . '">Reject</a>
                    </div>
                </div>
            </div>
        ';
    }

?>
    </div>