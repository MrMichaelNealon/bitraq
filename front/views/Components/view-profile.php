<?php

    if (! isset($this->_data['profile']))
    {
        $this->messages->_pushMessage(MESSAGES_ERROR, "Error retrieving profile");
        header("Location: /");
        exit;
    }
    

    if ($this->_data['profile']['status'] !== 'Public')
    {
    //  If the profile is protected, then only friends may
    //  view the profile.
    //
    //  If it's private, nobody other than the user may
    //  view the profile.
    //
        if (! isset($_SESSION[SESSION_USER_NAME]))
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Selected user profile is private");
            header("Location: /");
            exit;
        }

        if ($_SESSION[SESSION_USER_ID] !== $this->_data['profile']['user_id'] && $this->_data['profile']['status'] === 'Private')
        {
            $this->messages->_pushMessage(MESSAGES_ERROR, "Selected user profile is private");
            header("Location: /");
            exit;
        }

        $_profile = new \App\User\ProfileController();

        if ($_SESSION[SESSION_USER_ID] !== $this->_data['profile']['id'])
        {
            if ($_profile->checkFriends($_SESSION[SESSION_USER_ID], $this->_data['profile']['id']) === false)
            {
                $this->messages->_pushMessage(MESSAGES_ERROR, "Selected user profile is protected, you are not friends");
                header("Location: /");
                exit;
            }
        }
    }

    if ($this->_data['profile']['first_name'] === "")
        $this->_data['profile']['first_name'] = "Not specified";

    if ($this->_data['profile']['last_name'] === "")
        $this->_data['profile']['last_name'] = "Not specified";

    if ($this->_data['profile']['gen'] === "")
        $this->_data['profile']['gen'] = "Not specified";

    if ($this->_data['profile']['loc'] === "")
        $this->_data['profile']['loc'] = "Not specified";

    if ($this->_data['profile']['company'] === "")
        $this->_data['profile']['company'] = "Not specified";

    if ($this->_data['profile']['bio'] === "")
        $this->_data['profile']['bio'] = "Not specified";

    if ($this->_data['profile']['img'] === "")
        $this->_data['profile']['img'] = "[STORE[Images/index.png]]";

    $_user = new \App\User\AuthControlleR();
    if (($_userInfo = $_user->findTableRow([
        'id', '=', $this->_data['profile']['user_id']
    ])) === false)
    {
        $_userScore = 0;
    }
    else
    {
        $_userScore = $_userInfo[0]['score'];
    }
?>

[CSS[
    
    #user-profile {
        position: relative;
        float: left;

        clear: both;

        box-sizing: border-box;

        margin: 1.5vw .5vw 1.5vw 1.5vw;
        padding: .5vw;

        width: 54vw;
        height: auto;

        font-family: Helvetica, sans-serif;
        font-weight: bold;
        font-size: 12px;

        background: #FFF;
    }

        .profile-header {
            position: relative;
            float: left;

            box-sizing: border-box;

            width: 100%;
            height: auto;

            color: #222;
        }

        .profile-field {
            position: relative;
            float: left;

            box-sizing: border-box;

            padding: 0 .5vw;

            margin: 1vh 0vh 2.5vh 0vh;
            width: 100%;
            height: auto;

            color: #FF8C00;
        }

    #user-image {
        position: relative;
        float: right;

        box-sizing: border-box;

        margin: 1.5vw 1.5vw 0vw 0vw;
        padding: .5vw;

        width: 40vw;
        height: auto;

        background: #FFF;
    }

        #user-image img {
            position: relative;
            float: left;

            width: 100%;
            height: auto;
        }

    #user-stats {
        position: relative;
        float: right;

        box-sizing: border-box;

        margin: .5vw 1.5vw 0vw 0vw;
        padding: .5vw;

        width: 40vw;
        height: auto;

        background: #FFF;
    }

        .user-stats-section {
            position: relative;
            float: right;

            width: 33.33%;
            height: 5vh;

            line-height: 5.5vh;

            font-family: Helvetica, sans-serif;
            font-size: 12px;
        }

            .user-stats-half {
                position: relative;
                float: left;

                width: 50%;
                height: 2.5hv;
            }

    .buttons a {
        font-size: 12px !important;
    }

    .buttons a:hover {
        font-weight: bold !important;
        text-decoration: underline !important;
    }

    @media only screen and (min-width: 640px) {
        #user-profile {
            font-size: 14px;
            width: 69vw;
        }

        #user-image {
            width: 25vw;
        }

        #user-stats {
            width: 25vw;
        }

        .buttons a {
            font-size: 14px !important;
        }
    }
]CSS]


    <h2>User Profile</h2>

    <div id="user-profile">
    <div class="profile-header" id="profile-header-first-name">
            First Name
        </div>
        <div class="profile-field" id="profile-first-name">
            <?php echo $this->_data['profile']['first_name']; ?>
        </div> 

        <div class="profile-header" id="profile-header-last-name">
            Last Name
        </div>
        <div class="profile-field" id="profile-last-name">
            <?php echo $this->_data['profile']['last_name']; ?>
        </div> 
        
        <div class="profile-header" id="profile-header-gender">
            Gender
        </div>
        <div class="profile-field" id="profile-gender">
            <?php echo $this->_data['profile']['gen']; ?>
        </div>
        
        <div class="profile-header" id="profile-header-location">
            Location
        </div>
        <div class="profile-field" id="profile-location">
            <?php echo $this->_data['profile']['loc']; ?>
        </div>
        
        <div class="profile-header" id="profile-header-company">
            Company
        </div>
        <div class="profile-field" id="profile-company">
            <?php echo $this->_data['profile']['company']; ?>
        </div>
        
        <div class="profile-header" id="profile-header-bio">
            Biography
        </div>
        <div class="profile-field" id="profile-bio">
            <?php echo $this->_data['profile']['bio']; ?>
        </div>
    </div>

    <div id="user-image">
        <?php echo "<img src=" . $this->_data['profile']['img'] . ">"; ?>
    </div>

    <div id="user-stats">
        <div class="user-stats-section">
            <div class="user-stats-half" style="text-align: right;">
                Score
            </div>
            <div class="user-stats-half" style="padding-left: .5vw; width: calc(50% - .5vw); color: #0A0;">
                <?php echo $_userScore; ?>
            </div>
        </div>
    </div>


<?php
    if (\App\Models\Session::loggedIn() === true)
    {
        if ($_SESSION[SESSION_USER_ID] === $this->_data['profile']['user_id'])
        {
            echo "
                <div class=\"buttons\">
                    <a href=\"/edit-profile/" . $_SESSION[SESSION_USER_NAME] . '/' . $_SESSION[SESSION_USER_ID] . "\">
                        Edit profile
                    </a>
                </div>
            ";
        }
        else
        {
            if ($_profile->checkFriends($_SESSION[SESSION_USER_ID], $this->_data['profile']['user_id']) === false) {
                echo "
                    <div class=\"buttons\">
                        <a href=\"/friend-request/" . $_SESSION[SESSION_USER_NAME] . '/' . $_SESSION[SESSION_USER_ID] . '/' . $this->_data['profile']['user_id'] . "\">
                            Add Friend
                        </a>
                    </div>
                ";
            }
            else
            {
                echo "
                    <div class=\"buttons\">
                        <a href=\"/remove-friend/" . $_SESSION[SESSION_USER_NAME] . '/' . $_SESSION[SESSION_USER_ID] . '/' . $this->_data['profile']['user_id'] . "\">
                            Unfriend
                        </a>
                    </div>
                ";
            }
        }
    }
?>