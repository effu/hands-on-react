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

Why not use 3rd party libraries?
A bunch of 3rd party libraries wrap and simplify how you can work with the XMLHttpRequest object. Feel free to use them if you want, but using the XMLHttpRequest object directly isn’t very complicated either. It’s only a few lines of code, and (compared to everything we’ve been through in learning React) it’ll be some of the easiest lines of code you’ll encounter :P

IT’S REACT TIME!
Now that we have a good-enough understanding of how HTTP requests and the XMLHttpRequest object work, it is time to shift our focus to the React side of the house. I should warn you, though. There is very little React brings to the table when it comes to working with external data. The reason has to do with React being primarily focused on the presentation layer (aka the V in MVC). What we will be doing is writing regular, boring JavaScript inside a React component whose primary purpose will be to deal with the web requests we will be making. We will talk more about that design choice in a little bit, but let’s get the example up and running first.

Getting Started
The first step is to create a new React app. From your command line, navigate to the folder you want to create your new project, and enter the following:

create-react-app ipaddress
Press Enter/Return to run that command. A few moments later, a brand new React project will get created. Since we want to start from a blank slate, we are going to delete a lot of things. First, delete everything under your public folder. Next, delete everything inside your src folder. Don’t worry. We will fill them back with the content we care about in a few moments...starting with our HTML file.

Inside our public folder, create a new file called index.html. Add the following content into it:
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

All we have going here is a div element named container. Next, go to your src folder and create a new file called index.js. Inside this file, add the following:

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

This is the script entry point for our app, and it contains the boilerplate references to React, ReactDOM, a non-existent CSS file, and a non-existent IPAddressContainer component. We also have the ReactDOM.render call that is responsible for writing our content to the container div element we defined in our HTML a few moments ago.

There is just one more thing to do before we get to the really interesting stuff. Inside the src folder, create our index.css file and add the following style rule into it:

```
body {
  background-color: #FFCC00;
}
```

Save all of our changes if you haven’t done so already. We sorta have the beginnings of our app started. In the next section, we are going to make our app really useful...or at least get really REALLY close!

GETTING THE IP ADDRESS
Next on our plate is to create a component whose job it is to fetch the IP address from a web service, store it as state, and then share that state as a prop to any component that requires it. Let’s create a component to help with all of this. Inside your src folder, add a file called IPAddressContainer.js, and add the following lines inside it:

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
