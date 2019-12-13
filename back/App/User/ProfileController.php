<?php


namespace App\User;


use App\Models\UserModel;
use App\Models\Session;


Class ProfileController extends UserModel
{

    protected           $_tableName = "user_profile";
    protected           $_tableSchema = [
        [ 'id', 'int', 'primary', 'auto', 'required' ],
        [ 'user_id', 'int', 'required' ],
        [ 'first_name', 'char', 48 ],
        [ 'last_name', 'char', 48 ],
        [ 'gen', 'char', 12 ],
        [ 'loc', 'char', 48 ],
        [ 'company', 'char', 48 ],
        [ 'bio', 'text', 4096 ],
        [ 'img', 'char', 255 ],
        [ 'status', 'char', 12, 'required' ]
    ];


public function createProfile($username)
    {
        $_user = new \App\User\AuthController();

        if (($_userInfo = $_user->findTableRow([
            'username', '=', $username
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Error creating user profile");
            header("Location: /dashboard");
            exit;
        }

        if ($this->insertTableRow([
            'user_id' => $_userInfo[0]['id'],
            'first_name' => '',
            'last_name' => '',
            'gen' => '',
            'loc' => '',
            'company' => '',
            'bio' => '',
            'img' => '',
            'status' => 'Public'
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /");
            exit;
        }

        if ($this->isError())
            $this->messages->_pushMessage(MESSAGES_ERROR, $this->isError());
    
        return true;
    }


public function viewProfile($username, $id)
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if (($_profile = $this->findTableRow(
            [ 'user_id', '=', $id ]
        )) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /dashboard");
            exit;
        }

        $this->_data['profile'] = $_profile[0];

        $this->render('Pages/view-profile.php', $this->_data);
        exit;
    }


public function checkFriends($userId, $profileId)
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        $_user = new \App\User\AuthController();

        if (($userInfo = $_user->findTableRow([
            'id', '=', $userId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Error looking up profile for user $userId");
            header("Location: /");
            exit;
        }

        if (($profileInfo = $_user->findTableRow([
            'id', '=', $profileId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Error looking up profile for user $profileId");
            header("Location: /");
            exit;
        }


    //  Both users should appear in one anothers friends list
    //
        if ($userInfo[0]['friends'] === "")
            $userFriends = Array();
        else
            $userFriends = preg_split('/;/', $userInfo[0]['friends'], null, PREG_SPLIT_NO_EMPTY);

        if ($profileInfo[0]['friends'] === "")
            $profileFriends = Array();
        else
            $profileFriends = preg_split('/;/', $profileInfo[0]['friends'], null, PREG_SPLIT_NO_EMPTY);
    
        if (
            (array_search($profileInfo[0]['username'], $userFriends) === false)
            ||
            (array_search($userInfo[0]['username'], $profileFriends) === false)
        )
            return false;

        return true;
    }


public function editProfile($username, $id, $noSession = false)
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
            header("Location: /dashboard");
            exit;
        }

        if (($_profile = $this->findTableRow([
            'id', '=', $id
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Error retrieving profile");
            header("Location: /dashboard");
            exit;
        }

        if ($noSession === false)
        {
            $_SESSION['first_name'] = $_profile[0]['first_name'];
            $_SESSION['last_name'] = $_profile[0]['last_name'];
            $_SESSION['gen'] = $_profile[0]['gen'];
            $_SESSION['loc'] = $_profile[0]['loc'];
            $_SESSION['company'] = $_profile[0]['company'];
            $_SESSION['bio'] = $_profile[0]['bio'];
        }        
        
        $_SESSION['user_id'] = $_profile[0]['user_id'];
        $_SESSION['img'] = $_profile[0]['img'];

        $this->render('Pages/edit-profile.php');
        exit;
    }


public function saveProfile($username, $id)
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
            header("Location: /dashboard");
            exit;
        }

        if ($this->validateInput($_POST, [
            'first-name' => 'Not specified',
            'last-name' => 'Not specified',
            'profile-gender' => 'Male',
            'location' => 'Not specified',
            'company' => 'Not specified',
            'biography' => 'Not specified'
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Input validation error");
            header("Location: /dashboard");
            exit;
        }

        if ($this->updateTableRow([
            'first_name' => $_POST['first-name'],
            'last_name' => $_POST['last-name'],
            'gen' => $_POST['profile-gender'],
            'loc' => $_POST['location'],
            'company' => $_POST['company'],
            'bio' => $_POST['biography']
        ], [
            'user_id', '=', $id
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /dashboard");
            exit;
        }

        $this->messages->_pushMessage(MESSAGES_NOTIFY, "Profile updated successfully");
        
        header("Location: /dashboard");
        exit;
    }


public function uploadImage($username, $id)
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if (! isset($_FILES['img']))
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "No file selected?");
            header("Location: /edit-profile/$username/$id");
            exit;
        }

        $_SESSION['first_name'] = $_POST['upload-first-name'];
        $_SESSION['last_name'] = $_POST['upload-last-name'];
        $_SESSION['gen'] = $_POST['upload-profile-gen'];
        $_SESSION['loc'] = $_POST['upload-location'];
        $_SESSION['company'] = $_POST['upload-company'];
        $_SESSION['bio'] = $_POST['upload-biography'];

        $_file = $_FILES['img'];

        $_fileName = $_file['name'];
        $_fileTemp = $_file['tmp_name'];
        $_fileSize = $_file['size'];
        $_fileToks = explode('.', $_fileName);
        $_fileExtn = strtolower(end($_fileToks));

        $_fileType = Array('png', 'jpg');

        if (! in_array($_fileExtn, $_fileType))
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Incorrect file type - only .png and .jpg are allowed");
            header("Location: /edit-profile/$username/$id");
            exit;
        }

        if ($_file['error'] !== 0)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "1 Error uploading file!");
            header("Location: /edit-profile/$username/$id");
            exit;
        }

        if ($_fileSize > 2097152)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "File exceeds size limit of 2MB");
            header("Location: /edit-profile/$username/$id");
            exit;
        }

        $_filePath = uniqid('', true) . $_fileExtn;
        $_fileDest = __buildpath(Array(PATH_ROOT, "store", "Images", $_filePath));
    
        if (! move_uploaded_file($_fileTemp, $_fileDest))
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Error uploading file!");
            header("Location: /edit-profile/$username/$id");
            exit;
        }

        $this->__unlinkProfileImage($username, $id);
        $this->messages->_pushMessage(MESSAGES_NOTIFY, "File uploaded successfully");

        if ($this->updateTableRow([
            'img' => $_fileDest
        ], [
            'user_id', '=', $id
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /edit-profile/$username/$id");
            //$this->render('Pages/edit-profile.php');
            exit;
        }
        //$this->render('Pages/edit-profile.php');

        //header("Location: /edit-profile/$username/$id");
        
        $this->editProfile($username, $id, true);
        exit;
    }


public function __unlinkProfileImage($username, $id)
    {
        if (($_profile = $this->findTableRow([
            'user_id', '=', $id
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /edit-profile/$username/$id");
            exit;
        }

        if ($_profile[0]['img'] !== "")
            unlink($_profile[0]['img']);

        if ($this->updateTableRow([
            'img' => ''
        ], [
            'user_id', '=', $id
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /edit-profile/$username/$id");
            exit;
        }
    }


public function getProfileImage()
    {
        if (Session::loggedIn() === false)
        {
            return __buildpath(Array(PATH_ROOT, "store", "Images", "index.png"));
        }

        if (($_profile = $this->findTableRow([
            'user_id', '=', $_SESSION[SESSION_USER_ID]
        ])) === false)
        {
            return __buildpath(Array(PATH_ROOT, "store", "Images", "index.png"));
        }

        if ($_profile[0]['img'] === '')
            return __buildpath(Array(PATH_ROOT, "store", "Images", "index.png"));
    
        return $_profile[0]['img'];
    }


public function friendRequest($username, $userId, $friendId)
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if ($_SESSION[SESSION_USER_ID] !== $userId)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        if ($this->checkFriends($userId, $friendId) === true)
        {
            $this->messages->_pushMessage(MESSAGES_NOTIFY, "You are already friends with ths user!");
            header("Location: /dashboard");
            exit;
        }

        $myInfo = new \App\User\AuthController();
        $userInfo = new \App\User\AuthController();

        if (($_myInfo = $myInfo->findTableRow([
            'username', '=', $username,
            'AND',
            'id', '=', $userId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error!");
            header("Location: /dashboard");
            exit;
        }

        if (($_userInfo = $userInfo->findTableRow([
            'id', '=', $friendId
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error!");
            header("Location: /dashboard");
            exit;
        }

        if ($myInfo->updateTableRow([
            'friends' => $_myInfo[0]['friends'] . ';' . $_userInfo[0]['username']
        ], [
            'username', '=', $username,
            'AND',
            'id', '=', $userId
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error!");
            header("Location: /dashboard");
            exit;
        }

        if ($userInfo->updateTableRow([
            'notifications' => $_userInfo[0]['notifications'] . 'FriendRequest:' . $_myInfo[0]['username'] . ';'
        ], [
            'id', '=', $friendId
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error!");
            header("Location: /dashboard");
            exit;
        }

        $this->messages->_pushMessage(MESSAGES_NOTIFY, "Friend request sent to user " . $_userInfo[0]['username']);
        header("Location: /dashboard");

        die();
    }


public function viewNotifications()
    {
        if (Session::loggedIn() === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Permission denied");
            header("Location: /");
            exit;
        }

        $userInfo = new \App\User\AuthController();

        if (($_notifications = $userInfo->findTableRow([
            'username', '=', $_SESSION[SESSION_USER_NAME],
            'AND',
            'id', '=', $_SESSION[SESSION_USER_ID]
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /dashboard");
            exit;
        }

        $this->_data['notifications'] = $_notifications[0]['notifications'];
        $this->render('Pages/notifications.php', $this->_data);

        exit;
    }


public function acceptFriendRequest($username, $friendName, $index)
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

        $_user = new \App\User\AuthController();
        if (($_userInfo = $_user->findTableRow([
            'username', '=', $username
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /dashboard");
            exit;
        }

        $_userInfo[0]['friends'] .= $friendName . ';';

        $this->removeNotification($_userInfo, $index);

        $this->messages->_pushMessage(MESSAGES_NOTIFY, "You are now friends with <b>$friendName</b>");
        header("Location: /notifications");

        exit;
    }


public function rejectFriendRequest($username, $friendName, $index)
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

        $_user = new \App\User\AuthController();
        if (($_userInfo = $_user->findTableRow([
            'username', '=', $username
        ])) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /dashboard");
            exit;
        }

        $_friends = "";
        $_friendsList = preg_split('/;/', $_userInfo[0]['friends'], null, PREG_SPLIT_NO_EMPTY);

        foreach ($_friendsList as $friend)
        {
            if ($friend !== $friendName)
                $_friends .= $friend . ';';
        }

        $_userInfo[0]['friends'] = $_friends;
        $this->removeNotification($_userInfo, $index);
        $this->removeFriendRequest($friendName, $username);

        $this->messages->_pushMessage(MESSAGES_NOTIFY, "You are now friends with <b>$friendName</b>");
        header("Location: /notifications");

        exit;
    }


public function removeNotification($_userInfo, $index)
    {
        $_notifications = preg_split('/;/', $_userInfo[0]['notifications'], null, PREG_SPLIT_NO_EMPTY);
    
        if (count($_notifications) > $index)
            array_splice($_notifications, $index, 1);
        
        $notifications = "";
        foreach ($_notifications as $notification)
        {
            $notifications .= $notification . ';';
        }

        $_user = new \App\User\AuthController();
        if ($_user->updateTableRow([
            'friends' => $_userInfo[0]['friends'],
            'notifications' => $notifications
        ], [
            'username', '=', $_userInfo[0]['username']
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /dashboard");
            exit;
        }

        return true;
    }


public function removeFriendRequest($friendName, $username)
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

        $_user = new \App\User\AuthController();
        if (($_userInfo = $_user->findTableRow([
            'username', '=', $friendName
        ])) === false) 
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "SQL Error");
            header("Location: /dashboard");
            exit;
        }

        $_friends = "";
        $_friendsList = preg_split('/;/', $_userInfo[0]['friends'], null, PREG_SPLIT_NO_EMPTY);

        foreach ($_friendsList as $friend)
        {
            if ($friend !== $username)
                $_friends .= $friend . ';';
        }

        if ($_user->updateTableRow([
            'friends' => $_friends
        ], [
            'username', '=', $friendName
        ]) === false)
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "!!!SQL Error");
            header("Location: /dashboard");
            exit;
        }

        $this->messages->_pushMessage(MESSAGES_NOTIFY, "Friend request from <b>$friendName</b> rejected");
        headeR("Location: /notifications");

        exit;
    }

}

