[CSS[
    #dashboard-options {
        position: fixed;

        padding: 0 2.5vh;

        top: 10vh;
        right: 0px;

        width: 100%;
        height: 0;
        
        background: #000;

        z-index: 1000;
    }

    #dashboard-options a {
        margin-right: 2.5vw;
        visibility: hidden;

        font-familt: Helvetica, sans-serif;
        font-size: 14px;
    }

    @media only screen and (min-width: 640px) {
        #dashboard-options a {
            font-size: 16px;
        }
    }
]CSS]

    <div id="dashboard-options">
        <a href="/dashboard">Dashboard</a> 
        <a href="/view-profile/<?php echo $_SESSION[SESSION_USER_NAME] . '/' . $_SESSION[SESSION_USER_ID]; ?>">Profile</a>
        <a href="/search">Search</a>
        <a href="/sign-out">Sign Out</a>
    </div>

    <script>
        $(document).ready(function() {
            $(window).on("scroll", function() {
                $("#dashboard-options a").css("visibility", "hidden");
                $("#dashboard-options").css("height", "0px");
            });

            $("#toggle-dash-options").on("click", function() {
                if ($("#dashboard-options").css("height") == "0px") {
                    $("#dashboard-options").css("height", "auto");
                    $("#dashboard-options a").css("visibility", "visible");
                }
                else {
                    $("#dashboard-options a").css("visibility", "hidden");
                    $("#dashboard-options").css("height", "0px");
                }
            });
        });
    </script>
