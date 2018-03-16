# 19. Introduction to Redux

The greatest love story of all time is not between Romeo and Juliet. It’s not between any characters we’ve seen in books or movies. It’s actually between React and a mysterious unknown straggler from a far-away land known as Redux:

By now, we know enough about React to understand how it works and why it does some of the things it does. Redux is something that we haven’t talked about at all. We need to fix that before we can try to figure out why React and Redux get along so well. In the following sections, we are going to do just that by getting a deep dive into what Redux is.

## WHAT IS REDUX?

If there is one thing we’ve learned in all this time, it is this: maintaining application state and keeping it consistent with our UI is a major challenge. Solving this is partly why libraries like React really took off. If we cast a wider net and look beyond just the UI layer, you’ll see that maintaining application state in general is complicated. Your typical app has many layers, and each layer has its own dependency on some piece of data that it relies on to do its thing.

Often, visualizing the relationship between your app’s functionality and its application state would look pretty confusing:

To solve this more general problem of maintaining application state, you have Redux. The easiest way to understand how Redux works is by just walking through the various pieces that go into it. The first thing we are going to need is an app:

Our app doesn’t have to be anything special. It can be built in React, Angular, Vue, vanilla JS, or whatever happens to be the hot new library/framework this week. Redux doesn’t care how your app is built. All it cares is that your app has a magical way for dealing with application state and storing it. In the Redux world, we store all our application state in a single location that we will just call the Store:

The thing about the store is that it is very easy to read data from it. Getting information into it is a whole another story. The way you add new state to (or modify existing state in) our store is by using a combination of actions, which describe what to change, and a reducer that determines what the final state will be as a result for a given action. When we throw both of these into the picture, this is what we see:

There are a few more moving pieces that this diagram doesn’t highlight, but it is a good approximation of what happens when our app needs to update the state stored in our store. Now, looking at this diagram, you are probably wondering why there is all this roundaboutness and indirection. Why can’t our app just update the store directly:

The reason is scalability. Even for simple apps, keeping our application state in-sync with what our app is doing is a chore. For complex apps where different parts of your app want to access and modify the application state...fuggedaboutit! This roundabout way is Redux’s answer for helping make sure storing application state is easy for both simple apps as well as complex ones. Beyond just ease, Redux helps make maintaining your application state predictable. By predictable, this is what Dan Abramov and Andrew Clark, the creators of Redux, meant:

1. Your entire application’s state is stored in a single location. You don’t have to search across a variety of data stores to find the part of your state you want to update. Keeping everything stored in a single location also ensures you don’t have to worry about keeping all of this data in-sync!

2. Your state should be read-only and can only be modified through actions. As we saw in the diagram earlier, in a Redux world, you should ensure random parts of your app can’t access the store and modify the state stored inside it. The only way our app can modify what is in the store is by relying on actions.

3. You just specify what the final state should be. To keep things simple, your state is never modified or mutated. You use a reducer to just specify what the final result of your state should be.

These three principles may seem a bit abstract, but as we start to write some Redux code, you’ll see us put them into practice.

BUILDING A SIMPLE APP USING REDUX
What we are going to do is take all of the diagrams and text we saw in the previous section and turn it into code. The app we are going to build to highlight how Redux works is going to be a really simple console-driven app without any UI. It is an app that will store and display a list of our favorite colors. From our app, you can add colors, and you can remove colors. That’s pretty much it.

While this may seem like a step backwards from the UI-rich apps we have been building so far, this app will tie together all of this theoretical Redux knowledge into some tangible lines of code. The goal is to simply make sense of Redux. We’ll complicate our world by combining Redux with some UI later.

It’s Redux Time
The first thing we are going to do is create a new HTML document and reference the Redux library as part of it. We won’t be using create-react-app or any fancy build system here. It will just be a loose HTML file somewhere that you can view in your browser. Using your favorite code editor, go ahead and create a new file called favoriteColors.html and add the following markup:
```
<!DOCTYPE html>
<html>
  <head>
    <title>Favorite Colors!</title>
    <script src="https://unpkg.com/redux@latest/dist/redux.js"></script>
  </head>
  <body>
    <script>

    </script>
  </body>
</html>
```
As you can see, what we have here is an empty HTML document with only the basic structure defined. We are referencing a hosted version of the Redux library, which is fine for kicking the tires like we are doing. For production apps, just like we saw with React, there are better approaches you should use. We’ll look at those better approaches later, but referencing the library directly is OK for now!

Lights! Camera! Action!!!
With our Redux library referenced, the first thing we are going to do is define our actions. Remember, the action is the only mechanism we have to communicate with our Store. For our app, because we want to add and remove colors, our actions will represent that want in a way that our store will understand.

Inside your script tag, add the following lines:
```
function addColor(value) {
  return {
    type: "ADD",
    color: value
  }
}

function removeColor(value) {
  return {
    type: "REMOVE",
    color: value
  }
}
```
We have two functions called addColor and removeColor. They each take one argument and return an action object as a result. For addColor, the action object would be the highlighted two lines:
```
function addColor(value) {
  return {
    type: "ADD",
    color: value
  }
}
```
When defining an action, you have a lot of freedom. Every action object will have a type property. This is a keyword that signals what you are intending to do. Beyond that, what other information you send along with your action is entirely up to you. Because we are interested in adding or removing a color value from our store, our action object will also have a color property that stores the color we are interested in.

Now, let’s get back to our addColor and removeColor functions. They both really serve just one purpose: to return an action. There is a more formal name for these functions in the Redux world. They are known as Action Creators because they, um....create an action!

