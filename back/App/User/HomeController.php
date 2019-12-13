<?php


namespace App\User;


use App\Models\UserModel;


Class HomeController extends UserModel
{

public function getPage()
    {
        $this->render("Pages/home.php");
    }


public function search()
    {
        $this->_data['users'] = Array();
        $this->_data['projects'] = Array();
        $this->_data['reports'] = Array();

        $_SESSION['query-string'] = '';

        $this->render('Pages/search.php', $this->_data);
    }


public function submitSearch()
    {
        $_queryString = filter_var($_POST['search-query'], FILTER_SANITIZE_STRING);

        $users = new \App\User\AuthController();
        $projects = new \App\User\ProjectController();
        $reports = new \App\User\ReportController();

        if (($userList = $users->getRowsLike('username', $_queryString)) === false)
        {
            $userList = Array();
        }
        
        if (($projectList = $projects->getRowsLike('name', $_queryString)) === false)
        {
            $projectList = Array();
        }
        
        if (($reportList = $reports->getRowsLike('title', $_queryString)) === false)
        {
            $reportList = Array();
        }

        if ($this->isError())
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, $this->isError());
            return false;
        }
        
        $this->_data['users'] = $userList;
        $this->_data['projects'] = $projectList;
        $this->_data['reports'] = $reportList;

        $_SESSION['query-string'] = $_queryString;

        $this->render('Pages/search.php', $this->_data);
        exit;
    }

}

