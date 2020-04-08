import React, { Component, lazy, Suspense } from "react";
import Row from 'react-bootstrap/Row';
import Container from 'react-bootstrap/Container';
import Col from 'react-bootstrap/Col';
import Button from 'react-bootstrap/Button';
import {
    Link
} from "react-router-dom";
import moment from 'moment';
export default class Event extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isLoaded: false,
            error: "",
            event: {}
        }
    }
    /*
    Load the page
     */
    load(){
        this.getEvent(this.props.id);
    }
    componentDidMount() {
        window.addEventListener("load", this.load());
    }

    /*
    Pull event from API server and load them into a state for the page to load
    */
    getEvent(){
        fetch("/api/events/"+this.props.match.params.id)
            .then(res => res.json())
            .then(
                (result) => {
                    var data = JSON.parse(result.data);
                    var event = JSON.parse(data.event)[0];
                    event.participants = JSON.parse(data.participants);
                    event.organisers = JSON.parse(data.organisers);
                    if (result.success === true) {
                        this.setState({
                            isLoaded: true,
                            event: event,
                            error: result.error
                        });
                    }
                    else{
                        this.setState({
                            isLoaded: true,
                            event: [],
                            error: result.error
                        });
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

    render() {
        return (
            <Container fluid id={"event"}>
                <Row>
                    <Col sm={2} md={2} lg={2} id={"sentral-sidebar"}><div id={"sentral-sidebar-content"}>
                        <h1>Sentral</h1>
                        <nav id={"links"}>
                            <Link to={"/public"}>View Public Site</Link>
                        </nav>
                    </div>
                    </Col>
                    <Col sm={10} md={10} lg={10}  id={"sentral-content"}>
                        <div id={"event-content"}>
                        <Button href={"/#/events"} id={"events-btn"}>Back to events</Button>
                        {this.state.isLoaded ?
                            <div>
                                <h3>{this.state.event.event_name}</h3>
                                <p><b>Category: </b>{this.state.event.category_name}</p>
                                <p><b>Date & Time: </b>{moment(this.state.event.event_datetime).format('MMMM Do YYYY, h:mm:ssa')}</p>
                                <p><b>Description: </b>{this.state.event.event_description}</p>
                                <p><b>Location:</b> {this.state.event.location_name}</p>
                                <p><b>Distance from school:</b> {this.state.event.event_distance}</p>
                                <p><b>Travel time:</b> {this.state.event.event_travel_time}</p>
                                <hr/>
                                <h4>Organiser Details</h4>
                                <Row>
                                    {this.state.event.organisers.map(p =>
                                        <Col sm={4} md={4} lg={4}>
                                            <div className={"organiser-item"}>
                                                <p><b>Name:</b> {p.organiser_name}</p>
                                                <p><b>School:</b> {p.school_name}</p>
                                            </div>
                                        </Col>
                                    )}
                                </Row>
                                <hr/>
                                <h4>Participant Details</h4>
                                <Row>
                                {this.state.event.participants.map(p =>
                                    <Col sm={4} md={4} lg={4}>
                                        <div className={"participant-item"}>
                                            <p><b>Name:</b> {p.participant_fname} {p.participant_lname}</p>
                                            <p><b>Type:</b> {p.participant_type}</p>
                                            <p><b>Email:</b> {p.participant_email}</p>
                                        </div>
                                    </Col>
                                )}
                                </Row>
                            </div>
                                : <p>Loading..</p>
                        }
                        </div>
                    </Col>
                </Row>
            </Container>
        );
    }
}
