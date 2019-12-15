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

}

