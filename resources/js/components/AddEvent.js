import React, { Component, lazy, Suspense } from "react";
import Row from 'react-bootstrap/Row';
import Container from 'react-bootstrap/Container';
import Col from 'react-bootstrap/Col';
import Button from 'react-bootstrap/Button';
import {
    Link
} from "react-router-dom";
import SearchHelper from "../helpers/SearchHelper";
export default class AddEvent extends Component {
    constructor(props) {
        super(props);
        this.state = {
            school_id: 1,
            organiser_num: 1,
            participant_num: 1,
            schools: [],
            categories: [],
            location_long: 0,
            location_lat: 0,
            event_name: "",
            event_description: "",
            event_location: "",
            event_date: "",
            event_time: "",
            event_distance: "",
            event_travel_time: "",
            category_id: 1,
        };
        this.participants = [];
        this.organisers = [];
    }
    /*
 Load the page
  */
    load(){
        var s =  new SearchHelper();
        s.getSchools(this);
        s.getCategories(this);
        this.loadGoogleMapAutoComplete();
    }
    componentDidMount() {
        window.addEventListener("load", this.load());
    }
    calculateDistance(){
        var app = this;
        let s = new SearchHelper();
        let loc_lat = this.state.location_lat;
        let loc_long = this.state.location_long;
        let loc_coords = loc_lat + "," + loc_long;
        let school_coords = "";
        //Get admin's current school details
        s.getAdminSchoolDetails(this).then(() => {
            school_coords = this.state.school_details.school_coords.split(",");
            let school_lat = school_coords[0];
            let school_long = school_coords[1];
            //Get distance between two positions with Google Maps API
            s.getDistance(app, loc_lat, loc_long, school_lat, school_long).then((response => {
                //Save distance so we can send it to DB.
                var distance_data = response.rows[0].elements[0];
                console.log(distance_data);
                app.setState({
                     event_distance: distance_data.distance.text,
                     event_travel_time: distance_data.duration.text
                 })
            }));
        });
    }

