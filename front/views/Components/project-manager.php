[CSS[
    .project-row {
        position: relative;
        float: left;

        white-space: nowrap;
        overflow: hidden;

        margin: .5vh;
        padding: 0 .5vh;

        width: calc(100% - 1vh);
        height: 5vh;

        font-faminy: Helvetica, sans-serif;
        font-size: 12px;
        font-weight: bold;

        line-height: 5.5vh;

        border-top: 1px solid #C0C0C0;
        background: #FFF;
    }

        .project-column-name {
            position: relative;
            float: left;

            box-sizing: b0rder-box;

            width: 25%;
            height; 6vh;

            font-family: Helvetica, sans-serif;

            color: #FD6C00;
            background: #FFF;

            cursor: pointer;
        }

        .project-column-repo {
            position: relative;
            float: left;

            box-sizing: border-box;

            width: 35%;
            height: 5vh;

            font-family: Helvetica, sans-serif;

            color: #1E90FF;
            background: #FFF;
            
            cursor: pointer;
        }

        .project-column-type {
            position: relative;
            float: left;

            white-space: nowrap;
            box-sizing: border-box;

            font-family: Helvetica, sans-serif;

            width: calc(40% - 32px);
            height: 5vh;
            
            background: #FFF;

            cursor: pointer;
            z-index: 101;
        }

        .project-column-more {
            position: relative;
            float: right;

            box-sizing: border-box;

            text-align: center;
            font-family: Helvetica, sans-serif;

            width: 32px;
            height: 6vh;

            color: #D0D0D0;
            background: #FFF;

            z-index: 999;
        }

            .project-column-more-arrow {
                position: relative;
                float: left;

                margin: 12px 25%;
                box-sizing: border-box;

                width: 50%;
                height: 16px;

                border-top: 8px solid #FF8C00;
                border-left: 8px solid #FFF;
                border-right: 8px solid #FFF;
                border-bottom: 8px solid #FFF;

                cursor: pointer;

                z-index: 999;
            }

    .project-info {
        position: relative;
        float: left;
        
        padding: 0 .5vh;

        width: calc(100% - 1vh);
        height: 0px;

        background: #FAFAFA;

        display: none;
    }

        .project-ver {
            position: repative;
            float: left;

            width: 40%;
            height: 8vh;
        }

            .project-ver-header {
                position: relative;
                float: left;

                box-sizing: border-box;

                padding: .5vh 0 0 .5vh;

                font-size: 10px;
                font-family: Helvetica, sans-serif;

                width: 25%;
                height: 4vh;

                line-height: 4vh;

                color: #000;
                background: #FAFAFA;
            }

            .project-ver-data {
                position: relative;
                float: left;

                padding: 0 1vh;
                
                width: calc(25% - 2vh);
                height: 4vh;

                font-size: 12px;
                font-weight: bold;
                
                color: #888;
            }

        .project-tags {
            position: relative;
            float: right;

            margin-top: 1.5vh;

            width: auto;
            height: auto;
        }

            .project-tag {
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

        .project-desc {
            position: relative;
            float: left;

            box-sizing: border-box;

            padding: 1vh .5vh;

            width: 100%;
            height: auto;

            font-size: 12px;
        }

        .project-options {
            position: relative;
            float: right;

            box-sizing: border-box;

            padding: .5vh .5vh;

            width: 100%;
            height: auto;

            text-align: right;
            font-size: 12px;
        }

            .project-options a {
                font-size: 12px;
                font-weight: bold;
                font-family: Helvetica, sans-serif;

                margin: 0 1vw;
            }

    @media only screen and (min-width: 640px) {
        .project-row {
            font-size: 14px;
        }

        .project-ver-header {
            font-size: 14px;
        }

        .project-ver-data {
            font-size: 14px;
        }

        .project-options a {
            font-size: 14px;
        }

        .project-tag {
            font-size: 14px;
            font-weight: bold;
        }

        .project-desc,
        .project-options
        {
            font-size: 16px;
        }
    }

]CSS]
    
    <h2>Project manager</h2>
    
    <div class="project-manager">

        @partial error-messages.php
        @partial notifications.php

<?php
    foreach ($this->_data['projects'] as $project)
    {
        echo '
            <div id="project-row-' . $project['id'] . '" class="project-row">
                <div id="project-name-' . $project['id'] . '" class="project-column-name" onclick="window.location.href = \'view-reports/' . $_SESSION[SESSION_USER_NAME] . '/' . $project['id'] . '\'">' . $project['name'] . '</div>
                <div id="project-repo-' . $project['id'] . '" class="project-column-repo" onclick="window.open(\'' . $project['repo'] . '\', \'_blank\')">' . $project['repo'] . '</div>
                <div id="project-type-' . $project['id'] . '" class="project-column-type">' . str_replace('-', ' ', $project)['typ'] . '</div>
                <div id="project-more-' . $project['id'] . '" class="project-column-more">
                    <div id="project-more-arrow-' . $project['id'] . '" class="project-column-more-arrow" title="Expand">&nbsp</div>
                </div>
            </div>
            <div id="project-info-' . $project['id'] . '" class="project-info">
                <div id="project-ver-' . $project['id'] . '" class="project-ver">
                    <div id="project-ver-version-' . $project['id'] . '" class="project-ver-header">
                        Version
                    </div>
                    <div id="project-ver-build-' . $project['id'] . '" class="project-ver-header">
                        Build
                    </div>
                    <div id="project-ver-release-' . $project['id'] . '" class="project-ver-header">
                        Release
                    </div>
                    <div id="project-class-' . $project['id'] . '" class="project-ver-header">
                        Class
                    </div>

                    <div id="project-ver-version-data-' . $project['id'] . '" class="project-ver-data">
                        ' . $project['version'] . '
                    </div>
                    <div id="project-ver-build-data-' . $project['id'] . '" class="project-ver-data">
                        ' . $project['build'] . '
                    </div>
                    <div id="project-ver-release-data-' . $project['id'] . '" class="project-ver-data">
                        ' . $project['rel'] . '
                    </div>
                    <div id="project-ver-class-data-' . $project['id'] . '" class="project-ver-data">
                        ' . $project['status'] . '
                    </div>
                </div>
                <div id="project-tags-' . $project['id'] . '" class="project-tags">
                ';

                $_tagList = preg_split('/;/', $project['tags'], null, PREG_SPLIT_NO_EMPTY);

                foreach ($_tagList as $tagIndex=>$tag)
                {
                    echo '
                        <div id="project-tag-' . $project['id'] . '-' . $tagIndex . '" class="project-tag">
                            ' . $tag . '
                        </div>
                    ';
                }

            echo '
                </div>
                <div id="project-desc-' . $project['id'] . '" class="project-desc">
                    ' . $project['description'] . '
                </div>
                <div id="project-options-' . $project['id'] . '" class="project-options">
                    <a href="/create-report/' . $_SESSION[SESSION_USER_NAME] . '/' . str_replace(' ', '-', $project['name']) . '/' . $project['id'] . '">New Report</a>
                    <a href="/view-reports/' . $_SESSION[SESSION_USER_NAME] . '/' . $project['id'] . '">View Reports</a>
                    <a id="delete-project-' . $project['id'] . '" class="delete-project" href="#">Delete</a>
                    <a href="/edit-project/' . $_SESSION[SESSION_USER_NAME] . '/' . $project['id'] . '">Edit</a>
                </div>
            </div>
        ';
    }
?>

    </div>

    @partial dashboard-buttons.php

    <script>

<?php
        echo "const __userName = \"" . $_SESSION[SESSION_USER_NAME] . "\";" . PHP_EOL;
?>

        var     confirmAction = __confirmAction();

        $(document).ready(function() {
            $(".project-column-more-arrow").on("click", function() {
                var     __id = $(this).attr("id").substr(19);

                function __collapseAllProjectInfo()
                {
                    $(".project-info").css("height", "0px");
                    $(".project-info").css("display", "none");

                    $(".project-column-more-arrow").css({
                        'margin-top': '12px',
                        'border-top': '8px solid #DF6C00',
                        'border-bottom': '8px solid #FFF'
                    });

                    $("#project-more-arrow-" + __id).attr("title", "Expand");
                }

                if ($("#project-info-" + __id).css("display") != "block")
                {
                    __collapseAllProjectInfo();

                    $("#project-info-" + __id).css("display", "block");
                    $("#project-info-" + __id).css("height", "auto");

                    $("#project-more-arrow-" + __id).css({
                        'margin-top': '0px',
                        'border-top': '10px solid #FFF',
                        'border-bottom': '8px solid #DF6C00'
                    });

                    $("#project-more-arrow-" + __id).attr("title", "Collapse");
                }                
                else
                {
                    __collapseAllProjectInfo();
                }
            });


            $(".delete-project").on("click", function() {
                var     __id = $(this).attr("id").substr(15);
                var     __projectName = $("#project-name-" + __id).html()

                confirmAction.showConfirm(
                    "Delete " + __projectName,
                    "Are you sure you want to delete <b>" + __projectName + "</b>?",
                    "Delete", "Cancel",
                    "/delete-project/" + __userName + "/" + __id
                );
            });
        });
    </script>

