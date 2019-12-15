///////////////////////////////////////////////////////////
//
//  Sign in form.
//
//  The styling for the input fields is in:
//
//      front/public/css/main.css
//
[CSS[   
    #signin-copy {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 2.5vh 0vw;
        padding: 5vh 2.5vh;

        width: 80vw;
        height: auto;

        font-family: Helvetica, sans-serif;
        font-weight: bold;
        font-size: 5vh;

        text-align: center;

        color: #FF8C00;
        text-shadow: 2px 2px 2px #000;

        background: rgba(255, 255, 255, 0);
    }

    #signin-form
    {
        position: relative;
        float: right;

        box-sizing: border-box;

        margin: 5vh 10vw;
        padding: 5vh 2.5vh;

        width: 80vw;
        height: auto;

        background: rgba(255, 255, 255, 0.80);
    }

    @media only screen and (min-width: 640px) {
        #signin-copy {
            margin: 10vh 2.5vw 0 5vw;
            width: 30vw;
            font-size: 6vh;
            text-align: left;
        }

        #signin-form { 
            margin: 15vh 5vw 0 0vw;
            width: 50vw;
        }
    }

    @media only screen and (min-width: 1000px) {
        #signin-form { 
            margin: 15vh 5vw 0 0vw;
            width: 40vw;
        }
    }
]CSS]

///////////////////////////////////////////////////////////
//
//  Redirect logged in users to the dashboard.
//
<?php
    if (\App\Models\Session::loggedIn() === true) {
        header("Location: /dashboard");
        exit;
    }
?>

    <div id="signin-copy">
        Clean your code of creepy crawlies!
    </div>


///////////////////////////////////////////////////////////
//
//  Sign in form.
//
//  Triggers the POST /signin request - see:
//
//      front/routes/post.routes.php
//
    <form id="signin-form" method="POST" action="/signin">
    
        <h1>Sign In</h1>

        @partial ../Components/error-messages.php
        @partial ../Components/notifications.php

        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">
        
        <input type="submit" value="Sign In">

    </form>


    <script>
        var     __bgPath = "<?php echo __buildPath(Array(PATH_ROOT, "store", "Images", "BackgroundCaterpillar.jpg")); ?>";
        var     __bgSmallPath = "<?php echo __buildPath(Array(PATH_ROOT, "store", "Images", "BackgroundCaterpillarSmall.jpg")); ?>";
    
        $(document).ready(function() {

        $(window).on("resize", function() {
            __resizeBg();
        });

        function __resizeBg() {
            var     __screenWidth = window.innerWidth;
            var     __screenHeight = window.innerHeight;

            if (__screenWidth >= 640)
            {
                $("body").css({
                    'background': "url(" + __bgPath + ")",
                    'background-position': "0 0vh",
                    'background-size': '100% 100vh',
                    'background-repeat': 'no-repeat',
                    'overflow': 'hidden'
                });
            }
            else 
            {
                $("body").css({
                    'background': "url(" + __bgSmallPath + ")",
                    'background-position': "0 0vh",
                    'background-size': '100% 120%',
                    'background-repeat': 'no-repeat',
                    'overflow': 'hidden'
                });
            }
        }

        __resizeBg();
        });
    </script>