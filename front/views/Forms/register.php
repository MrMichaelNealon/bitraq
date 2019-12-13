///////////////////////////////////////////////////////////
//
//  Registration form.
//
//  The styling for the input fields is in:
//
//      front/public/css/main.css
//
[CSS[
    #registration-form
    {
        position: relative;
        float: right;

        box-sizing: border-box;

        margin: 15vh 10vw;
        padding: 5vh 2.5vh;

        width: 80vw;
        height: auto;

        background: #FFF;
    }

    @media only screen and (min-width: 640px) {
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

