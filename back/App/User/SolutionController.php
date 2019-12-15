<?php


namespace App\User;


use App\Models\UserModel;
use App\Models\Session;


Class SolutionController extends UserModel
{

    protected           $_tableName = "solutions";
    protected           $_tableSchema = [
        [ 'id', 'int', 'required', 'primary', 'auto' ],
        [ 'username', 'char', 48, 'required' ],
        [ 'project_owner', 'char', 48, 'required' ],
        [ 'project_id', 'int', 'required' ],
        [ 'report_id', 'int', 'required', ],
        [ 'body', 'text', 4096, 'required' ],
        [ 'status', 'char', 12 ]
    ];


public function editSolution($username, $reportId, $solutionId)
    {
        if (Session::loggedIn() === false || $_SESSION[SESSION_USER_NAME] != $username)
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

        if (($_solution = $this->findTableRow([
            'id', '=', $solutionId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_report = new \App\User\ReportController();

        if (($_reportList = $_report->findTableRow([
            'id', '=', $_solution[0]['report_id']
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_SESSION['report-reply'] = $_solution[0]['body'];
        $_SESSION['edit-report-reply'] = true;

        $this->_data['username'] = $_SESSION[SESSION_USER_NAME];
        $this->_data['reply-id'] = $solutionId;
        $this->_data['report'] = $_reportList[0];

        $this->render('Pages/submit-report-reply.php', $this->_data);
    
        exit;
    }


public function editReportSolution($username, $replyId)
    {
        if (Session::loggedIn() === false || $_SESSION[SESSION_USER_NAME] != $username)
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

        if (($_reply = $this->findTableRow([
            'id', '=', $replyId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        if ($this->updateTableRow([
            'body' => $_POST['report-reply']
        ],[
            'id', '=', $replyId
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        unset($_SESSION['report-reply']);
        unset($_SESSION['edit-report-reply']);

        header("Location: /view-project-report/" . $_reply[0]['project_id'] . '/' . $_reply[0]['report_id']);

        exit;
    }


public function deleteSolution($username, $reportId, $replyId)
    {
        if (Session::loggedIn() === false || $_SESSION[SESSION_USER_NAME] != $username)
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

        if (($_reply = $this->findTableRow([
            'id', '=', $replyId
        ])) ===  false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if ($this->deleteTableRow([
            'id', '=', $replyId
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        header("Location: /view-project-report/" . $_reply[0]['project_id'] . '/' . $_reply[0]['report_id']);
        exit;
    }


public function acceptSolution($username, $solutionId)
    {
        if (Session::loggedIn() === false || $_SESSION[SESSION_USER_NAME] != $username)
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

        if (($_solutionInfo = $this->findTableRow([
            'id', '=', $solutionId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_profile = new \App\User\ProfileController();
        $_user = new \App\User\AuthController();
        $_project = new \App\User\ProjectController();
        $_report = new \App\User\ReportController();

        if (($_userInfo = $_user->findTableRow([
            'username', '=', $_solutionInfo[0]['username']
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        if (($_profileInfo = $_profile->findTableRow([
            'user_id', '=', $_userInfo[0]['id']
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_notifications = $_userInfo[0]['notifications'] . 'Solution:Accepted:by:' . $_SESSION[SESSION_USER_NAME] . ';';
        $_score = $_userInfo[0]['score'];

        $_score += 10;

        if ($_user->updateTableRow([
            'notifications' => $_notifications,
            'score' => $_score
        ], [
            'id', '=', $_userInfo[0]['id']
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        if ($this->updateTableRow([
            'status' => 'solved'
        ], [
            'id', '=', $solutionId
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_username = $_userInfo[0]['username'];

        if (($_userInfo = $_user->findTableRow([
            'username', '=', $_SESSION[SESSION_USER_NAME]
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $this->_data['notifications'] = $_userInfo[0]['notifications'];

        $this->render('Pages/notifications.php', $this->_data);
        exit;
    }


public function rejectSolution($username, $solutionId)
    {
        if (Session::loggedIn() === false || $_SESSION[SESSION_USER_NAME] != $username)
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

        if (($_solutionInfo = $this->findTableRow([
            'id', '=', $solutionId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_profile = new \App\User\ProfileController();
        $_user = new \App\User\AuthController();

        if (($_userInfo = $_user->findTableRow([
            'username', '=', $_solutionInfo[0]['username']
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        if (($_profileInfo = $_profile->findTableRow([
            'user_id', '=', $_userInfo[0]['id']
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_notifications = $_userInfo[0]['notifications'] . 'Solution:Rejected:by:' . $_SESSION[SESSION_USER_NAME] . ';';

        if ($_user->updateTableRow([
            'notifications' => $_notifications
        ], [
            'id', '=', $_userInfo[0]['id']
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        // if ($this->updateTableRow([
        //     'status' => 'solved'
        // ], [
        //     'id', '=', $solutionId
        // ]) === false)
        // {
        //     $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
        //     header("Location: /");
        //     exit;
        // }

        $_username = $_userInfo[0]['username'];

        if (($_userInfo = $_user->findTableRow([
            'username', '=', $_SESSION[SESSION_USER_NAME]
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        if ($this->deleteTableRow([
            'id', '=', $solutionId
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $this->_data['notifications'] = $_userInfo[0]['notifications'];

        $this->render('Pages/notifications.php', $this->_data);
        exit;
    }

}

