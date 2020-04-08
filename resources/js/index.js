import 'react-app-polyfill/ie9';
import 'react-app-polyfill/ie11';
import 'react-app-polyfill/stable';
import React, { Component, lazy, Suspense } from "react";
import ReactDOM from "react-dom";
import {
    HashRouter as Router,
    Switch,
    Route,
    Link,
    withRouter
} from "react-router-dom";

// import Loading from './components/Loading';
import Home from './components/Home';
import Events from './components/Events';
import AddEvent from './components/AddEvent';
import Event from './components/Event';
import PublicEvent from "./components/PublicEvent";
import PublicEvents from "./components/PublicEvents";
class Index extends Component {
  render(){
    return(
        <Router>
          {/*<Suspense fallback={<Loading />}>*/}
            <Switch>
              <Route exact path="/" component={withRouter(Home)}/>
              <Route exact path="/events" component={withRouter(Events)}/>
              <Route exact path="/events/add" component={withRouter(AddEvent)}/>
              <Route exact path="/events/:id" component={withRouter(Event)}/>
              <Route exact path="/public/events" component={withRouter(PublicEvents)}/>
              <Route exact path="/public/events/:id" component={withRouter(PublicEvent)}/>

            </Switch>
          {/*</Suspense>*/}
        </Router>
    );
  }
}
ReactDOM.render(<Index/>, document.getElementById('sentral'));



export default Index;
