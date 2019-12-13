///////////////////////////////////////////////////////////
//
//  Sign in form.
//
//  The styling for the input fields is in:
//
//      front/public/css/main.css
//
[CSS[
    #signin-form
    {
        position: relative;
        float: left;

        box-sizing: border-box;

        margin: 15vh 10vw;
        padding: 5vh 2.5vh;

        width: 80vw;
        height: auto;

        background: #FFF;
    }

    @media only screen and (min-width: 640px) {
        #signin-form { 
            margin: 15vh 25vw 0 25vw;
            width: 50vw;
        }
    }

    @media only screen and (min-width: 1000px) {
        #signin-form { 
            margin: 15vh 30vw 0 30vw;
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

