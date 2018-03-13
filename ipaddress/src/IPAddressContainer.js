import React, { Component } from "react";
import IPAddress from './IPAddress';

var xhr;

class IPAddressContainer extends Component {
  constructor(props, context) {
    super(props, context);

// add new fields to the state

    this.state = {
      ip_address: "...",
      hostname: "...",
      location: "...",
    };

    this.processRequest = this.processRequest.bind(this);
  }

  componentDidMount() {
    xhr = new XMLHttpRequest();
    xhr.open("GET", "https://ipinfo.io/json", true);
    xhr.send();

    xhr.addEventListener("readystatechange", this.processRequest, false);
  }

  processRequest() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);

// update state with new fields

      this.setState({
        ip_address: response.ip,
        hostname: response.hostname,
        location: response.loc
      });
    }
  }

  render() {

  // pass fields to the IPAddress component
  
    return (
      <IPAddress ip={this.state.ip_address} hostname={this.state.hostname} location={this.state.location} />
    );
  }
};

export default IPAddressContainer;