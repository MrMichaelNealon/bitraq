<?php

    $_project = $this->_data['project'];
    $_reports = $this->_data['reports'];
    $_friends = true;
    
    $_user = new \App\User\AuthController();

    if (($userInfo = $_user->findTableRow([
        'username', '=', $this->_data['username']
    ])) === false)
    {
        \App\Models\Messages::pushMessage(MESSAGES_ERROR, "SQL Error");
        header("Location: /");
        exit;
    }

    if (\App\Models\Session::loggedIn() === false)
        $_friends = false;
    else
    {
        $_profile = new \App\User\ProfileController();
        $_friends = $_profile->checkFriends($_SESSION[SESSION_USER_ID], $userInfo[0]['id']);
    }

    $_isUser = false;

    if (isset($_SESSION[SESSION_USER_NAME]) && $this->_data['username'] === $_SESSION[SESSION_USER_NAME])
        $_isUser = true;

    if (! $_isUser)
    {
        if ($_project['status'] !== 'Public' && $_friends === false)
        {
            \App\Models\Messages::pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }
    }
?>

[CSS[
    #report-browser {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 1.5vw;
        padding: .5vw;

        width: 96vw;
        height: auto;

        font-family: Helvetica, sans-serif;
        font-weight: bold;
        font-size: 12px;
    }

        .report-row {
            position: relative;
            float: left;

            margin: 0;
            padding: 1.5vh .5vw;

            width: 100%;
            height: 3vh;

            line-height: 3.25vh;

            background: #FFF;
        }

            .report-title {
                position: relative;
                float: left;

                margin: 0;
                padding: 0;

                width: 30%;
                height: 3vh;

                overflow: hidden;
                white-space: nowrap;

                cursor: pointer;
            }

            .report-added {
                position: relative;
                float: left;

                margin: 0;
                padding: 0;

                width: 40%;
                height: 3vh;

                color: #0A0;

                overflow: hidden;
                white-space: nowrap;

                text-align: center;
            }

            .report-resolved {
                position: relative;
                float: left;

                margin: 0;
                padding: 0;

                width: 30%;
                height: 3vh;

                overflow: hidden;
                white-space: nowrap;

                text-align: right;
            }


    @media only screen and (min-width: 640px) {
        #report-browser {
            font-size: 14px;
        }
    }
]CSS]

    <h2>Showing reports for project <?php echo $_project['name'] ?></h2>

    <div id="report-browser">

<?php
    foreach ($_reports as $index=>$report)
    {
        if (! $_isUser)
        {
            if ($report['status'] !== 'Public')
            {
                if ($report['status'] === 'Private' || $_friends === false)
                    continue;
            }
        }

        if ($report['solved_by'] === "")
        {
            $_solvedBy = "Unresolved";
            $_titleStyle = "color: #A00";
        }
        else
        {
            $_solvedBy = $report['solved_by'];
            $_titleStyle = "color: #0A0";
        }

        echo '
            <div id="report-row-' . $report['id'] . '" class="report-row" style="' . $_titleStyle . '">
                <div title="View report" onclick="window.location.href = \'/view-project-report/' . $_project['id'] . '/' . $report['id'] . '\'" id="report-title-' . $report['id'] . '" class="report-title">
                    ' . $report['title'] . '
                </div>
                <div id="report-added-' . $report['id'] . '" class="report-added">
                    ' . $report['created_at'] . '
                </div>
                <div id="report-resolved-' . $report['id'] . '" class="report-resolved style="' . $_titleStyle . '">
                    ' . $_solvedBy . '
                </div>
            </div>
        ';
    }
?>

    </div>

