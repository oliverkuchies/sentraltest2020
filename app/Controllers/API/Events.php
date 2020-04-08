<?php


namespace app\Controllers\API;

use app\Models\EventManager;
use app\Helpers;
class Events
{
    private $events = "";
    private $json = "";
    public function __construct(){
        $this->events = new EventManager();
        $this->json = new \stdClass();
    }
    public function addEvent(){
        /*
         * Data types / POST requests
         */
        //TODO - CSRF?
        $event_name = Helpers::Post("event_name");
        $event_description = Helpers::Post("event_description");
        $event_location = Helpers::Post("event_location");
        $event_date = Helpers::Post("event_date");
        $event_time = Helpers::Post("event_time");
        $organiser_num = Helpers::Post("organiser_num");
        $participant_num = Helpers::Post("participant_num");
        $school_id = Helpers::Post('school_id');
        $category_id = Helpers::Post('category_id');
        $event_distance = Helpers::Post('event_distance');
        $event_travel_time = Helpers::Post('event_travel_time');
        $current_user = 1;
        //TODO - Generate location coords with API
        $location_coords = Helpers::Post('event_coords');
        try {
            $event_location_id = $this->events->addNewLocation([
                'location_name' => $event_location,
                'location_coords' => $location_coords
            ]);
            $this->events->startTransaction();
            $event_id = $this->events->addNewEvent(
                [
                    'event_name' => $event_name,
                    'event_description' => $event_description,
                    'event_datetime' => date("Y-m-d H:i:s", strtotime("$event_date $event_time")),
                    'event_location_id' => $event_location_id,
                    'event_creator_id' => $current_user,
                    'event_school_id' => $school_id,
                    'event_category_id' => $category_id,
                    'event_distance' => $event_distance,
                    'event_travel_time' => $event_travel_time
                ]
            );
            for ($i = 1; $i <= $organiser_num; $i++) {
                //TODO - add check here to ensure organiser doesn't already exist.
                $this->events->addOrganiser(
                    [
                        "organiser_name" => Helpers::Post("organiser_name_$i"),
                        "organiser_school_id" => Helpers::Post("organiser_school_$i")
                    ],
                    $event_id
                );
            }
            for ($i = 1; $i <= $participant_num; $i++) {
                //TODO - add check here to ensure participant doesn't already exist.
                $this->events->addParticipant(
                    [
                        "participant_fname" => Helpers::Post("participant_fname_$i"),
                        "participant_lname" => Helpers::Post("participant_lname_$i"),
                        "participant_type" => Helpers::Post("participant_type_$i"),
                        "participant_email" => Helpers::Post("participant_email_$i")
                    ],
                    $event_id
                );
            }
            $this->events->commit();
            $this->json->success = true;
            $this->json->error = "";
        }
        catch (\Exception $e){
            $this->json->success = false;
            $this->json->error = $e->getMessage();
            $this->events->rollBack();
        }
        echo json_encode($this->json);
    }
    /*Get school information for a given school*/
    public function getSchool(){
        $id = Helpers::Get('id');
        try {
            $this->json->success = true;
            $this->json->error = "";
            $this->json->data = $this->events->getSchoolDetails($id);
        }
        catch (\Exception $e){
            $this->json->success = false;
            $this->json->error = $e->getMessage();
        }
        echo json_encode($this->json);
    }
    /*
     * Get events requested by the app
     */
    public function getEvents(){
        $search = false;
        $where_cond = "";
        $data = null;
        /*
         * A search if necessary.
         */
        if (Helpers::Get('start-date') !== ""){
            $where_cond .= "event_datetime >= :start_date";
            $start_date = Helpers::Get('start-date');
            $data = [];
            $data['start_date'] = $start_date;
        }
        if (Helpers::Get('end-date') != ""){
            if ($where_cond != ""){
                $where_cond .= " AND event_datetime <= :end_date";
            }
            else{
                $where_cond .= "event_datetime <= :end_date";
                $data = [];
            }
            $end_date = Helpers::Get('end-date');
            $data['end_date'] = $end_date;
        }
        if (Helpers::Get('category') != ""){
            if ($where_cond != ""){
                $where_cond .= " AND event_category.category_id = :category";
            }
            else{
                $where_cond .= "event_category.category_id = :category";
            }
            $data['category'] = Helpers::Get('category');
        }
        try {
            $events = $this->events->getEvents($where_cond, $data);
            $this->json->success = true;
            $this->json->error = "";
            $this->json->data = $events;
        }
        catch (\Exception $e){
            $this->json->success = false;
            $this->json->error = $e->getMessage();
        }
        echo json_encode($this->json);
    }
    /*
    Get one user event
    */
    public function getEvent(){
        try {
            $event = $this->events->getEvent(Helpers::Get('id'));
            $this->json->success = true;
            $this->json->data = $event;
        }
        catch (\Exception $e){
            $this->json->success = false;
            $this->json->error = $e->getMessage();
        }
        echo json_encode($this->json);
    }
    /*
   Get event categories
   */
    public function getEventCategories(){
        try {
            $categories = $this->events->getEventCategories();
            $this->json->success = true;
            $this->json->data = $categories;
        }
        catch (\Exception $e){
            $this->json->success = false;
            $this->json->error = $e->getMessage();
        }
        echo json_encode($this->json);
    }
    /*
    Get event schools
    */
    public function getEventSchools(){
        try{
            $schools = $this->events->getEventSchools();
            $this->json->success = true;
            $this->json->data = $schools;
        }
        catch (\Exception $e){
            $this->json->success = false;
            $this->json->error = $e->getMessage();
        }
        echo json_encode($this->json);
    }
}