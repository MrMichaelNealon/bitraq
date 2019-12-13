<?php


use App\Controllers\Route;


/**********************************************************
 *
 *  front/routes/post.routes.php
 * 
 */


    Route::post('/register', '\App\User\AuthController:registerUser');
    Route::post('/verification/$user/$id', '\App\User\AuthController:validateUser');

    Route::post('/signin', '\App\User\AuthController:signInUser');

    Route::post('/edit-profile/$username/$id', '\App\User\ProfileController:saveProfile');
    Route::post('/image-upload/$username/$id', '\App\User\ProfileController:uploadImage');

    Route::post('/add-project', '\App\User\ProjectController:addNewProject');
    Route::post('/update-project', '\App\User\ProjectController:updateProject');

    Route::post('/submit-report/$id', '\App\User\ReportController:submitReport');
    Route::post('/edit-report/$username/$project/$projectId/$id', '\App\User\ReportController:updateReport');
    Route::post('/submit-report-reply/$username/$projectId/$reportId', '\App\User\ReportController:submitSolution');

    Route::post('/search', '\App\User\HomeController:submitSearch');
