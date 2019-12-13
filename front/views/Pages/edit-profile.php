///////////////////////////////////////////////////////////
//
//  The template will expand the SECTION_BODY section
//  directive to any code defined in this file.
//
@template ../Templates/layout.php


///////////////////////////////////////////////////////////
//
//  Edit profile page.
//

            @partial ../Components/header.php


///////////////////////////////////////////////////////////
//          
//  Logged out users are redirected to the home page.
//
<?php if (\App\Models\Session::loggedIn() === false) {
    header("Location: /");
    exit;
}
?>


///////////////////////////////////////////////////////////
//
                    @partial ../Forms/edit-profile.php
