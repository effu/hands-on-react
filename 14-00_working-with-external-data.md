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

The lines we just added don’t do a whole lot. They just print out the words Nothing yet! to the screen. That’s not a bad place to be for now, but let’s go ahead and modify our code to make the HTTP request by making the following changes:

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

Now, we are getting somewhere! When our component becomes active and the componentDidMount lifecycle method gets called, we make our HTTP request and send it off to the ipinfo.io web service:

```
componentDidMount() {
    xhr = new XMLHttpRequest();
    xhr.open('GET', "https://ipinfo.io/json", true);
    xhr.send();

    xhr.addEventListener("readystatechange", this.processRequest, false);
  }
```

When we hear a response back from the ipinfo service, we call the processRequest function to help us deal with the result:

```
processRequest() {
     if (xhr.readyState === 4 && xhr.status === 200) {
   var response = JSON.parse(xhr.responseText);

       this.setState({
         ip_address: response.ip
       });
     }
```

Next, modify the render call to reference the IP address value stored by our state:

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

If you preview your app in your browser, you should see an IP address getting displayed. In case you need a reminder, you can preview your app by navigating into your ipaddress folder via your command line and entering npm start. When your app launches, it will look something like the following:

Image

Our app currently doesn’t look like much, but we’ll fix that up in the next section.

Kicking the Visuals Up a Notch
The hard part is done! We created a component that handles all of the HTTP requesting shenanigans, and we know that it returns the IP address when called. What we are going to do now is format the output a bit so that it doesn’t look as plain as it does now.

To do that, we are not going to add additional HTML elements and styling-related details to our IPAddressContainer component’s render method. Instead, we are going to create a new component whose only purpose will be to deal with all of that.

Add a new file called IPAddress.js in your src folder. Once you’ve done that, edit it by adding the following content into it:

```
import React, { Component } from "react";

class IPAddress extends Component {
  render() {
    return (
      <div>
        Blah!
      </div>
    );
  }
}
export default IPAddress;
```

What we are doing here is defining a new component called IPAddress, and this component will be responsible for displaying the additional text and ensuring our IP address is visually formatted exactly the way we want. Right now, it doesn’t do much, but that is going to change really quickly.

The first thing we want to do is modify this component’s render method to look as follows:

```
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

The highlighted changes should seem self-explanatory. We are putting the results of a prop value called ip inside a h1 tag, and we have some additional text we are displaying using a p tag. Besides making the rendered HTML a bit more semantic, these changes ensure we can style them better as well.

To get these elements styled, add a new CSS file to our src folder called IPAddress.css. Inside this file, add the following style rules:

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

With the styles defined, we need to reference this CSS file in our IPAddress.js file. To do that, add the following highlighted line:

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

All that is left is to use our IPAddress component and pass in the IP address. The first step is to ensure our IPAddressContainer component is aware of the IPAddress component by referencing it. At the top of IPAddressContainer.js, add the following highlighted line:

```
import React, { Component } from "react";
import IPAddress from "./IPAddress";
```

The second (and last!) step is to modify the render method as follows:

```
class IPAddressContainer extends Component {
// ...
  render() {
    return (
           <IPAddress ip={this.state.ip_address}/>
    );
  }
}
```

In our highlighted line, we call our IPAddress component, define a prop called ip, and set its value to the ip_address state variable. This is done to ensure our IP address value travels all the way back to the IPAddress component’s render method where it gets formatted and displayed.

After you’ve made these changes, if you preview the app in your browser now, what you should see will be identical to the example we set out to create at the beginning:

At this point, you are done with the app...and almost done with this tutorial. There is just one more thing about these awesome components that we’ve added you need to know about.

## Presentational vs. Container Components

Given what we’ve seen here so far, it seems like a good time to talk about a design choice that we’ve been indirectly following not just in this tutorial, but in other tutorials as well. In our React apps, we have been primarily dealing with two types of components:

1. Components that deal with how things look. These are better known as Presentational Components.

2. Components that perform some under-the-covers processing. Examples of this processing look like routing, increasing a counter, fetching data via a HTTP request, etc. You will see these components referred to as Container Components.

Thinking about your components in terms of whether they display something (Presentational) or whether they feed data to other components (Container) helps you organize your React app better. Instead of discussing this further here, for the full low-down on how to deal with these two types of components, you should [check out this article by React’s Dan Abramov](https://medium.com/@dan_abramov/smart-and-dumb-components-7ca2f9a7c7d0)

## CONCLUSION
At this point, you are probably wondering what you just learned that was made special because of React. All we really did was just use a boring old JavaScript API inside a component, hook up some events, and do the same state and prop-related tasks that we’ve looked at several times already. Here is the thing: you’ve already learned almost everything there is to learn about the basics of React. Going forward, nothing should surprise you. The only new things we’ll be looking at is how to repurpose and repackage the basic concepts we already know into newer and cooler situations. After all, isn’t that what programming is all about?