    /*Add participant to array of objects for later processing*/
    addParticipant(item, value, index){
        if (typeof(this.participants[index]) !== "object"){
            this.participants[index] = {
                fname: "",
                lname: "",
                email: "",
                type: "Staff"
            }
        }
        this.participants[index][item] = value;
    }
    /*
    Add organiser to array so we can send it through
     */
    addOrganisers(item, value, index){
        if (typeof(this.organisers[index]) !== "object"){
            this.organisers[index] = {
                school: 1,
                name: "",
            }
        }
        this.organisers[index][item] = value;
    }
    /*Load autocomplete into the input box*/
    loadGoogleMapAutoComplete(){
        var app = this;
        var input = document.getElementById('event_location');
        var autocomplete = new google.maps.places.Autocomplete(input);
        //Store X & Y for later use (its important!)
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            app.setState({
                location_lat: place.geometry.location.lat(),
                location_long: place.geometry.location.lng(),
                event_location: place.formatted_address
            });
            app.calculateDistance();
        });
    }
    /*Submit all form data in form format so its readable by PHP server*/
    submitForm(){
        var form_data = new FormData();
        var participants_loc = {};
        for(let i=1; i <= this.state.participant_num; i++){
            form_data.append('participant_fname_'+i, this.participants[i]['fname']);
            form_data.append('participant_lname_'+i,this.participants[i]['lname']);
            form_data.append('participant_type_'+i,this.participants[i]['type']);
            form_data.append('participant_email_'+i,this.participants[i]['email']);
        }
        var organisers_loc = {};
        for(let i=1; i <= this.state.organiser_num; i++){
            form_data.append('organiser_name_'+i, this.organisers[i]['name']);
            form_data.append('organiser_school_'+i, this.organisers[i]['school']);
        }
        form_data.append('event_name', this.state.event_name);
        form_data.append('event_description', this.state.event_description);
        form_data.append('event_time', this.state.event_time);
        form_data.append('event_location', this.state.event_location);
        form_data.append('event_coords', this.state.location_lat + "," + this.state.location_long);
        form_data.append('event_date', this.state.event_date);
        form_data.append('event_time', this.state.event_time);
        form_data.append('organiser_num', this.state.organiser_num);
        form_data.append('participant_num', this.state.participant_num);
        form_data.append('category_id', this.state.category_id);
        form_data.append('current_user', 1);
        form_data.append('school_id', 1);
        form_data.append('event_distance', this.state.event_distance);
        form_data.append('event_travel_time', this.state.event_travel_time);

        fetch("/api/events/add",
            {
                method: "POST",
                body: form_data
            })
            .then(res => res.json())
            .then(
                (result) => {
                    if (result.success === true) {
                        alert("Successfully added event");
                        this.props.history.push("/events");
                    }
                    else{
                        this.setState({
                            error: result.error
                        });
                        alert("An error occurred when entering data, please restart your form..");
                    }
                },
                (error) => {
                    this.setState({
                        isLoaded: true,
                        error
                    });
                }
            )
    }
    loadOrganisers(){
        var data = [];
        for(let i = 1; i <= this.state.organiser_num; i++) {
            data.push(
            <Row className={"item-row"}>
                <Col>
                    <label>School</label>
                    <select onChange={(e => this.addOrganisers("school", e.target.value, i))} className={"form-control"} id={"organiser_school_" + i}>
                        {this.state.schools.map(school =>
                            <option value={school.school_id}>{school.school_name}</option>
                        )}
                    </select>
                </Col>
                <Col>
                    <label>Organiser Name</label>
                    <input onChange={(e => this.addOrganisers("name", e.target.value, i))} className={"form-control"} type={"text"} id={"organiser_name_" + i}/>
                </Col>
            </Row>);
        }
        return data;
    }
    /*
    Load all participants into the form
     */
    loadParticipants(){
        var data = [];
        for(let i = 1; i <= this.state.participant_num; i++){
            data.push(
            <Row className={"item-row"}>
                <Col>
                    <label>First Name</label>
                    <input onChange={(e => this.addParticipant("fname", e.target.value, i))} className={"form-control"} type={"text"} id={"participant_fname_"+i}/>
                </Col>
                <Col>
                    <label>Last Name</label>
                    <input onChange={(e => this.addParticipant("lname", e.target.value, i))} className={"form-control"} type={"text"} id={"participant_lname_"+i}/>
                </Col>
                <Col>
                    <label>Email</label>
                    <input onChange={(e => this.addParticipant("email", e.target.value, i))} className={"form-control"} type={"text"} id={"participant_email_"+i}/>
                </Col>
                <Col>
                    <label>Type</label>
                    <select onChange={(e => this.addParticipant("type", e.target.value, i))} id={"participant_type_"+i} className={"form-control"}>
                        <option value={"Staff"}>Staff</option>
                        <option value={"Parent"}>Parent</option>
                        <option value={"Volunteer"}>Volunteer</option>
                        <option value={"Other"}>Other</option>
                    </select>
                </Col>
            </Row>);
        }
        return data;
    }
    /*Add to the current organiser amount*/
    addOrganiserCount(){
        this.setState({organiser_num: this.state.organiser_num+1})
    }
    /*Add to the current participant amount*/
    addParticipantCount(){
        this.setState({participant_num: this.state.participant_num+1})
    }

    render() {
        return (
            <Container fluid id={"add-event"}>
                <Row>
                    <Col sm={2} md={2} lg={2} id={"sentral-sidebar"}>
                        <div id={"sentral-sidebar-content"}>
                            <h1>Sentral</h1>
                            <nav id={"links"}>
                                <Link to={"/public"}>View Public Site</Link>
                            </nav>
                        </div>
                    </Col>
                    <Col sm={10} md={10} lg={10} id={"sentral-content"}>
                        <div id={"event-content"}>
                            <form>
                            <h2>Add New Event</h2>
                            <Button id={"back-to-events"} href={"/#/events"}>Back to events</Button>
                            <hr/>
                            <label>Event Name</label>
                            <input required={true} onChange={(e => this.setState({event_name: e.target.value}))} className="form-control" type="text" id={"event_name"}/>
                            <label>Event Description</label>
                            <textarea required={true} onChange={(e => this.setState({event_description: e.target.value}))} className={"form-control"} id={"event_description"}></textarea>
                            <label>Category</label>
                            <select onChange={(e => this.setState({category_id: e.target.value}))} className="form-control" id={"event_category"}>
                                {this.state.categories.map(category =>
                                    <option value={category.category_id}>{category.category_name}</option>
                                )}
                            </select>
                            <label>Location</label>
                            <input required={true} className={"form-control"} type="text" id={"event_location"}/>
                            <Row>
                                <Col>
                                    <label>Date</label>
                                    <input required={true}  onChange={(e => this.setState({event_date: e.target.value}))} className={"form-control"} type="date" id={"event_date"}/>
                                </Col>
                                <Col>
                                    <label>Time</label>
                                    <input required={true}  onChange={(e => this.setState({event_time: e.target.value}))} className={"form-control"} type="time" id={"event_time"}/>
                                </Col>
                            </Row>
                            <hr/>
                            <h4>Organisers</h4><Button onClick={() => this.addOrganiserCount()}>Add Organiser +</Button>
                            {this.loadOrganisers()}
                            <hr/>
                            <h4>Participants</h4><Button onClick={() => this.addParticipantCount()}>Add Participant +</Button>
                                {this.loadParticipants()}
                                <Button id={"add-event-btn"} onClick={() => this.submitForm()}>Add Event</Button>
                                <input type={"hidden"} id={"school_id"} value={this.state.school_id}/>
                            </form>
                        </div>
                    </Col>
                </Row>
            </Container>
        );
    }
}
