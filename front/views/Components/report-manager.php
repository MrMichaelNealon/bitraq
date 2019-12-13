<?php
    if (\App\Models\Session::loggedIn() === false)
    {
        $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
        header("Location: /");
        exit;
    }

    $reportList = $this->_data['report_list'];
    $projectInfo = $this->_data['project_info'];
?>

[CSS[
    #project-info {
        position: relative;
        float: left;

        margin: 1.5vw;
        padding: .5vw;

        width: 96vw;
        height: auto;

        background: #FFF;

        font-family: Helvetica;
        font-weight: bold;
        font-size: 12px;

        border: 1px solid #AAA;

        overflow: hidden;
        white-space: nowrap;
    }

        #project-info-labels {
            position: relative;
            float: left;

            width: 40%;
            height: 20vh;
            background: #FFF;
        }

        #project-info-fields {
            position: relative;
            float: left;

            width: 60%;
            height: 20vh;

            background: #FFF;
        }

            .project-info-inner {
                position: relative;
                float: left;

                width: 100%;
                height: 5vh;

                background: #FFF;
    
                line-height: 5.5vh
            }

            #project-info-name { color: #FF8C00; cursor: pointer; }
            #project-info-repo { color: #1E90FF; cursor: pointer; }
            #project-info-created { color: #0A0; }
            #project-info-reports { color: #222; }


    #report-manager {
        position: relative;
        float: left;

        margin: 0px 1.5vw 1.5vw 1.5vw;
        padding: .5vw;

        width: 96vw;
        height: auto;

        font-family: Helvetica;
        font-weight: bold;
        font-size: 12px;

        background: #FFF;
    }

        #report-manager a {
            font-family: Helvetica;
            font-weight: bold;
            font-size: 12px;
        }

        .report-row {
            position: relative;
            float: left;

            box-sizing: border-box;

            margin: 0;

            width: 100%;
            height: 5vh;

            line-height: 5.5vh;

            overflow: hidden;
            white-space: nowrap;
        }
        
        .report-title {
            position: relative;
            float: left;

            margin: 0;
            padding: 0 .5vw;

            width: 20%;
            height: 5vh;

            color: #FF8C00;
            background: #FFF;

            cursor: pointer;
        }
       
        .report-created-on {
            position: relative;
            float: left;

            margin: 0;
            padding: 0 .5vw;

            width: 25%;
            height: 5vh;

            color: #0A0;
            background: #FFF;

            display: none;
        }

        .report-solved-by {
            position: relative;
            float: left;

            margin: 0;
            padding: 0 .5vw;

            width: 20%;
            height: 5vh;

            color: #0A0;
            background: #FFF;
        }

        .report-unresolved {
            position: relative;
            float: left;

            margin: 0;
            padding: 0 .5vw;

            width: 20%;
            height: 5vh;

            color: #A00;
            background: #FFF;
        }

        .report-solved-on {
            position: relative;
            float: left;

            margin: 0;
            padding: 0 .5vw;

            width: 20%;
            height: 5vh;

            color: #0A0;
            background: #FFF;
        }

        .report-edit {
            position: relative;
            float: right;

            box-sizing: border-box;

            margin: 0;
            padding: 0 .5vw;

            text-align: right;

            width: 20%;
            height: 5vh;
            background: #FFF;
        }

        .report-delete {
            position: relative;
            float: right;

            box-sizing: border-box;

            margin: 0;
            padding: 0 .5vw;

            text-align: right;

            width: 20%;
            height: 5vh;
            background: #FFF;
        }

    @media only screen and (min-width: 640px) {
        #project-info-labels, #project-info-fields {
            width: 100%;
            height: 5vh;

            font-size: 14px;
        }

        #report-manager {
            font-size: 14px;
        }

        #project-info-labels {
            border-bottom: 1px solid #DCDCDC;
        }

        .project-info-inner {
            width: 25%;
        }

        .report-created-on {
            display: block;
        }

            .report-resolved-by {
                width: 20%;
            }

            .report-edit, .report-delete {
                width: 15%;
            }

        #report-manager a {
            font-size: 14px;
        }
    }
]CSS]


        <h2>Reports</h2>

        <div id="project-info">
            <div id="project-info-labels">
                <div class="project-info-inner">
                    Project
                </div>
                <div class="project-info-inner">
                    Repository
                </div>
                <div class="project-info-inner">
                    Created 
                </div>
                <div class="project-info-inner">
                    Total reports
                </div>
            </div>
            <div id="project-info-fields">
                <div echo="View project" class="project-info-inner" id="project-info-name" style="cursor: default;">
                    <?php echo $projectInfo['name']; ?>
                </div>
                <div title="View repo" class="project-info-inner" id="project-info-repo" onclick="window.open('<?php echo $projectInfo['repo']; ?>', '_blank')">
                    <?php echo $projectInfo['repo']; ?>
                </div>
                <div class="project-info-inner" id="project-info-created">
                    <?php echo $projectInfo['created_at']; ?>
                </div>
                <div class="project-info-inner" id="project-info-reports">
                    <?php echo count($reportList); ?>
                </div>
            </div>
        </div>

        <div id="report-manager">
<?php
    foreach ($reportList as $index=>$report)
    {
        echo '
            <div id="report-' . $report['project_id'] . '" class="report-row">
                <div id="report-title-' . $report['project_id'] . '" class="report-title" onclick="window.location.href = \'/edit-report/' . $report['username'] . '/' . $projectInfo['name'] . '/' . $projectInfo['id'] . '/' . $report['id'] . '\'">
                    ' . $report['title'] . '
                </div>
                <div id="report-created-' . $report['project_id'] . '" class="report-created-on">
                    ' . $report['created_at'] . '
                </div>
        ';

        if ($report['solved_by'] === '')
        {
            $solvedBy = "Unresolved";
            $solvedOn = '-';
            echo '
                <div id="report-solved-by-' . $report['solved_by'] . '" class="report-unresolved">
                    ' . $solvedBy . '
                </div>
            ';
        }
        else
        {
            $solvedBy = 'Resolved';
            $solvedOn = $report['solved_on'];
            echo '
                <div id="report-solved-by-' . $report['solved_by'] . '" class="report-solved-by">
                    ' . $solvedBy . '
                </div>
            ';
        }

        echo '
                <a href="/edit-report/' . $report['username'] . '/' . $projectInfo['name'] . '/' . $projectInfo['id'] . '/' . $report['id'] . '" class="report-edit">
                    Edit
                </a>
                <a class="delete-report" name="report-' . $report['title'] . '" id="delete-report-' . $report['id'] . '" href="#">
                    Delete
                </a>
            </div>
        ';
    }
?>
        </div>

        <script>
            var     confirmAction = __confirmAction();

            $(document).ready(function() {
                $(".delete-report").on("click", function() {
                    var __reportId = $(this).attr("id").substr(14);
                    var __reportName = $(this).attr("name").substr(7);

                    var __userName = "<?php echo $_SESSION[SESSION_USER_NAME]; ?>";
                    var __id = "<?php echo $_SESSION[SESSION_USER_ID]; ?>";

                    confirmAction.showConfirm(
                        "Delete " + __reportName,
                        "Are you sure you want to delete report <b>" + __reportName + "</b>?",
                        "Delete", "Cancel",
                        "/delete-report/" + __userName + "/" + __id + "/" + __reportId
                    );
                });
            });
        </script>