Our Reducer
While our actions define what you would like to do, the exact specifics of what happens and how our new state is defined is handled by our reducer. You can think of our reducer as the intermediary between our store and the outside world where it does the following three things:

1. Provides access to our store’s original state

2. Allows you to inspect the action that was currently fired

3. Allows you to set our store’s new state

We can see all of this when we add a reducer to deal with adding and removing colors from our store. Add the following code after where you have defined your action creators:
```
function favoriteColors(state, action) {
  if (state === undefined) {
    state = [];
  }

  if (action.type === "ADD") {
    return state.concat(action.color);
  } else if (action.type === "REMOVE") {
    return state.filter(function(item) {
      return item !== action.color;
    });
  } else {
    return state;
  }
}
```
Take a moment to walk through what this code is doing. The first thing we do is ensure we actually have some state to fiddle with:
```
function favoriteColors(state, action) {
  if (state === undefined) {
    state = [];
  }

  if (action.type === "ADD") {
    return state.concat(action.color);
  } else if (action.type === "REMOVE") {
    return state.filter(function(item) {
      return item !== action.color;
    });
  } else {
    return state;
  }
}
```
If our state object doesn’t exist, like it would the first time we launch our app, we just initialize it as an empty array. You can use any data structure you want, but an array is the right one for what we are trying to do.

From there, the rest of our code is responsible for dealing with our actions. The detail to note is that our reducer gets the full action object via its action argument. This means you have access to not only the action’s type property, but anything else you may have specified as part of defining your actions earlier.

For our example, if our action’s type is ADD, we add the color (specified by the action’s color property) to our state array. If our action’s type is REMOVE, we return a new array with the color in question omitted. Lastly, if our action’s type is something we don’t know, we just return our current state unmodified:
```
function favoriteColors(state, action) {
  if (state === undefined) {
    state = [];
  }

  if (action.type === "ADD") {
    return state.concat(action.color);
  } else if (action.type === "REMOVE") {
    return state.filter(function(item) {
      return item !== action.color;
    });
  } else {
    return state;
  }
}
```
Pretty simple, right? There is one important Redux design choice that you should keep in mind. The Redux documentation (https://redux.js.org/docs/basics/Reducers.html) describes it best, so I’ll just quote it directly below:

Things you should never do inside a reducer:

• Mutate its arguments;

• Perform side effects like API calls and routing transitions;

• Call non-pure functions, e.g. Date.now() or Math.random().

Given the same arguments, it should calculate the next state and return it. No surprises. No side effects. No API calls. No mutations. Just a calculation.

You can see this in our code. For adding new color values to our state array, we used the concat method that returns an entirely new array made up of the old values along with the new value we are adding. While using push would still give us the same end result, it violates our goal of not modifying the existing state. For removing color values, we continue to maintain our goal of not modifying our current state. We use the filter method which returns a brand new array with the value we want to remove omitted.

What you should keep in mind and something that Mark Erikson (@acemarke)  reminded me about is this: Redux doesn’t contain any mechanics to prevent us from modifying state and making other poor choices. The creators of Redux have laid down some guidelines. It is up to us to follow them and put those guidelines into practice.

Store Stuff
All that remains now is tie our actions and reducer with our store! The first thing we are going to do is actually create our store. Below your favoriteColors reducer function, add the following:

var store = Redux.createStore(favoriteColors);
What we are doing here is creating a new Store by using the createStore method. The argument we provide is the favoriteColors reducer we created a few moments ago. Once you’ve done this, we’ve come full circle with using Redux to store our application state. We have our store, we have our reducer, and we have actions that tell our reducer what to do.

To see everything fully working, we are going add (and remove) some colors to our store. To do this, we use the dispatch method on our store object that takes an action as its argument. Go ahead and add the following lines:
```
store.dispatch(addColor("blue"));
store.dispatch(addColor("yellow"));
store.dispatch(addColor("green"));
store.dispatch(addColor("red"));
store.dispatch(addColor("gray"));
store.dispatch(addColor("orange"));
store.dispatch(removeColor("gray"));
```
Each dispatch call sends an action to our reducer. Our reducer takes the action and performs the appropriate work to define our new state. To see our Store’s current state, you can just add the following after all of our dispatch calls:

console.log(store.getState());
The getState method, as its name implies, returns our state’s value. If you preview your app in the browser and bring up your browser’s developer tools, you’ll see the colors we added get displayed in the console:

We are almost done here. There is just one more really important thing we need to cover. In real world scenarios, you will want to be notified each time our application’s state has been modified. This push model makes our lives much easier if we want to update UI or perform other tasks as a result of some change to our store. To accomplish this, you have the subscribe method that allows you to specify a function (aka a listener) that gets called each time the contents of our store get modified. To see the subscribe method in action, just after you defined our store object, add the following highlighted lines:
```
var store = Redux.createStore(favoriteColors);
store.subscribe(render);

function render() {
  console.log(store.getState());
}
```
After you’ve done this, preview your app again. This time, each time we call dispatch to fire another action, the render function will get called when our store is modified. Phew!

# CONCLUSION

We’ve taken a whirlwind tour of Redux and the major pieces of functionality it brings to the table. We not only looked at the concepts that make Redux really useful for dealing with application state, we also looked at the code to make everything real. The only thing we didn’t get to do was create a more realistic example. Redux is flexible enough to work with any UI framework, and each UI framework has its own magic in working with Redux. Our UI framework of choice is, of course, React! We’ll look at how to tie them together in the next chapter.
