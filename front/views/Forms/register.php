///////////////////////////////////////////////////////////
//
//  Registration form.
//
//  The styling for the input fields is in:
//
//      front/public/css/main.css
//
[CSS[

    #registration-copy {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 2.5vh 10vw;
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

    #registration-form
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
        #registration-copy {
            margin: 10vh 2.5vw 0 5vw;
            width: 30vw;
            font-size: 6vh;
            text-align: left;
        }
        #registration-form { 
            margin: 15vh 2.5vw 0 0;
            width: 50vw;
        }
    }

    @media only screen and (min-width: 1000px) {
        #registration-form { 
            margin: 15vh 2.5vw 0 0;
            width: 30vw;
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

    <div id="registration-copy">
        Let&#39;s work together and beat the bugs!
    </div>


///////////////////////////////////////////////////////////
//
//  Registration form.
//
//  Triggers the POST /register request - see:
//
//      front/routes/post.routes.php
//
    <form id="registration-form" method="POST" action="/register">
    
        <h1>Sign Up</h1>

        @partial ../Components/error-messages.php
        @partial ../Components/notifications.php

        <input type="text" name="username" placeholder="Username">
        <input type="email" name="email" placeholder="Email Address">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="password-confirm" placeholder="Password-confirm">
        
        <input type="submit" value="Sign Up">

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
                    'background-size': '100% 100%',
                    'background-repeat': 'no-repeat',
                    'overflow': 'hidden'
                });
            }
        }

        __resizeBg();
        });
    </script>