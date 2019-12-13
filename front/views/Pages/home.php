///////////////////////////////////////////////////////////
//
//  The template will expand the SECTION_BODY section
//  directive to any code defined in this file.
//
@template ../Templates/layout.php


///////////////////////////////////////////////////////////
//
//  Home page.
//

            @partial ../Components/header.php


///////////////////////////////////////////////////////////
//          
//  Logged in users are redirected to the dashboard.
//
<?php if (\App\Models\Session::loggedIn()) {
    header("Location: /dashboard");
    exit;
}
?>


///////////////////////////////////////////////////////////
//
//  Logged out users will see the registration form.
//
            @partial ../Forms/register.php
