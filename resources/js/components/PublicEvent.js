import React, { Component, lazy, Suspense } from "react";
import Row from 'react-bootstrap/Row';
import Container from 'react-bootstrap/Container';
import Col from 'react-bootstrap/Col';
import Button from 'react-bootstrap/Button';
import {
    Link
} from "react-router-dom";
import moment from 'moment';
export default class PublicEvent extends Component {
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
            <Container fluid id={"event"} className={"public"}>
                <Row>
                    <Col id={"sentral-sidebar"} sm={12} md={2} lg={2}>
                        <h1>Cherrybrook Technology High School</h1>
                        <img src={"/assets/img/schools/cths/logo.png"} id={"school-logo"}/>
                    </Col>
                    <Col sm={10} md={10} lg={10}  id={"sentral-content"}>
                        <div id={"event-content"}>
                        <Button href={"/#/public/events"} id={"events-btn"}>Back to events</Button>
                        {this.state.isLoaded ?
                            <div>
                                <h3>{this.state.event.event_name}</h3>
                                <p><b>Category: </b>{this.state.event.category_name}</p>
                                <p><b>Date & Time: </b>{moment(this.state.event.event_datetime).format('MMMM Do YYYY, h:mm:ssa')}</p>
                                <p><b>Description: </b>{this.state.event.event_description}</p>
                                <p><b>Location:</b> {this.state.event.location_name}</p>
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
