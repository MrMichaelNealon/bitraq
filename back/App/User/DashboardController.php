<?php


namespace App\User;


use App\Models\Session;
use App\Models\UserModel;


Class DashboardController extends UserModel
{

public function getPage()
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        $_projects = new \App\User\ProjectController();

        if (! isset($this->_data))
            $this->_data = Array();

        $this->_data['projects'] = $_projects->findTableRow([
            'username', '=', $_SESSION[SESSION_USER_NAME]
        ]);

        $this->render("Pages/dashboard.php", $this->_data);
    }


public function getAddProjectPage()
    {
        $this->render("Pages/add-project.php");
    }

}

