export default class SearchHelper{
    /*Pull all schools into state*/
    getSchools(app){
        fetch("/api/events/schools")
            .then(res => res.json())
            .then(
                (result) => {
                    if (result.success === true) {
                        app.setState({
                            isLoaded: true,
                            schools: JSON.parse(result.data),
                            error: result.error
                        });
                    }
                    else{
                        app.setState({
                            isLoaded: true,
                            schools: [],
                            error: result.error
                        });
                    }
                },
                (error) => {
                    app.setState({
                        isLoaded: true,
                        error
                    });
                }
            )
    }
    /*Pull all categories into the form*/
    getCategories(app){
        fetch("/api/events/categories")
            .then(res => res.json())
            .then(
                (result) => {
                    if (result.success === true) {
                        app.setState({
                            isLoaded: true,
                            categories: JSON.parse(result.data),
                            error: result.error
                        });
                    }
                    else{
                        app.setState({
                            isLoaded: true,
                            categories: [],
                            error: result.error
                        });
                    }
                },
                (error) => {
                    app.setState({
                        isLoaded: true,
                        error
                    });
                }
            )
    }
    /*
   Pull events from API server and load them into a state for the page to load
   */
    getEvents(app){
        let events_url = "/api/events";
        if (app.state.s_start_date !== ""){
            events_url += "&start-date=" + app.state.s_start_date;
        }
        if (app.state.s_end_date !== ""){
            events_url = this.addParam(events_url);
            events_url += "end-date=" + app.state.s_end_date;
        }
        if (app.state.s_category !== ""){
            events_url = this.addParam(events_url);
            events_url += "category=" + app.state.s_category;
        }
        fetch(events_url)
            .then(res => res.json())
            .then(
                (result) => {
                    if (result.success === true) {
                        app.setState({
                            isLoaded: true,
                            events: JSON.parse(result.data),
                            error: result.error
                        });
                    }
                    else{
                        app.setState({
                            isLoaded: true,
                            events: [],
                            error: result.error
                        });
                    }
                },
                (error) => {
                    app.setState({
                        isLoaded: true,
                        error
                    });
                }
            )
    }
    /*Add & to url params */
    addParam(events_url){
        events_url += "&";
        return events_url;
    }
    /*Get admin's current school*/
    getAdminSchoolDetails(app){
        return fetch("/api/events/schools/"+app.state.school_id)
            .then(res => res.json())
            .then(
                (result) => {
                    if (result.success === true) {
                        app.setState({
                            isLoaded: true,
                            school_details: JSON.parse(result.data)[0],
                            error: result.error
                        });
                    }
                    else{
                        app.setState({
                            isLoaded: true,
                            school_details: [],
                            error: result.error
                        });
                    }
                },
                (error) => {
                    app.setState({
                        isLoaded: true,
                        error
                    });
                }
            )
    }
    /*Get distance details
    *
    * compare item 1 and item 2 with Google
    * */
    getDistance(app,origin_lat, origin_long, destination_lat, destination_long){
        return new Promise((resolve,reject) =>
            {
                var origin = new google.maps.LatLng(origin_lat, origin_long);
                var destination = new google.maps.LatLng(destination_lat, destination_long);
                var service = new google.maps.DistanceMatrixService();
                var distance = service.getDistanceMatrix(
                    {
                        origins: [origin],
                        destinations: [destination],
                        travelMode: 'DRIVING',
                        unitSystem: google.maps.UnitSystem.METRIC
                    }, (response, status) => {
                         resolve(response);
                    });
            }
        )
    }
}
