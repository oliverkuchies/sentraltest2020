<?php
namespace App;
use app\Loader\RouteManager\Router;

/*
 * Our beautiful router! Place the URL, the File (based in Controllers folder), and the method to call to return data :)
 */
Router::addRoute('', "View/Home", "loadHomePage");
//(If i had the time and this was a serious project i'd probably create a new parameter for addRoute for GET/POST to reduce routes)
//Route for pulling events
Router::addRoute("api/events", "API/Events", "getEvents");
//Route for categories
Router::addRoute('api/events/categories', 'API/Events', 'getEventCategories');
//Route for schools
Router::addRoute('api/events/schools', 'API/Events', 'getEventSchools');
//Route for school information
Router::addRoute('api/events/schools/[id]', 'API/Events', 'getSchool');
//Route for adding events
Router::addRoute("api/events/add", "API/Events", "addEvent");
//Route for removing events
Router::addRoute("api/events/remove", "API/Events", "getTestData");
//Route for updating events
Router::addRoute("api/events/update", "API/Events", "getTestData");
//Route for searching events
Router::addRoute('api/events/search', 'API/Events', 'getTestData');
//Route for individual event
Router::addRoute('api/events/[id]', 'API/Events', 'getEvent');

?>