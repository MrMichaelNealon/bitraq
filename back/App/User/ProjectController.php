<?php


namespace App\User;


use App\Models\UserModel;
use App\Models\Session;


    __defifndef('SESSION_ACCT_STATUS',  '__session_acct_status');

    __defifndef('SESSION_ACCT_BASIC',   'Basic');
    __defifndef('SESSION_ACCT_SUPER',   'Super');


Class ProjectController extends UserModel
{

    protected           $_tableName = "projects";
    protected           $_tableSchema = [
        [ 'id', 'int', 'required', 'primary', 'auto' ],
        [ 'username', 'varchar', 48, 'required' ],
        [ 'name', 'varchar', 48, 'required' ],
        [ 'repo', 'varchar', 128, 'required' ],
        [ 'description', 'varchar', 255, 'required' ],
        [ 'version', 'int', 'required' ],
        [ 'build', 'int', 'required' ],
        [ 'rel', 'int', 'required' ],
        [ 'typ', 'varchar', 48, 'required' ],
        [ 'tags', 'varchar', 255, 'required' ],
        [ 'status', 'varchar', 24, 'required' ],
        [ 'created_at', 'varchar', 24, 'required' ]
    ];


public function addNewProject()
    {
        if (Session::loggedIn() === false)
        {
            header("Location: /");
            exit;
        }

        $this->__setProjectSessionVars();

        if ($this->validateInput($_POST, [
            'project-name' => true,
            'project-repo' => "Not specified",
            'project-version' => 1,
            'project-build' => 0,
            'project-release' => 0,
            'project-type' => 'CLI-Application',
            'project-class' => 'Public'
        ]) == false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "There was an input validation error");
            header("Locaton: /add-project");
            exit;
        }

        if ($this->insertTableRow([
            'username' => $_SESSION[SESSION_USER_NAME],
            'name' => $_POST['project-name'],
            'repo' => $_POST['project-repo'],
            'description' => $_POST['project-desc'],
            'version' => $_POST['project-version'],
            'build' => $_POST['project-build'],
            'rel' => $_POST['project-release'],
            'typ' => $_POST['project-type'],
            'tags' => $_POST['project-tags'],
            'status' => $_POST['project-class'],
            'created_at' => date('d/m/Y H:i:s')
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, $this->isError());
            header("Location: /add-project");
            exit;
        }

        $this->__unsetProjectSessionVars();

        $this->messages->_pushMessage(MESSAGES_NOTIFY, "Project {$_POST['project-name']} added successfully");
        header("Location: /dashboard");

        exit();
    }


public function deleteProject($username, $id)
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
            header("Location: /dashboard");
            exit;
        }

        if ($this->deleteTableRow([
            'id', '=', $id,
            'AND',
            'username', '=', $username
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "There was a problem...");
            header("Location: /dashboard");
            exit;
        }

        $this->messages->_pushMessage(MESSAGES_NOTIFY, "Project successfully deleted");
        header("Location: /dashboard");

        exit;
    }


public function editProject($username, $id)
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /dashboard");
            exit;
        }

        if ($_SESSION[SESSION_USER_NAME] !== $username)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /dashboard");
            exit;
        }

        $_projectData = $this->findTableRow([
            'id', '=', $id,
            'AND',
            'username', '=', $username
        ]);

        if ($_projectData === false || count($_projectData) !== 1)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Error retrieving project data");
            header("Location: /dashboard");
            exit;
        }

        $_SESSION['project-username'] = $_projectData[0]['username'];
        $_SESSION['project-id'] = $_projectData[0]['id'];

        $_SESSION['project-name'] = $_projectData[0]['name'];
        $_SESSION['project-repo'] = $_projectData[0]['repo'];
        $_SESSION['project-desc'] = $_projectData[0]['description'];

        $_SESSION['project-version'] = $_projectData[0]['version'];
        $_SESSION['project-build'] = $_projectData[0]['build'];
        $_SESSION['project-release'] = $_projectData[0]['rel'];

        $_SESSION['project-type'] = $_projectData[0]['typ'];
        $_SESSION['project-class'] = $_projectData[0]['status'];

        $_SESSION['project-tags'] = $_projectData[0]['tags'];
        
        $_SESSION['project-edit'] = true;

        $this->render("Pages/add-project.php");

        exit;
    }


public function updateProject()
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /dashboard");
            exit;
        }

        if ($_SESSION[SESSION_USER_NAME] !== $_SESSION['project-username'])
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /dashboard");
            exit;
        }

        if ($this->validateInput($_POST, [
            'project-name' => true,
            'project-repo' => "Not specified",
            'project-version' => 1,
            'project-build' => 0,
            'project-release' => 0,
            'project-type' => 'CLI-Application',
            'project-class' => 'Public'
        ]) == false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "There was an input validation error");
            header("Locaton: /edit-project");
            exit;
        }


        if ($this->updateTableRow([
            'username' => $_SESSION['project-username'],
            'name' => $_POST['project-name'],
            'repo' => $_POST['project-repo'],
            'description' => $_POST['project-desc'],
            'version' => $_POST['project-version'],
            'build' => $_POST['project-build'],
            'rel' => $_POST['project-release'],
            'typ' => $_POST['project-type'],
            'tags' => $_POST['project-tags'],
            'status' => $_POST['project-class']
        ], [
            'id', '=', $_SESSION['project-id'],
            'AND',
            'username', '=', $_SESSION[SESSION_USER_NAME]
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "There was an SQL error");
            header("Locaton: /edit-project");
            exit;
        }

        if ($this->isError())
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, $this->isError(true));
            header("Location: /dashboard");
            exit;
        }

        $this->__unsetProjectSessionVars();

        $this->messages->_pushMessage(MESSAGES_NOTIFY, "Project updated successfully");
        header("Location: /dashboard");

        exit;
    }


private function __setProjectSessionVars()
    {
        unset($_SESSION['project-edit']);

        $_SESSION['project-name'] = $_POST['project-name'];
        $_SESSION['project-repo'] = $_POST['project-repo'];
        $_SESSION['project-desc'] = $_POST['project-desc'];

        $_SESSION['project-version'] = $_POST['project-version'];
        $_SESSION['project-build'] = $_POST['project-build'];
        $_SESSION['project-release'] = $_POST['project-release'];
        
        $_SESSION['project-type'] = $_POST['project-type'];
        $_SESSION['project-class'] = $_POST['project-class'];

        $_SESSION['project-tags'] = $_POST['project-tags'];
    }


private function __unsetProjectSessionVars()
    {
        unset($_SESSION['project-username']);
        unset($_SESSION['project-id']);

        unset($_SESSION['project-edit']);

        unset($_SESSION['project-name']);
        unset($_SESSION['project-repo']);
        unset($_SESSION['project-desc']);

        unset($_SESSION['project-version']);
        unset($_SESSION['project-build']);
        unset($_SESSION['project-release']);

        unset($_SESSION['project-type']);
        unset($_SESSION['project-class']); 

        unset($_SESSION['project-tags']);
    }


public function viewUserProjects($username)
    {
        if (($projects = $this->findTableRow([
            'username', '=', $username
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Error finding projects for $username");
            header("Location: /");
            exit;
        }

        $this->_data['projects'] = $projects;
        $this->_data['username'] = $username;
        
        $this->render('Pages/view-user-projects.php', $this->_data);

        exit;
    }

}

