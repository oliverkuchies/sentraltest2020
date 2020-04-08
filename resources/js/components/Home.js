import React, { Component, lazy, Suspense } from "react";
import Row from 'react-bootstrap/Row';
import Container from 'react-bootstrap/Container';
import Col from 'react-bootstrap/Col';
import Button from 'react-bootstrap/Button';
export default class Home extends Component {
    constructor(props) {
        super(props);
    }
    attemptLogin(){
        //Navigate user to dashboard once their login is successfully checked.
        this.props.history.push('/events');
    }
    register(){
        //Navigate user to registration page.
        this.props.history.push('/register');
    }
    render() {
        return (
            <Container id={"sentral-login"} fluid>
                <Row>
                    <Col sm={4} md={4} lg={4} id={"sentral-sidebar"}>                      <
                        div id={"sentral-sidebar-content"}>
                        <h1>Sentral Event Management</h1>
                        </div>
                    </Col>
                    <Col sm={8} md={8} lg={8} id={"sentral-content"}>
                        <form id={"sentral-login-form"}>
                            <p>Welcome to Sentral</p>
                            <input className={"form-control"} placeholder="Email Address" type="email" id={"email"}/>
                            <input className={"form-control"} placeholder={"Password"} type={"password"} id={"password"}/>
                            <Row>
                                <Col>
                                    <Button onClick={() => this.attemptLogin()}>Login</Button>
                                </Col>
                                <Col>
                                    <Button disabled={true} onClick={() => this.register()}>Register</Button>
                                </Col>
                            </Row>
                        </form>
                    </Col>
                </Row>
            </Container>
        );
    }
}
