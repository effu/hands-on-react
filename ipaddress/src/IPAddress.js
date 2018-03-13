import React, { Component } from "react";
import "./IPAddress.css";

class IPAddress extends Component {
  
  render() {
    return (
      <div>
        <h1>{this.props.ip}</h1>
        <p>( This is your IP address )</p>
        <p>Host name: {this.props.hostname}</p>
        <p>Geo location: {this.props.location}</p>
      </div>
    );
  }
}

export default IPAddress;