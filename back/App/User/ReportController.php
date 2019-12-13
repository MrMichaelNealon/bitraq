<?php


namespace App\User;


use App\Models\UserModel;
use App\Models\Session;


    __defifndef('SESSION_ACCT_STATUS',  '__session_acct_status');

    __defifndef('SESSION_ACCT_BASIC',   'Basic');
    __defifndef('SESSION_ACCT_SUPER',   'Super');


Class ReportController extends UserModel
{

    protected           $_tableName = "reports";
    
    protected           $_tableSchema = [
        [ 'id', 'int', 'required', 'primary', 'auto' ],
        [ 'project_id', 'int', 'required' ],
        [ 'created_at', 'char', 24, 'required' ],
        [ 'username', 'char', 48, 'required' ],
        [ 'title', 'char', 48, 'required' ],
        [ 'body', 'text', 4096, 'required' ],
        [ 'status', 'char', 12, 'required' ],
        [ 'replicate', 'text', 4096, 'required' ],
        [ 'notes', 'text', 4096 ],
        [ 'replies', 'text', 65535 ],
        [ 'solved_by', 'text', 48 ],
        [ 'solved_on', 'text', 24 ]
    ];


public function createReport($username, $project, $id)
    {
        unset($_SESSION['edit-report']);
        unset($_SESSION['report-title']);
        unset($_SESSION['report-body']);
        unset($_SESSION['reqport-status']);
        unset($_SESSION['report-replicate']);
        unset($_SESSION['report-notes']);
        
        $this->_data['report-username'] = $username;
        $this->_data['report-project'] = $project;
        $this->_data['report-id'] = $id;

        $this->render("Pages/create-report.php", $this->_data);
    }


public function submitReport($id)
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if ($this->validateInput($_POST, [
            'report-title' => true
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Input validation error");
            header("Location: /");
            exit;
        }

        if ($this->insertTableRow([
            'project_id' => $id,
            'created_at' => date('d/m/Y H:i:s'),
            'username' => $_SESSION[SESSION_USER_NAME],
            'title' => $_POST['report-title'],
            'body' => $_POST['report-body'],
            'status' => $_POST['report-status'],
            'replicate' => $_POST['report-replicate'],
            'notes' => $_POST['report-notes'],
            'replies' => '',
            'solved_by' => '',
            'solved_on' => ''
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Input validation error");
            header("Location: /dashboard");
            exit;
        }


        $this->messages->_pushMessage(MESSAGES_NOTIFY, "Report submitted (<a href=\"#\">View</a>");
        header("Location: /dashboard");
        exit;
    }


public function viewReports($username, $id)
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

    //  If id < 0 return all reports for this project.
    //
        if ($id < 0)
        {
            $_reportList = $this->findTableRow([
                'username', '=', $username
            ]);
        }
        else
        {
            $_reportList = $this->findTableRow([
                'username', '=', $username,
                'AND',
                'project_id', '=', $id
            ]);
        }

        if ($_reportList === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /dashboard");
            exit;
        }

        $_projects = new \App\User\ProjectController();

        if (($_projectInfo = $_projects->findTableRow([
            'id', '=', $id
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /dashboard");
            exit;
        }

        $_data['report_list'] = $_reportList;
        $_data['project_info'] = $_projectInfo[0];

        $this->_data['project_id'] = $_data['project_info'];

        $this->render('Pages/report-manager.php', $_data);
        exit;
    }


public function editReport($username, $project, $id, $reportId)
    {
        if (Session::loggedIn() === false || $_SESSION[SESSION_USER_NAME] != $username)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if (($reportData = $this->findTableRow([
            'username', '=', $username,
            'AND',
            'id', '=', $reportId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_SESSION['edit-report'] = true;

        $_SESSION['report-title'] = $reportData[0]['title'];
        $_SESSION['report-body'] = $reportData[0]['body'];
        $_SESSION['report-status'] = $reportData[0]['status'];
        $_SESSION['report-replicate'] = $reportData[0]['replicate'];
        $_SESSION['report-notes'] = $reportData[0]['notes'];
    
        $this->_data['report-username'] = $username;
        $this->_data['report-project'] = $project;
        $this->_data['report-id'] = $reportId;
        $this->_data['project-id'] = $id;

        $this->render('Pages/create-report.php', $this->_data);
    }


public function updateReport($username, $project, $projectId, $id)
    {
        if (Session::loggedIn() === false || $_SESSION[SESSION_USER_NAME] != $username)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if ($this->validateInput($_POST, [
            'report-title' => true
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Input validation error");
            header("Location: /");
            exit;
        }

        if ($this->updateTableRow([
            'title' => $_POST['report-title'],
            'body' => $_POST['report-body'],
            'status' => $_POST['report-status'],
            'replicate' => $_POST['report-replicate'],
            'notes' => $_POST['report-notes']
        ], [
            'username', '=', $username,
            'AND',
            'id', '=', $id
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /create-report");
            exit;
        }

        unset($_SESSION['edit-report']);
        unset($_SESSION['report-title']);
        unset($_SESSION['report-body']);
        unset($_SESSION['report-status']);
        unset($_SESSION['report-replicate']);
        unset($_SESSION['report-notes']);

        $this->_data['report-username'] = $username;
        $this->_data['report-project'] = $project;
        $this->_data['report-id'] = $id;

        $this->messages->_pushMessage(MESSAGES_NOTIFY, "Report edited successfully");
        header("Location: /view-reports/$username/$projectId");
        exit;
    }


public function viewProjectReports($username, $projectId)
    {
        if (($_reportList = $this->findTableRow([
            'username', '=', $username,
            'AND',
            'project_id', '=', $projectId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_project = new \App\User\ProjectController();

        if (($projectInfo = $_project->findTableRow([
            'id', '=', $projectId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $this->_data['username'] = $username;
        $this->_data['project'] = $projectInfo[0];
        $this->_data['project_id'] = $projectId;
        $this->_data['reports'] = $_reportList;

        $this->render('Pages/view-project-reports.php', $this->_data);
        exit;
    }


public function viewProjectReport($projectId, $reportId)
    {
        if (($_reports = $this->findTableRow([
            'id', '=', $reportId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }


        $_project = new \App\User\ProjectController();

        if (($_project = $_project->findTableRow([
            'id', '=', $projectId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $this->_data['username'] = $_reports[0]['username'];
        $this->_data['project'] = $_project[0];
        $this->_data['report'] = $_reports[0];

        $this->render('Pages/view-project-report.php', $this->_data);
        exit;
    }


public function deleteReport($username, $id, $reportId)
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if ($_SESSION[SESSION_USER_NAME] !== $username || $_SESSION[SESSION_USER_ID] !== $id)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if ($this->deleteTableRow([
            'id', '=', $reportId
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $this->messages->_pushMessage(MESSAGES_NOTIFY, "Report $reportId deleted");
        header("Location: /view-reports/$username/$id");

        exit;
    }


public function submitReportReply($username, $reportId)
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if ($_SESSION[SESSION_USER_NAME] !== $username)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if (($_reports = $this->findTableRow([
            'id', '=', $reportId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $this->_data['report'] = $_reports[0];
        $this->_data['username'] = $username;

        $this->messages->_pushMessage(MESSAGES_ERROR, "Reply submitted for report <b>{$_reports[0]['title']}</b>");
        $this->render('Pages/submit-report-reply.php', $this->_data);

        exit;
    }


public function submitSolution($username, $projectId, $reportId)
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if ($_SESSION[SESSION_USER_NAME] !== $username)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if (($_report = $this->findTableRow([
            'id', '=', $reportId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_reply = "
            <a href=\"#\">$username</a>
            <p>{$_POST['report-reply']}</p>
        ";

        $_report[0]['replies'] .= "\t" . $_reply;

        if ($this->updateTableRow([
            'replies' => $_reply
        ], [
            'project_id', '=', $projectId
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_profile = new \App\User\ProfileController();
        $_project = new \App\User\ProjectController();

        if (($_projectInfo = $_project->findTableRow([
            'id', '=', $projectId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_user = new \App\User\AuthController();

        if (($_userInfo = $_user->findTableRow([
            'username', '=', $_report[0]['username']
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        if (($_userProfile = $_profile->findTableRow([
            'user_id', '=', $_userInfo[0]['id']
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_userInfo[0]['notifications'] .= "Solution@{$username}@{$projectId}:{$reportId}:Solution submitted by user $username";

        if ($_user->updateTableRow([
            'notifications' => $_userInfo[0]['notifications']
        ], [
            'id', '=', $_userProfile[0]['user_id']
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $this->messages->_pushMessage(MESSAGES_ERROR, "Solutions submitted");
        header("location: /view-project-report/$projectId/$reportId");

        exit;
    }

}

