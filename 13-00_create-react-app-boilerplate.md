# 13. Setting Up Your React Dev Environment Easily

Before now we used script tags to load React libraries and Babel for JSX.

```
<script src="https://unpkg.com/react@16/umd/react.development.js"></script>
<script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>
<script src="https://unpkg.com/babel-standalone@6.15.0/babel.min.js"></script>
```

## Using Create React

Using a few commands you can setup a React boilerplate app
[Install Node] (https://nodejs.org/)
[Create React project website] (https://github.com/facebookincubator/create-react-app) 

After you have Node installed type this in your terminal / command prompt:
`npm install -g create-react-app`

When that is done type the following to create your first app called 'helloworld'
`create-react-app helloworld`

Changee directories to your new app
`cd helloworld`

Run this command in the helloworld directory
`npm start`

This is the index.html file in the helloworld/public directory:
```
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#000000">

    <link rel="manifest" href="%PUBLIC_URL%/manifest.json">
    <link rel="shortcut icon" href="%PUBLIC_URL%/favicon.ico">

    <title>React App</title>
  </head>
  <body>
    <noscript>
      You need to enable JavaScript to run this app.
    </noscript>

    <div id="root"></div>

  </body>
</html>
```

helloworld/src/index.js 
```
import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import App from './App';
import registerServiceWorker from './registerServiceWorker';

ReactDOM.render(<App />, document.getElementById('root'));
registerServiceWorker();
```

helloworld/src/App.js
```
import React, { Component } from 'react';
import logo from './logo.svg';
import './App.css';

class App extends Component {
  render() {
    return (
      <div className="App">
        <header className="App-header">
          <img src={logo} className="App-logo" alt="logo" />
          <h1 className="App-title">Welcome to React</h1>
        </header>
        <p className="App-intro">
          To get started, edit <code>src/App.js</code> and save to reload.
        </p>
      </div>
    );
  }
}

export default App;
```

## Making our own React app

Remove all the files in src and add the following files:

### helloworld/src/index.js
```
import React from "react";
import ReactDOM from "react-dom";
import HelloWorld from "./HelloWorld";

ReactDOM.render(
    <HelloWorld/>,
    document.getElementById("root")
);
```

### helloworld/src/HelloWorld.js
```
import React, { Component } from "react";

class HelloWorld extends Component {
  render() {
    return (
      <div className="helloContainer">
        <h1>Hello, world!</h1>
      </div>
    );
  }
}

export default HelloWorld;
```

### helloworld/src/index.css
```
body {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  margin: 0;
}
```
### helloworld/src/index.js
Update the index.js file and import the css file we just made.
```
import React from "react";
import ReactDOM from "react-dom";
import HelloWorld from "./HelloWorld";
import "./index.css";

ReactDOM.render(
    <HelloWorld/>,
    document.getElementById("root")
);
```

### helloworld/src/HelloWorld.css
```
h1 {
    font-family: sans-serif;
    font-size: 56px;
    padding: 5px;
    padding-left: 15px;
    padding-right: 15px;
    margin: 0;
    background: linear-gradient(to bottom,
                                white 0%,
                                white 62%,
                                gold 62%,
                                gold 100%);
}
```

### helloworld/src/HelloWorld.js
Update the HelloWorld.js file
```
import React, { Component } from "react";
import "./HelloWorld.css";

class HelloWorld extends Component {
  render() {
    return (
      <div className="helloContainer">
        <h1>Hello, world!</h1>
      </div>
    );
  }
}

export default HelloWorld;
```

## Make a production build of your app

`npm run build`

After this completes you should have a build directory with your app.
