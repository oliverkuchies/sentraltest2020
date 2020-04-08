<?php


namespace app\Models;

use PDOException;
class EventManager extends Model
{
    /*
     * @return JSON string with all events
     */
    public function getEvents($where_cond = null, $data = null, $limit = null, $offset = null){
        return json_encode($this->selectRows("events", "*",
            $where_cond, $data, ['event_category', 'event_locations', 'event_organiser_info', 'event_schools'],
            [
                'event_category.category_id = events.event_category_id',
                'event_locations.location_id = events.event_location_id',
                'event_organiser_info.organiser_id = events.event_creator_id',
                'event_schools.school_id = events.event_school_id'
            ], $limit, $offset, true, "events.event_id", "event_datetime DESC"));
    }

    /*Get individual event*/
    public function getEvent($event_id){
        $data = new \stdClass();
        $data->event = json_encode($this->selectRows("events", "*",
            "events.event_id = :event_id",
            ['event_id' => $event_id],
            ['event_category', 'event_locations', 'event_organisers', 'event_organiser_info', 'event_schools'],
            [
                'event_category.category_id = events.event_category_id',
                'event_locations.location_id = events.event_location_id',
                'event_organisers.event_id = events.event_id',
                'event_organiser_info.organiser_id = event_organisers.organiser_id',
                'event_schools.school_id = events.event_school_id'
            ]
        ));
        $data->participants = $this->getEventParticipants($event_id);
        $data->organisers = $this->getEventOrganisers($event_id);
        return json_encode($data);
    }

    /*
     * @return JSON string with all event categoriess
     */
    public function getEventCategories(){
        return json_encode($this->selectRows("event_category", "*"));
    }

    /*
     * @return JSON string with a given school's details
     */
    public function getSchoolDetails($school_id){
        return json_encode(
            $this->selectRows(
                "event_schools",
                "*",
                "school_id=:school_id",
                ["school_id" => $school_id]
            )
        );
    }
    /*
     * @return JSON string with all event schools
     */
    public function getEventSchools(){
        return json_encode($this->selectRows("event_schools", "*"));
    }

    /*
    * @return Event participants for a particular event
    */
    public function getEventParticipants($event_id){
        return json_encode($this->selectRows("event_participants",
            "*",
            "event_id=:event_id",
            ["event_id" => $event_id],
            "event_participant_info",
            "event_participants.participant_id = event_participant_info.participant_id"
        ));
    }
    /*
    * @return Event organisers for a particular event
    */
    public function getEventOrganisers($event_id){
        return json_encode($this->selectRows("event_organisers",
            "*",
            "event_id=:event_id",
            ["event_id" => $event_id],
            ["event_organiser_info", 'event_schools'],
            ["event_organisers.organiser_id = event_organiser_info.organiser_id",
            "event_organiser_info.organiser_school_id = event_schools.school_id"]
        ));
    }
    /*
    * @return School for a particular event
    */
    public function getEventHostingSchool($event_id){
        return json_encode($this->selectRows("events",
            "school_id, school_name, school_coords",
            "event_id=:event_id",
            ["event_id" => $event_id],
            "event_schools",
            "event_schools.school_id = events.event_school_id"
        ));
    }
    /*
    * @return Location for a particular event
    */
    public function getEventLocation($event_id){
        return json_encode($this->selectRows("events",
            "location_id, location_name, location_coords",
            "event_id=:event_id",
            ["event_id" => $event_id],
            "event_locations",
            "event_locations.location_id = events.event_location_id"
        ));
    }

    /*
     * @return status of insert function
     */
    public function addNewEvent($data){
        /*
         * Data will contain data to insert into the database
         */
        try{
        $dbh = $this->add("events", "event_name, event_description, event_category_id, event_datetime, event_location_id, event_creator_id, event_school_id, event_distance, event_travel_time", $data);
            return $dbh;
        }
        catch (PDOException $e){
            die($e->getMessage());
            return false;
        }
    }
    /*
     * @return status of insert function
     * Add new location for event
     */
    public function addNewLocation($data){
        /*
         * Data will contain data to insert into the database
         */
        try{
            $location_id = $this->add("event_locations", "location_name, location_coords", $data);
            return $location_id;
        }
        catch (PDOException $e){
            die($e->getMessage());
            return false;
        }
    }
    /*
     * Add organisers to an event
     */
    public function addOrganiser($organiser_data, $event_id){
        try{
            //TODO - Check if organiser exists. If not, skip add
            $organiser_id = $this->add("event_organiser_info", "organiser_name, organiser_school_id", $organiser_data);
            $event_organiser_data = [
                'event_id' => $event_id,
                'organiser_id' => $organiser_id
            ];
            $this->add("event_organisers", "event_id, organiser_id", $event_organiser_data);
            return true;
        }
        catch (PDOException $e){
            die($e->getMessage());
            return false;
        }
    }
    /*
     * Add participant to an event
     */
    public function addParticipant($participant_data, $event_id){
        try{
            //TODO - Check if participant exists. If not, skip add
            $participant_id = $this->add("event_participant_info", "participant_fname, participant_lname, participant_type, participant_email", $participant_data);
            $event_participant_data = [
                'event_id' => $event_id,
                'participant_id' => $participant_id
            ];
            $this->add("event_participants", "event_id, participant_id", $event_participant_data);
            return true;
        }
        catch (PDOException $e){
            die($e->getMessage());
            return false;
        }
    }
}