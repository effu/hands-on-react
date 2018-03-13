# 14. Working with External Data in React

## Process to get external data

1. App requests data from API (local or remote)  
2. API takes request and sends data
3. App consumes data
4. App shows the data to user

This is done when the user or your app asks for it.

[Simple React app](https://www.kirupa.com/react/examples/ipaddress.htm)

This app will show your IP address. It is simple but follows the process


To get information about the user, here is our HTTP request:

```
GET /user
Accept: application/json
```

For that request, here is what the server might return:

```
200 OK
Content-Type: application/json

{
  "name": "Kirupa",
  "url": "https://www.kirupa.com"
}
```

In JavaScript, the object that is responsible for allowing you to send and receive HTTP requests is the weirdly named XMLHttpRequest. This object allows you to do several things that are important to making web requests. It allows you to:

1. Send a request to a server  
2. Check on the status of a request  
3. Retrieve and parse the response from the request  
4. Listen for the readystatechange event that helps you react to the status of your request  

There are a few more things that XMLHttpRequest does, but those things aren’t important for us to deal with right now.

## Create a new react app called 'ipaddress'

```
create-react-app ipaddress
```

Remove everything in the *public/* and *src/* directories.

Create a #container for our app.

### public/index.html
```
<!doctype html>
<html>
  <head>
    <title>IP Address</title>
  </head>
  <body>
    <div id="container">

    </div>
  </body>
</html>
```

Add the IPAddressContainer component to our #container.

### src/index.js
```
import React from "react";
import ReactDOM from "react-dom";
import "./index.css";
import IPAddressContainer from "./IPAddressContainer";

var destination = document.querySelector("#container");

ReactDOM.render(
    <div>
        <IPAddressContainer/>
    </div>,
    destination
);
```

Add some styling

### src/index.css
```
body {
  background-color: #FFCC00;
}
```

## Getting the IP Address

## Create our IpAddressContainer dummy component

### src/IPAddressContainer.js
```
import React, { Component } from "react";

class IPAddressContainer extends Component {
  render() {
    return (
      <p>Nothing yet!</p>
    );
  }
}

export default IPAddressContainer;
```

Add the xhr request to componentDidMount()

```
var xhr;

class IPAddressContainer extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      ip_address: "..."
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

      this.setState({
        ip_address: response.ip
      });
    }
  }

  render() {
    return (
      <div>Nothing yet!</div>
    );
  }
};
```

Call the processRequest() function when a response is received.

```
componentDidMount() {
    xhr = new XMLHttpRequest();
    xhr.open('GET', "https://ipinfo.io/json", true);
    xhr.send();

    xhr.addEventListener("readystatechange", this.processRequest, false);
  }
```

Set the ip_address value in our state

```
processRequest() {
     if (xhr.readyState === 4 && xhr.status === 200) {
   var response = JSON.parse(xhr.responseText);

       this.setState({
         ip_address: response.ip
       });
     }
```

Update the render method to show the ipaddress from state

```
var xhr;

class IPAddressContainer extends Component {
  constructor(props, context) {
    super(props, context);

    this.state = {
      ip_address: "..."
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

      this.setState({
        ip_address: response.ip
      });
    }
  }

  render() {
    return (
      <div>{this.state.ip_address}</div>
    );
  }
}
```

Your app should show your public IP address that it receives from https://ipinfo.io/json

# Eye-candy for our IP Address App

Make a new component called IPAddress

### src/IPAddress.js
```
import React, { Component } from "react";

class IPAddress extends Component {
  render() {
    return (
      <div>
        <h1>{this.props.ip}</h1>
        <p>( This is your IP address...probably :P )</p>
      </div>
    );
  }
}

export default IPAddress;
```

Style the IPAddress component 

### src/IPAddress.css
```
h1 {
  font-family: sans-serif;
  text-align: center;
  padding-top: 140px;
  font-size: 60px;
  margin: -15px;
}
p {
  font-family: sans-serif;
  color: #907400;
  text-align: center;
}
```

Import the new stylesheet into the IPAddress component

### src/IPAddress.js
```
import React, { Component } from "react";
import "./IPAddress.css";

class IPAddress extends Component {
  render() {
    return (
      <div>
        <h1>{this.props.ip}</h1>
        <p>( This is your IP address...probably :P )</p>
      </div>
    );
  }
}

export default IPAddress;
```


Update the IPAddressContainer component to import the IPAddress component.

### src/IPAddressContainer.js
```
import React, { Component } from "react";
import IPAddress from "./IPAddress";
```

Change the render() method to use the IPAddress component

### src/IPAddressContainer.js
```
class IPAddressContainer extends Component {
// ...
  render() {
    return (
           <IPAddress ip={this.state.ip_address}/>
    );
  }
}
// ...
```

Eye-candy completed!

# Extra Mile add more props

```
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
```

Add the new props into the IPAddress component.

### src/IPAddress.js
```
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
```

## Presentational vs. Container Components

There are two main types of components

### 1. Presentational Components

Components that deal with how things look. These are better known as Presentational Components.

### 2. Container Components

Components that perform processing. Examples of this processing look like routing, increasing a counter, fetching data via a HTTP request, etc. You will see these components referred to as Container Components.

[Smart and Dumb Components by React’s Dan Abramov](https://medium.com/@dan_abramov/smart-and-dumb-components-7ca2f9a7c7d0)

