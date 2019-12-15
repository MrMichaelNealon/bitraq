<?php


namespace App\User;

use App\Models\UserModel;
use App\Models\Session;

use App\User\EmailController;

use App\Vendor\PHPMailer;


    __defifndef('SESSION_ACCT_STATUS',  '__session_acct_status');

    __defifndef('SESSION_ACCT_BASIC',   'Basic');
    __defifndef('SESSION_ACCT_SUPER',   'Super');


Class AuthController extends UserModel
{

    protected           $_tableName = "users";
    protected           $_tableSchema = [
        [ 'id', 'int', 'required', 'primary', 'auto' ],
        [ 'username', 'char', 48, 'required' ],
        [ 'email', 'char', 120, 'required' ],
        [ 'password', 'char', 255, 'required' ],
        [ 'status', 'char', 32, 'required' ],
        [ 'created_at', 'char', 32, 'required' ],
        [ 'last_login', 'char', 120, 'required' ],
        [ 'friends', 'text', 65535 ],
        [ 'score', 'int', 'required' ],
        [ 'notifications', 'text', 65535 ],
        [ 'fails', 'int', 'required' ]
    ];


public function getSignInPage()
    {
        $this->render("Pages/sign-in.php");
    }


public function signInUser()
    {
    //  The validateInput() method is part of the
    //  UserModel, see:
    //
    //      back/App/Models/UserModel.php
    //
        if ($this->validateInput($_POST, [
            'username' => true,
            'password' => true
        ]) === false)
        {
            header("Location: /sign-in");
            exit;
        }

        if (($_row = $this->findTableRow([
            'username', '=', $_POST['username']
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Invalid form input");
            header("Location: /sign-in");
            exit;
        }

        if (count($_row) < 1) {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Sign in failed");
            header("Location: /sign-in");
            exit;
        }
        
        if (password_verify($_POST['password'], $_row[0]['password']) === false)
            return $this->__recordLoginFail($_POST['username'], $_row[0]['fails']);

        if (substr($_row[0]['last_login'], 0, 4) == "http")
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Account not verified - check your email");
            header("Location: /sign-in");
            exit;
        }

        if ($_row[0]['fails'] >= 3)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Account locked - please contact site administrator");
            header("Location: /sign-in");
            exit;
        }

        if ($this->updateTableRow([
            'last_login' => date('d/m/Y H:i:s'),
            'fails' => 0
        ], [
            'username', '=', $_POST['username']
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /sign-in");
            exit;
        }

        $_SESSION[SESSION_USER_ID] = $_row[0]['id'];
        $_SESSION[SESSION_USER_NAME] = $_row[0]['username'];
        $_SESSION[SESSION_USER_STATUS] = SESSION_LOGGED_IN;
        $_SESSION[SESSION_ACCT_STATUS] = $_row[0]['status'];

        header("Location: /dashboard");
        exit;
    }


private function __recordLoginFail($username, $fails)
    {
        if ($this->updateTableRow([
            'last_login' => date('d/m/Y H:i:s'),
            'fails' => ($fails + 1)
        ], [
            'username', '=', $username
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /sign-in");
            exit;
        }

        $this->messages->_pushMessage(MESSAGES_ERROR, "Sign in failed");
        header("Location: /sign-in");

        exit;
    }


public function registerUser()
    {
        if ($this->validateInput($_POST, [
            'username' => true,
            'password' => true,
            'email' => true,
            'password-confirm' => true,
        ]) === false) {
            header("Location: /");
            exit;
        }

    //  Check for existing username.
    //
        if ($this->__userExists($_POST['username']) === true)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "A user named {$_POST['username']} already exists");
            header("Location: /");
            exit;
        }

    //  Check if email already registers.
    //
        if ($this->__emailExists($_POST['email']))
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Email address {$_POST['email']} already registered");
            header("Location: /");
            exit;
        }

    //  All good - create the account.
    //
        if ($this->__createUser() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "There was an error creating the new user account, check your email address");
            header("Location: /");
            exit;
        }

    //  Create user profile.
    //
        $_profile = new \App\User\ProfileController();
        if ($_profile->createProfile(
            $_POST['username']
        ) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "There was an error creating the new user profile");
            header("Location: /");
            exit;
        }

        $this->messages->_pushMessage(MESSAGES_NOTIFY, "Account created - please visit your email inbox and verify your account");
        header("Location: /sign-in");

        return true;
    }


private function __userExists($username)
    {
        $_row = $this->findTableRow([
            'username', '=', $username
        ]);

        if ($_row === NULL)
        {
            $this->messages->_pushMessage(MESSAGES_NOTIFY, "There was an SQL server error");
            return false;
        }

        if (count($_row) == 1)
            return true;
        
        return false;
    }


private function __emailExists($email)
    {
        $_row = $this->findTableRow([
            'email', '=', $email
        ]);

        if ($_row === NULL)
        {
            $this->messages->_pushMessage(MESSAGES_NOTIFY, "There was an SQL server error");
            return false;
        }

        if (count($_row) == 1)
            return true;
        
        return false;
    }


private function __createUser()
    {
        $_mail = new EmailController();
        
        $_password = password_hash($_POST['password'], PASSWORD_BCRYPT, [ 'cost' => 12 ]);
        $_emailVerifyLink = $_mail->getVerifyURL($_POST['username']);
        
        if ($this->insertTableRow([
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => $_password,
            'status' => SESSION_ACCT_BASIC,
            'created_at' => date('d/m/Y H:i:s'),
            'last_login' => $_emailVerifyLink,
            'friends' => '',
            'score' => 0,
            'notifications' => '',
            'fails' => 0
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "There was an error creating the new user");
            header("Location: /");
            exit;
        }

    //  Verification email.
    //
        $_mail->sendVerifyEmail($_emailVerifyLink, $_POST['username'], $_POST['email']);

        return true;
    }


public function validateUser($username, $id)
    {
        $_row = $this->findTableRow([
            'username', '=', $username
        ]);

        if ($_row === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "There was an SQL error");
            header("Location: /");
            exit;
        }

        if (count($_row) < 1)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Account verification failed");
            header("Location: /");
            exit;
        }

        $_verificationCode = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        if ($_verificationCode != $_row[0]['last_login'])
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "....Account verification failed");
            header("Location: /");
            exit;  
        }
        
        if ($this->updateTableRow([
            'last_login' => date('d/m/Y H:i:s')
        ], [
            'username', '=', $username
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Account verification failed");
            header("Location: /");
            exit;
        }

        $this->messages->_pushMessage(MESSAGES_NOTIFY, "Account verified, please sign in");

        header("Location: /sign-in");
        exit;
    }


public function signOutUser()
    {
        $_SESSION[SESSION_USER_ID] = SESSION_ID_GUEST;
        $_SESSION[SESSION_USER_NAME] = SESSION_USER_GUEST;
        $_SESSION[SESSION_USER_STATUS] = SESSION_STATUS_NULL;
        unset($_SESSION[SESSION_ACCT_STATUS]);

        header("Location: /");
        exit;
    }


public function deleteNotification($username, $notifyId)
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

        if (($_userInfo = $this->findTableRow([
            'username', '=', $username
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        $_notifications = preg_split('/;/', $_userInfo[0]['notifications'], null, PREG_SPLIT_NO_EMPTY);
        $_newString = "";

        foreach ($_notifications as $index=>$notification)
        {
            if ($index === $notifyId)
                continue;
            $_newString .= $_newString . ";";
        }

        if ($this->updateTableRow([
            'notifications' => $_newString
        ], [
            'username', '=', $username
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        header("Location: /notifications");
        exit;
    }

}

