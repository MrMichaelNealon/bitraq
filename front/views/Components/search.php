
[CSS[
    #searchbar {
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
    }

        #searchbar input[type="text"] {
            box-sizing: border-box;
            margin-top: 0;
            margin-bottom: 0;
            width: 75%;
        }

        #searchbar input[type="submit"] {
            float: right;
            box-sizing: border-box;
            margin-top: 0;
            margin-bottom: 0;
            width: 25%;
            cursor: pointer;
        }

    .search-row {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 0 1.5vw 1px 1.5vw;
        padding: .5vw;

        font-family: Helvetica, sans-serif;
        font-weight: bold;
        font-size: 12px;

        width: 96vw;
        height: auto;

        background: #FFF;
    }

        .search-row img {
            position: relative;
            float: left;

            margin-top: .5vh;

            width: 15vw;
            height: 15vh;
        }

        .search-info {
            position: relative;
            float: left;

            box-sizing: border-box;

            margin: 0 0vh 1.5vh 0vh;
            padding: .5vh .5vh 0 .5vh;

            width: 75vw;
            height: auto;

            color: #FF8C00;
        }

        .search-header {
            position: relative;
            float: left;

            box-sizing: border-box;

            padding-left: 2.5vw;

            font-size: 16px;

            width: 100%;
            height: 3vh;

            color: #FF8C00;
            text-shadow: 1px 1px 1px #000;
        }

        .search-bio {
            position: relative;
            float: left;

            box-sizing: border-box;

            padding-left: 2.5vw;

            font-size: 14px;

            width: 100%;
            height: 8vh;

            color: #000;

            overflow: hidden;
        }

        .search-user-stats {
            position: relative;
            float: left;

            box-sizing: border-box;

            margin-top: 1vh;
            padding-left: 2.5vw;

            font-size: 12px;

            width: 79vw;
            height: 3vh;
        }

            .search-user-stats-section {
                position: relative;
                float: left;

                box-sizing: border-box;
                padding: 0;

                width: 33.33%;
                height: 3vh;

                line-height: 3vh;
            }

                .search-user-stats-half {
                    position: relative;
                    float: left;

                    width: auto;
                    height: 3vh;

                    color: #FF8C00;
                }

            .search-user-stats a {
                font-size: 12px;
            }

    .search-results {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 2.5vh 1.5vw 0.5vh 1.5vw;
        padding: .5vh;

        width: 100%;
        height: auto;

        color: #000;
    }

    @media only screen and (min-width: 640px) {
        .search-user-stats {
            font-size: 14px;
        }

        .search-user-stats a {
            font-size: 14px;
        }
    }
]CSS]

<?php if (! isset($_SESSION['search_query'])) $_SESSION['search_query'] = ""; ?>

<h2>Search</h2>

<form id="searchbar" method="POST" action="/search">
    <input type="text" name="search-query" required placeholder="<?php echo $_SESSION['search_query']; ?>">
    <input type="submit" value="Submit">
</form>


<?php

//  Users search results.
//
    if (isset($this->_data['users']) && count($this->_data['users']) > 0)
    {    
        echo "<p class='search-results'>Found " . count($this->_data['users']) . " matching users";

        foreach ($this->_data['users'] as $user)
        {
            if ($user['status'] === 'Private')
                continue;

            $_profile = new \App\User\ProfileController();
            $_projects = new \App\User\ProjectController();
            $_reports = new \App\User\ReportController();

            if (($projects = $_projects->findTableRow([
                'username', '=', $user['username']
            ])) === false)
                $projects = Array();

            if (($reports = $_reports->findTableRow([
                'username', '=', $user['username']
            ])) === false)
                $reports = Array();
    
            if (($profile = $_profile->findTableRow([
                'user_id', '=', $user['id']
            ])) === false)
            {
                $_userImage = __buildpath(Array(PATH_ROOT, "store", "Images", "index.png"));
            }
            else
            {
                if ($profile[0]['img'] === "")
                    $_userImage = __buildpath(Array(PATH_ROOT, "store", "Images", "index.png"));
                else
                    $_userImage = $profile[0]['img'];
            }

            echo "<div class='search-row'>
                <img src='$_userImage'>
                <div class='search-info'>
                    <a title='View profile for user " . $user['username'] . "' href='/view-profile/{$user['username']}/{$user['id']}'>
                        <div class='search-header'>
                            {$user['username']}
                        </div>
                    </a>

                <div class='search-bio'>
                    {$profile[0]['bio']}
                </div>

                <div class='search-user-stats'>
                    <div class='search-user-stats-section' style='text-align: center;'>
                        <div class='search-user-stats-half' style='color: #000;'>
                            Score
                        </div>
                        <div class='search-user-stats-half' style='padding-left: 1.5vw;'>
                            {$user['score']}
                        </div>
                    </div>
                
                    <div class='search-user-stats-section''>
                        <div class='search-user-stats-half' style='color: #000; width: 50%; text-align: right'>
                            Projects
                        </div>
                        <div class='search-user-stats-half' style='padding-left: 1.5vw;'>
                            <a title='View projects for user " . $user['username'] . "' href='/view-user-projects/" . $user['username'] . "'>" . count($projects) . "</a>
                        </div>
                    </div>

                    <div class='search-user-stats-section'>
                        <div class='search-user-stats-half' style='float: right; padding-left: 1.5vw;'>
                            <a title='View reports for user " . $user['username'] . "' href='/view-project-reports/" . $user['username'] . '/' . $user['id'] . "'>" . count($reports) . "</a>
                        </div>
                        <div class='search-user-stats-half' style='float: right; color: #000;'>
                            Reports
                        </div>
                    </div>
                </div>
                </div>
            </div>";
        }
    }

//  Projects search results.
//
//  Still to be implemented.
//

//  Reports search results.
//
//  Still to be implemented.
//
?>

