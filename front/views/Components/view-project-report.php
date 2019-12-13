<?php

    $_report = $this->_data['report'];
    $_project = $this->_data['project'];

    $_replies = preg_split('/\\t/', $_report['replies'], null, PREG_SPLIT_NO_EMPTY);
    
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
            die("HYOR");
            \App\Models\Messages::pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }
    }

    if ($_report['solved_on'] === '')
    {
        $_statusStyle = "color: #A00";
        $_statusValue = "Unresolved";
        $_statusDate = "---";
    }
    else
    {
        $_statusStyle = "color: #0A0";
        $_statusValue = $_report['solved_by'];
        $_statusDate = $_report['solved_on'];
    }
?>

[CSS[

    #report-container h1 {
        position: relative;
        float: left;

        margin: .5vh 0 2vh 0;

        width: 100%;
        height: auto;

        font-size: 18px;
        font-weight: bold;

        color: #1E90FF;
        text-shadow: 1px 1px 1px #000;
    }

    #report-container br {
        display: block;

        margin: .5vh 0;
    }

    #report-container {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 1.5vh 1.5vw;
        padding: 0 .5vw;

        width: 97vw;
        height: auto;

        font-family: Helvetica, sans-serif;
        font-size: 12px;

        background: #FFF;
    }

        #report-status {
            position: relative;
            float: left;

            box-sizing: border-box;

            margin: 0;
            padding: 0 .5vw;

            width: 96vw;
            height: 5vh;

            line-height: 5vh;
        }

            #report-status-title {
                position: relative;
                float: left;

                box-sizing: border-box;

                margin: 0;
                padding: 0;

                width: 33%;
                height: 5vh;
            }

            #report-status-user {
                position: relative;
                float: left;

                box-sizing: border-box;

                margin: 0;
                padding: 0;

                width: 33%;
                height: 5vh;
            }

            #report-status-date {
                position: relative;
                float: left;

                box-sizing: border-box;

                margin: 0;
                padding: 0;

                width: 33%;
                height: 5vh;

                color: #0A0;
            }

        .report-body {
            position: relative;
            float: left;

            box-sizing: border-box;

            margin: 0 0 1.5vh 0;
            padding: 1.5vh .5vw;

            width: 100%;
            height: auto;

            background: #EAEAEA;
            border: 1px solid #E0E0E0;
        }

        #report-replicate {
            position: relative;
            float: left;

            box-sizing: border-box;

            margin: 0 0 1.5vh 0;
            padding: 1.5vh .5vw;

            width: 100%;
            height: auto;

            background: #EAEAEA;
            border: 1px solid #E0E0E0;
        }

            .inner-text {
                position: relative;
                float: left;

                margin: 0;
                padding: 0 .5vw;

                width: calc(100% - 1vw);
                height: auto;

                color: #222;
                backgrond: #FFF;
            }

        .report-reply {
            position: relative;
            float: left;

            box-sizing: border-box;

            margin: 1.5vw 1.5vw;
            padding: 1.5vh 0 1.5vh .5vw;

            width: 97vw;
            height: auto;

            background: #FFF;
        }

            .report-reply-inner {
                position: relative;
                float: left;

                margin: 0;
                padding: .5vw;

                width: 95vw;
                height: auto;

                color: #222;
                background: #DCDCDC;
            }

            .report-reply-options {
                position: relative;
                float: right;

                box-sizing: border-box;

                width: 100%;
                height: auto;
            }

    @media only screen and (min-width: 640px) {
        #report-container {
            font-size: 14px;
        }
    }
]CSS]

    <h2>Report: <em><?php echo $_report['title']; ?></em></h2>

    <div id="report-container">

        <div id="report-status">
            <div id="report-status-title">
                Status
            </div>
            <div id="report-status-user" style="<?php echo $_statusStyle; ?>">
                <?php echo $_statusValue; ?>
            </div>
            <div id="report-status-date">
                <?php echo $_statusDate; ?>
            </div>
        </div>

        <div id="report-body" class="report-body">
            <h1>Main report</h1>
            <p class="inner-text">
                <?php echo str_replace("\n", '<br>', $_report['body']); ?>
            </p>
        </div>

        <div id="report-replicate" class="report-body">
            <h1>Replicate</h1>
            <p class="inner-text">
                <?php echo str_replace("\n", '<br>', $_report['replicate']); ?>
            </p>
        </div>

        <div id="report-notes" class="report-body">
            <h1>Additional Notes</h1>
            <p class="inner-text">
                <?php echo str_replace("\n", '<br>', $_report['notes']); ?>
            </p>
        </div>
    </div>

<?php
    if (! $_isUser && $_statusValue === "Unresolved")
    {
        echo '
            <div id="report-reply-options-reply" class="report-reply-options">
                <div class="buttons">
                    <a href="/submit-report-reply/' . $_SESSION[SESSION_USER_NAME] . '/' . $_report['id'] . '">Submit solution</a>
                </div>
            </div>
        ';
    }
?>

    <h2>Replies <em>(<?php echo count($_replies); ?>)</em></h2>
<?php
    foreach ($_replies as $replyIndex=>$reply)
    {
        echo '
            <div id="report-reply-' . $replyIndex . '" class="report-reply">
                <div id="report-reply-inner-' . $replyIndex . '" class="report-reply-inner">
                    ' . $reply . '
                </div>
                <div id="report-reply-options-' . $replyIndex . '" class="report-reply-options">
        ';

    if ($_isUser === true && $_statusValue === "Unresolved")
    {
        echo '
                    <div id="report-reply-accept-options-' . $replyIndex . '" class="buttons">
                        <a href="#" style="margin-right: -.5vw">
                            Accept Solution
                        </a>
                    </div>
        ';
    }

        echo '
                </div>
            </div>
        ';
    }
?>


