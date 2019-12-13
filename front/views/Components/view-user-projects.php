<?php

    $_projects = $this->_data['projects'];
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

?>

[CSS[

    #user-projects {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 3.5vh .5vw 2.5vh .5vw;
        padding: 0;
    }

        .user-project-row {
            position: relative;
            float: left;

            box-sizing: border-box;

            margin: 0 1.5vh 1px 1.5vh;
            padding: 0;

            width: 97vw;
            height: 5vh;

            background: #FFF;

            line-height: 5vh;

            font-family: Helvetica, sans-serif;
            font-weight: bold;
            font-size: 12px;
        }

            .user-project-name {
                position: relative;
                float: left;

                box-sizing: border-box;

                margin: 0;
                padding: 0 .5vw;

                width: 20%;
                height: 3vh;

                color: #FF8C00;

                overflow: hidden;
                white-space: nowrap;
            }

            .user-project-version {
                position: relative;
                float: left;

                box-sizing: border-box;

                margin: 0;
                padding: 0 .5vw;

                width: 10%;
                height: 3vh;

                color: #000;

                overflow: hidden;
                white-space: nowrap;
            }

            .user-project-repo {
                position: relative;
                float: left;

                box-sizing: border-box;

                margin: 0;
                padding: 0 .5vw;

                width: 40%;
                height: 3vh;

                color: #1E90FF;

                overflow: hidden;
                white-space: nowrap;

                cursor: pointer;
            }

            .user-project-type {
                position: relative;
                float: left;

                box-sizing: border-box;

                margin: 0;
                padding: 0 .5vw;

                width: calc(30% - 3vw);
                height: auto;

                color: #000;

                overflow: hidden;
                white-space: nowrap;
            }

            .user-project-info {
                position: relative;
                float: left;

                box-sizing: border-box;

                margin: -1px 0 0 0;
                padding: 0;

                width: 3vw;
                height: 3vw;
            }

                .user-project-info-arrow {
                    position: absolute;

                    box-sizing: border-box;

                    top: 1vw;
                    left: .75vw;
                    height: 1.5vw;
                    width: 1.5wv;

                    border-top: .75vw solid #FF8C00;
                    border-left: .75vw solid rgba(0, 0, 0, 0);
                    border-right: .75vw solid rgba(0, 0, 0, 0);
                    border-bottom: .75vw solid rgba(0, 0, 0, 0);
                
                    cursor: pointer;
                }

        .user-project-more {
            position: relative;
            float: left;

            display: none;

            box-sizing: border-box;

            margin: 0 1.5vh 1px 1.5vh;
            padding: 0;

            width: 97vw;
            height: auto;

            background: #FFF;

            font-family: Helvetica, sans-serif;
            font-weight: bold;
            font-size: 12px;
        }


            .user-project-more a {
                font-size: 12px;
            }

            .user-project-description {
                position: relative;
                float: left;

                box-sizing: border-box;

                margin: 0 1.5vh 1px 1.5vh;
                padding: 1.5vh 0;

                width: 40%;
                height: auto;
            }

            .user-project-reports {
                position: relative;
                float: left;

                box-sizing: border-box;

                margin: 0 1.5vh 1px 1.5vh;
                padding: 1.5vh 0;

                width: 30%;
                height: auto;
            }

            .user-project-tags {
                position: relative;
                float: right;

                margin-top: 1.5vh;
                margin-bottom: 1.5vh;

                width: auto;
                height: auto;
            }

                .user-project-tag {
                    position: relative;
                    float: left;

                    margin: 0 .5vh .5vh 0;
                    padding: 0 .5vw;

                    font-size: 11px;
                    font-family: Helvetica, sans-serif;

                    border-radius: 4px;

                    width: auto;
                    height: 3.5vh;

                    line-height: 3.75vh;

                    color: #FFF;
                    background: #0A0;
                }


    @media only screen and (min-width: 640px) {
        .user-project-row {
            font-size: 14px;
        }

        .user-project-more a {
            font-size: 14px;
        }

        .user-project-tag {
            font-size: 14px;
        }
    }

]CSS]

    <h2><?php
        echo "Showing all projects for user <em>" . $this->_data['username'] . "</em>";
    ?></h2>

    <div id="user-projects">
