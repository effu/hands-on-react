# 20. Using Redux with React

Now that you have a better idea of how Redux works, let’s look at the topic we set out to better understand in the first place. Why is Redux so popular in React projects? To help answer this, take a look at the following component hierarchy for some arbitrary app:

What this app does isn’t very important. The only detail we’ll throw in there is that that some of these components are responsible for managing state and transferring some of that state around in the form of props:

In the ideal setup, this data each component needs will flow neatly down from parent to child:

Unfortunately, outside of simple scenarios, what we would like to do isn’t very realistic. Your typical app does a lot of state generating, processing, and transferring! One component may initiate a state change. Another component somewhere else will want to react to it. The props related to this state change may travel both down the tree (yay!) as well as up the tree (nooo!) to reach whatever component is relying on the data being transferred:

We’ve been guilty of this quite a few times ourselves as part of communicating something (variable value, function/event handler reference, etc.) from child to parent and beyond.

Now, there are a few problems that we need to recognize at this point with data traveling willy nilly through our components:

1. Difficult to maintain. React’s stated goal was to avoid spaghetti-like dependencies, but we end up with exactly the thing that we were supposed to be free from when we have data that is flowing around our app.

2. Each time your state changes or a prop is transmitted, all affected components are asked to re-render. To ensure your UI is in-sync with the current state, this behavior is a good thing. As we talked about before, many components are unnecessarily asked to re-render when all they are doing is passing a value from parent to child with no additional input. We looked at ways of minimizing this re-rendering by setting shouldComponentUpdate or relying on PureComponent, but both approaches are a hassle to keep in-sync as your app’s data needs evolve.

3. Our component hierarchy mimics the UI and not our data needs. The way we arrange and nest our components is to help separate our UI into smaller and manageable pieces. This is the correct thing to do. Despite the correctness, the components that initiate a state change and the ones that need to react to it are often not in the same parent/child/descendant arrangement (aka subtree). Similar to what we talked about in #ii, this requires our props to travel great distances - often multiple times per change!

The solution to our problems is Redux! Now, Redux doesn’t solve all of these problems fully, but it gets us really REALLY close. What Redux allows you to do is have all of your application’s state live inside its data store instead of being distributed across a bunch of components:

This approach solves several of our problems. If you want to share data from one part of your app with another, you can do that without having to navigate up and down your component hierarchy:

You can initiate a state change and involve only the impacted components directly. This directness reduces a lot of overhead you would otherwise have to maintain around ensuring your data (and any changes to it) gets to its intended destination without causing unnecessary renders! Pretty cool, right?

Now, let’s go one level higher. From an architectural point of view, the overview of Redux we saw in the Introductionstill holds:

Besides the store, we still have to work with actions, reducers, and all the other related things that make up the Redux party. The only difference is that our app is built using React, and this difference (and how it plays with Redux) is where we will focus our attention on here.

Onwards!

## MANAGING REACT STATE WITH REDUX

The way Redux plugs into your React app is as straightforward as calling a few Redux APIs from your React code. There are just two steps involved:

1. Provide your app a reference to the Redux store

2. Map the action creators, dispatch functions, and state as props to whatever component needs data from our store

To see what is involved in bringing these two steps to life, we’re going to build a simple Counter app. It will look as follows:

Our app will have a plus button and a minus button to increase or decrease a counter value. That’s it. There is nothing else that is going on here, but this is just the right level of functionality and complexity to help us get our feet wet with combining React and Redux together.

How Redux and React Overlap
Given that we just saw what we are going to be building, this is where we would start copying and pasting HTML, CSS, and JavaScript to get our example up and running. We will totally get there in a few moments, but I want to first walk through how this app is structured. Ignoring the data and state management side, we are going to have just two components:

We will have an App component and a Counter component. Now, a counter is not the most complicated of examples to think about. If we had to implement it using plain old state, we would simply create a state object inside Counter and have a variable whose value increased or decreased depending on what button we press.

When we throw Redux into the mix, our component arrangement gets a little bizarre. It will look as follows:

The items in blue are what we had originally. The items in green are new as part of incorporating Redux into our app. Earlier, we mentioned that adding Redux to our app involved two steps. The green additions mimic those steps closely:

1. The first step of providing access to our Redux store is handled by the Provider component

2. The second step of providing any interested components access to our dispatch and actions is handled by the Connect component

Going into a little more detail, the Provider component is the gateway to getting Redux functionality in our React app. It is responsible for storing a reference to our store and ensuring all components in our app have a way of accessing it. The way it is able to do that is by being the top-most component in your component hierarchy. That vaulted position allows it to pass Redux-related wisdom throughout the entire app easily.

