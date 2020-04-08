import React, { Component, lazy, Suspense } from "react";
import Row from 'react-bootstrap/Row';
import Container from 'react-bootstrap/Container';
import Col from 'react-bootstrap/Col';
import Button from 'react-bootstrap/Button';
import {
    Link
} from "react-router-dom";
import moment from 'moment';
import SearchHelper from "../helpers/SearchHelper";
export default class Events extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isLoaded: false,
            error: "",
            events: [],
            categories: [],
            s_start_date: "",
            s_end_date: "",
            s_category: "",
        }
    }
    /*
    Load the page
     */
    load(){
        var s = new SearchHelper();
        s.getEvents(this);
        s.getCategories(this);
    }
    componentDidMount() {
        window.addEventListener("load", this.load());
    }

    render() {
        let s = new SearchHelper();
        return (
            <Container fluid id={"events"}>
                <Row>
                    <Col id={"sentral-sidebar"} sm={12} md={2} lg={2}>
                        <h1>Sentral</h1>
                        <nav id={"links"}>
                            <Link to={"/public/events"}>View Public Site</Link>
                        </nav>
                       <hr/>
                       <div id={"sidebar-searchbar"}>
                           <label>Category</label>
                           <select onChange={(e) => this.setState({s_category: e.target.value})} className={"form-control"}>
                               <option value={""}>All</option>
                               {this.state.categories.map(c =>
                               <option value={c.category_id}>{c.category_name}</option>)}
                           </select>
                           <label>Start Date (DD/MM/YYYY)</label>
                           <input onChange={(e) => this.setState({s_start_date: e.target.value})} className={"form-control"} type={"date"}/>
                           <label>End Date (DD/MM/YYYY)</label>
                           <input onChange={(e) => this.setState({s_end_date: e.target.value})} className={"form-control"} type={"date"}/>
                           <Button id={"refine-search"} onClick={() => s.getEvents(this)}>Refine Search</Button>
                       </div>
                    </Col>
                    <Col id={"sentral-content"} sm={12} md={10} lg={10}>
                        <div id={"events-content"}>
                        <Row>
                            <Col><h2>Events</h2></Col>
                            <Col><Button id={"add-event-btn"} onClick={() => this.props.history.push("/events/add")}>Add Event</Button></Col>
                        </Row>
                        <Row>
                            {this.state.isLoaded ? this.state.events.map((e =>
                                    <Col sm={12} md={4} lg={4}>
                                        <Link className={"event-item"} to={"/events/" + e.event_id}>
                                            <h3>{e.event_name}</h3>
                                            <p><b>Category: </b>{e.category_name}</p>
                                            <p><b>Date & Time: </b>{moment(e.event_datetime).format('MMMM Do YYYY, h:mm:ssa')}</p>
                                            <p><b>Description: </b>{e.event_description}</p>
                                            <p><b>Location:</b> {e.location_name}</p>
                                            <p><b>Distance from school:</b> {e.event_distance}</p>
                                            <p><b>Travel time:</b> {e.event_travel_time}</p>
                                            <hr/>
                                            <h4>Organiser Details</h4>
                                            <p>{e.organiser_name}</p>
                                            <p>{e.school_name}</p>
                                        </Link>
                                    </Col>
                            )) : <Col><h2 style={{marginTop: 20}}>Loading events..</h2></Col>}
                        </Row>
                        </div>
                    </Col>
                </Row>
            </Container>
        );
    }
}