<?php

        foreach ($_projects as $index=>$project)
        {
        
        //  Nobody can view private projects, only friends may vie
        //  protected.
        //
            if ($this->_data['username'] !== $_SESSION[SESSION_USER_NAME])
            {
                if ($project['status'] === 'Private')
                    continue;

                if ($project['status'] === 'Protected' && $_friends === false)
                    continue;
            }

            $_tagList = preg_split('/;/', $project['tags'], null, PREG_SPLIT_NO_EMPTY);

            $_report = new \App\User\ReportController();
            if (($reports = $_report->findTableRow([
                'project_id', '=', $project['id']
            ])) === false)
                $reports = Array();

            echo '
                <div id="user-project-' . $project['id'] . '" class="user-project-row">
                <div id="user-project-name-' . $project['id'] . '" class="user-project-name">
                    <!-- <a href="/view-profile/' . $userInfo[0]['username'] . '/' . $userInfo[0]['id'] . '"> -->
                    ' . $project['name'] . '
                    <!-- </a> -->
                </div>
                <div id="user-project-version-' . $project['id'] . '" class="user-project-version">
                    ' . $project['version'] . '.' . $project['build'] . '.' . $project['rel'] . '
                </div>
                <div id="user-project-repo-' . $project['id'] . '" class="user-project-repo">
                    ' . $project['repo'] . '
                </div>
                <div id="user-project-type-' . $project['id'] . '" class="user-project-type">
                    ' . $project['typ'] . '
                </div>
                <div id="user-project-info-' . $project['id'] . '" class="user-project-info">
                    <div id="user-project-info-arrow-' . $project['id'] . '" class="user-project-info-arrow">
                    </div>
                </div>
                </div>

                <div id="user-project-more-' . $project['id'] . '" class="user-project-more">
                    <div id="user-project-description-' . $project['id'] . '" class="user-project-description">
                        ' . $project['description'] . '
                    </div>
                    <div id="user-project-reports-' . $project['id'] . '" class="user-project-reports">
                        Reports <a href="/view-project-reports/' . $this->_data['username'] . '/' . $project['id'] . '">' . count($reports) . '</a>
                    </div>
                    <div id="user-project-tags-' . $project['id'] . '" class="user-project-tags">
            ';

            foreach ($_tagList as $tag)
            {
                echo '
                    <div id="user-project-tag-' . $tag . '" class="user-project-tag">
                        ' . $tag . '
                    </div>
                ';
            }

            echo '
                    </div>
                </div>
            ';
        }
?>
    </div>

    <script>
        $(document).ready(function() {
            $(".user-project-info").on("click", function() {
                var     __index = $(this).attr("id").substr(18);

                if ($("#user-project-more-" + __index).css("display") !== "block") {
                    $(".user-project-more").css("display", "none");

                    $(".user-project-info-arrow").css({
                        'border-top': '.75vw solid #FF8C00',
                        'border-left': '.75vw solid rgba(0, 0, 0, 0)',
                        'border-right': '.75vw solid rgba(0, 0, 0, 0)',
                        'border-bottom': '.75vw solid rgba(0, 0, 0, 0)',
                        'top': '2vh'
                    });

                    $("#user-project-more-" + __index).css("display", "block");
                    $("#user-project-info-arrow-" + __index).css({
                        'border-top': '.75vw solid rgba(0, 0, 0, 0)',
                        'border-left': '.75vw solid rgba(0, 0, 0, 0)',
                        'border-right': '.75vw solid rgba(0, 0, 0, 0)',
                        'border-bottom': '.75vw solid #FF8C00',
                        'top': '.25vh'
                    });
                }
                else {
                    $("#user-project-more-" + __index).css("display", "none");
                    $("#user-project-info-arrow-" + __index).css({
                        'border-top': '.75vw solid #FF8C00',
                        'border-left': '.75vw solid rgba(0, 0, 0, 0)',
                        'border-right': '.75vw solid rgba(0, 0, 0, 0)',
                        'border-bottom': '.75vw solid rgba(0, 0, 0, 0)',
                        'top': '2vh'
                    });
                }
            });

            $(".user-project-repo").on("click", function() {
                var     __repo = $(this).html();

                //alert("|" + __repo.trim().substr(0, 4) + "|");
                if (__repo.trim().substr(0, 4) == "http")
                    window.open(__repo, '_blank');
            });
        });
    </script>

