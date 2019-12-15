<?php


use App\Controllers\Route;


/**********************************************************
 *
 *  front/routes/get.routes.php
 * 
 */

 
   Route::get('/', '\App\User\HomeController:getPage');
   Route::get('/home', '\App\User\HomeController:getPage');
   Route::get('/search', '\App\User\HomeController:search');

   Route::get('/sign-in', '\App\User\AuthController:getSignInPage');
   Route::get('/sign-out', '\App\User\AuthController:signOutUser');
   
   Route::get('/view-profile/$username/$id', '\App\User\ProfileController:viewProfile');
   Route::get('/edit-profile/$username/$id', '\App\User\ProfileController:editProfile');
   
   Route::get('/dashboard', '\App\User\DashboardController:getPage');
   Route::get('/add-project', '\App\User\DashboardController:getAddProjectPage');
   Route::get('/view-user-projects/$username', '\App\User\ProjectController:viewUserProjects');
   Route::get('/view-project-reports/$username/$projectId', '\App\User\ReportController:viewProjectReports');
   Route::get('/view-project-report/$projectId/$reportId', '\App\User\ReportController:viewProjectReport');
   Route::get('/submit-report-reply/$username/$reportId', '\App\User\ReportController:submitReportReply');
   
   Route::get('/edit-solution/$username/$reportId/$solutionId', '\App\User\SolutionController:editSolution');
   Route::get('/delete-solution/$username/$reportId/$replyId', '\App\User\SolutionController:deleteSolution');
   
   Route::get('/delete-project/$username/$id', '\App\User\ProjectController:deleteProject');
   Route::get('/edit-project/$username/$id', '\App\User\ProjectController:editProject');
   
   Route::get('/create-report/$username/$project/$id', '\App\User\ReportController:createReport');
   Route::get('/view-reports/$username/$id', '\App\User\ReportController:viewReports');
   Route::get('/edit-report/$username/$project/$id/$projectId', '\App\User\ReportController:editReport');
   Route::get('/delete-report/$username/$id/$reportId', '\App\User\ReportController:deleteReport');

   Route::get('/friend-request/$username/$userId/$friendId', '\App\User\ProfileController:friendRequest');
   Route::get('/accept-friend-request/$username/$friendName/$index', '\App\User\ProfileController:acceptFriendRequest');
   Route::get('/reject-friend-request/$username/$friendName/$index', '\App\User\ProfileController:rejectFriendRequest');
   
   Route::get('/notifications', '\App\User\ProfileController:viewNotifications');
