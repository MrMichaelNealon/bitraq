///////////////////////////////////////////////////////////
//
//  The template will expand the SECTION_BODY section
//  directive to any code defined in this file.
//
@template ../Templates/layout.php


///////////////////////////////////////////////////////////
//
//  Report manager page.
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
//  Logged out users will see the report-manager
//  component.
//
                    @partial ../Components/report-manager.php