The Connect component is a bit more interesting. It isn’t a full-blown component in the traditional sense. It is known as a [Higher Order Component](https://reactjs.org/docs/higher-order-components.html), or HOC as the cool kids say it. What HOCs do is provide a consistent way to extend the functionality of a pre-existing component by simply wrapping it and injecting their own additional functionality into them. Think of this as the React-friendly way to mimic what the extends keyword does when working with ES6 classes. From looking at our diagram, the end result is that our Counter component, thanks to the Connect HOC, has access to any actions and dispatch calls needed to work with the Redux store without you having to write any special code to access it. The Connect HOC takes care of that.

Both the Provider and Connect HOC’s create a symbiotic relationship that gives any old React app the ability to easily work with Redux’s peculiar (yet totally efficient and awesome) way of managing application state. As we start to build our app, you’ll start to see more of how this relationship plays out.

## Getting Started

Now that we have an idea of how our app will be structured and some of the Redux-specific constructs that we’ll be using, let’s shift gears and start to build our app. To get started, first use create-react app create-react appto create an app we will call reduxcounter:
```
create-react-app reduxcounter
```
Once you have created this app, we are going to install the Redux and React Redux dependencies. From inside your Terminal/command line environment, navigate to the reduxcounter folder, and run the following command:
```
cd reduxcounter
npm install redux
```
This will install the Redux library so that our app can use the basic building blocks Redux provides for fiddling with application state. Once the Redux library has fully installed, there is just one more dependency we need to deal with. Run the following command to bring over all the React Redux content:
```
npm install react-redux
```
Once this command has run to completion, we have everything needed to both build our React app and use some Redux magic in it as well. It’s time to start building our app!

## Building the App

The first thing we will do is clear our package of all unnecessary and extraneous files. Go to your src and public folders, and delete all of the contents that you see in both of those locations. 

```
rm src/*
rm public/*
```

Once you have done this, create a new file called index.html in your public folder and add the following HTML into it:


```
<!doctype html>
<html lang="en">
  <head>
    <title>Redux Counter</title>
  </head>
  <body>
    <div id="container">

    </div>
  </body>
</html>
```
The only thing to note is that we have a div element with an id value of container.

Next, let’s create the JavaScript that will be the entry point to our app. In the src folder, create a file called index.js and add the following contents into it:
```
import React, { Component } from "react";
import ReactDOM from "react-dom";
import { createStore } from "redux";
import { Provider } from "react-redux";
import counter from "./reducer";
import App from "./App";
import "./index.css";

var destination = document.querySelector("#container");

// Store
var store = createStore(counter);

ReactDOM.render(
  <Provider store={store}>
    <App />
  </Provider>,
  destination
);
```
Take a moment to look at what we are doing here. We are first initializing our Redux store and using our trustworthy createStore method that takes a reducer at its argument. Our reducer is referenced by the counter variable, and if you look at our import statements, it is defined in a file called reducer.js. We’ll deal with that in a few moments.

Once we have created our store, we provide it as a prop to our Provider component. The Provider component is intended to be used as the outer-most component in our app to help ensure that every component has access to the Redux store and related functionality:
```
ReactDOM.render(
  <Provider store={store}>
    <App />
  </Provider>,
  destination
);
```
Next, let’s create our reducer. We already saw that our reducer is referenced by the counter variable and lives inside a file called reducer.js...which doesn’t exist. Let’s fix that by first creating a file called reducer.js in the src folder. Once you have created this file, add the following JavaScript into it:
```
// Reducer
function counter(state, action) {
  if (state === undefined) {
    return { count: 0 };
  }

  var count = state.count;

  switch (action.type) {
    case "increase":
      return { count: count + 1 };
    case "decrease":
      return { count: count - 1 };
    default:
      return state;
  }
}

export default counter;
```
Our reducer is pretty simple. We have a count variable that we initialize to 0 if our state is empty. There are two action types this reducer is going to deal with: increase and decrease. If the action type is increase, we up our count value by 1. If our action type is decrease, we decrease our count value by 1 instead.

At this point, we are about half-way done building our example:

We are ready to go one level deeper in our app. It’s time to deal with our App component. Inside the src folder, create a new file called App.js. Inside there, add the following:
```
import { connect } from "react-redux";
import Counter from "./Counter";

// Map Redux state to component props
function mapStateToProps(state) {
  return {
    countValue: state.count;
  }
}

// Action
var increaseAction = { type: "increase" };
var decreaseAction = { type: "decrease" };

// Map Redux actions to component props
function mapDispatchToProps(dispatch) {
  return {
    increaseCount: function() {
      return dispatch(increaseAction);
    },
    decreaseCount: function() {
      return dispatch(decreaseAction);
    }
  }
}

// The HOC
var connectedComponent = connect(
  mapStateToProps,
  mapDispatchToProps
)(Counter);

export default connectedComponent;
```
Take a few moments to see what we have going on here. The main purpose of the code here is to turn all of the Redux-specific hooks into something we can use in React. More specifically, we provide all of those hooks as props that our component can easily consume through two functions called mapStateToProps and mapDispatchToProps.

First up is our mapStateToProps function:
```
// Map Redux state to component props
function mapStateToProps(state) {
  return {
    countValue: state.count;
  }
}
```
This function subscribes to all store updates and gets called when anything in our store changes. What it returns is an object that contains the store data you wish to transmit as props to a component. In our case, what we are transmitting is pretty simple - an object that contains a property called countValue whose value is represented by our old count property from the store.

Providing the store value as props is only one part of what we need to do. The next part is to provide our component access to the action creators and actions, also in the form of props. That is handled by the following code:
```
// Action
var increaseAction = { type: "increase" };
var decreaseAction = { type: "decrease" };

// Map Redux actions to component props
function mapDispatchToProps(dispatch) {
  return {
    increaseCount: function() {
      return dispatch(increaseAction);
    },
    decreaseCount: function() {
      return dispatch(decreaseAction);
    }
  }
}
```
The really interesting stuff happens with mapDispatchToProps. We return an object containing the name of the two functions our component can call to dispatch a change to our store. The increaseCount function fires off a dispatch with an action type of increase. The decreaseCount function fires off a dispatch with an action type of decrease. If we look at the reducer we added a few moments ago, you can see how either of these function calls will affect the value of count we are storing in our store.

All that remains now is to ensure whatever component we want to provide all these props to has some way of actually receiving them. That is where the magical connect function comes in:
```
var connectedComponent = connect(
  mapStateToProps,
  mapDispatchToProps
)(Counter)
```
This function creates the magical Connect HOC we talked about earlier. It takes our mapStateToProps and mapDispatchToProps functions as arguments, and it passes all of that into the Counter component, which you also specify. The end result of all this code running is the equivalent of rendering the following:
```
<Connect>
  <Counter increaseCount={increaseCount}
           decreaseCount={decreaseCount}
           countValue={countValue}/>
</Connect>
```
Our Counter component gets access to increaseCount, decreaseCount, and countValue. The only strange thing is that there is no render function or equivalent in sight. All of that is handled automatically by React and its treatment of HOC!

We are almost done here! It’s time to get our Counter component up and running. In your src directory, add a file called Counter.js. Put the following things into it:
```
import React, { Component } from "react";

class Counter extends Component {
  render() {
    return (
      <div className="container">
        <button className="buttons"
                onClick={this.props.decreaseCount}>-</button>
        <span>{this.props.countValue}</span>
        <button className="buttons"
                onClick={this.props.increaseCount}>+</button>
      </div>
    );
  }
};

export default Counter;
```
This will probably be the most boring component you will have seen in quite some time. We already talked about how our Connect HOC sends down props and other related shenanigans to our Counter component. You can see those props in use here to display the counter value or call the appropriate function when our plus or minus buttons are clicked!

The last thing we are going to do is define our CSS file to style our counter. In the same src folder we’ve been working in all this time, create a file called index.css. Inside this file, add the following style rules:

```
body {
  margin: 0;
  padding: 0;
  font-family: sans-serif;
  display: flex;
  justify-content: center;
  background-color: #8E7C93;
}

.container {
  background-color: #FFF;
  margin: 100px;
  padding: 10px;
  border-radius: 3px;
  width: 200px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.buttons {
  background-color: transparent;
  border: none;
  font-size: 16px;
  font-weight: bold;
  border-radius: 3px;
  transition: all .15s ease-in;
}

.buttons:hover:nth-child(1) {
  background-color: #F45B69;
}

.buttons:hover:nth-child(3) {
  background-color: #C0DFA1;
}

```At this point, we are done with our example. If you haven’t done so yet, save your changes across all of the files we’ve been working on. If you preview your app in the browser (npm start), you will see your counter working as expected.

# CONCLUSION

In many ways, you could argue that Redux is designed to fix some of the shortcomings that React often claims as advantages. We looked at some of these advantages when we examined how data in React is supposed to flow. You could even go further and say that the ideas behind Redux should be formalized as part of React itself so that you get even better integration. The thing is this: Redux isn’t perfect either. Like many things in programming, it is simply one of many tools you have for accomplishing a task. Not every situation involving data will need Redux. Sometimes, adding Redux can add unnecessary complexity to what you are trying to do. Dan Abramov, one of the creators of Redux, wrote a great article (https://medium.com/@dan_abramov/you-might-not-need-redux-be46360cf367) explaining some of the situations when you probably shouldn’t use Redux for solving your problem. I highly encourage you to read that to get the full picture :P