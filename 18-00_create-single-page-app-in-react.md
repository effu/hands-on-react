# 18. Creating a Single-Page App in React using React Router

Now that you’ve familiarized yourself with the basics of how to work with React, let’s kick things up a few notches. What we are going to do is use React to build a simple single-page app (also referred to as SPA by the cool kids...and people living in Scandinavia). Like we talked about in our React introduction forever ago, single-page apps are different from the more traditional multi-page apps that you see everywhere. The biggest difference is that navigating a single-page app doesn’t involve going to an entirely new page. Instead, your pages (commonly known as views in this context) typically load inline within the same page itself:

When you are loading content inline, things get a little challenging. The hard part is not loading the content itself. That is relatively easy. The hard part is making sure that single-page apps behave in a way that is consistent with what your users are used to. More specifically, when users navigate your app, they expect that:

1. The URL displayed in the address bar always reflects the thing that they are viewing.  
2. They can use the browser’s back and forward buttons...successfully.  
3. They can navigate to a particular view (aka deep link) directly using the appropriate URL.  

With multi-page apps, these three things come for free. There is nothing extra you have to do for any of it. With single-page apps, because you aren’t navigating to an entirely new page, you have to do real work to deal with these three things that your users expect to just work. You need to ensure that navigating within your app adjusts the URL appropriately. You need to ensure your browser’s history is properly synchronized with each navigation to allow users to use the back and forward buttons. If users bookmark a particular view or copy/paste a URL to access later, you need to ensure that your single-page app takes the user to the correct place.

To deal with all of this, you have a bucket full of techniques commonly known as routing. Routing is where you try to map URLs to destinations that aren’t physical pages such as the individual views in your single-page app. That sounds complicated, but fortunately there are a bunch of JavaScript libraries that help us out with this. One such JavaScript library is the star of this tutorial, React Router (https://github.com/reactjs/react-router). React Router provides routing capabilities to single-page apps built in React, and what makes it nice is that extends what you already know about React in familiar ways to give you all of this routing awesomeness. In this tutorial, you’ll learn all about how it does that...and hopefully more!

## React Router Example

[React Router Example on Kirupa](https://www.kirupa.com/react/examples/react_router/index.html)

What you have here is a simple React app that uses React Router to provide all of the navigation and view-loading goodness:

Click on the various links to load the relevant content, and feel free to open up this page in its own browser window (https://www.kirupa.com/react/examples/react_router/index.html) to use the back and forward buttons to see them working.

In the following sections, we are going to be building this app in pieces. By the end, not only will you have re-created this app, you’ll hopefully have learned enough about React Router to build cooler and more awesomer things.

## GETTING STARTED

The first thing we need to do is get our project setup. We’ll use our trusty create-react-app command to do this. From your favorite terminal, navigate to the folder you want to create your app, and type the following:
```
create-react-app react_spa
```
This will create our new project inside a folder called react_spa. Go ahead and navigate into this folder:
```
cd react_spa
```
Normally, this is where we start messing around with deleting the existing content to start from a blank slate. We will do that, but first, we are going to install React Router. To do that, run the following command:
```
npm i react-router-dom --save
```
This copies the appropriate React Router files and registers it in our package.json so that our app is made aware of its existence. That’s good stuff, right?

Now that you’ve done this, it is time to clean up our project to start from a clean slate. From inside your react_spa folder, delete everything found inside your public and src folders. Once you’ve done this, let’s create our index.html file that will serve as our app’s starting point. In your public folder, create a file called index.html and add the following contents into it:

```
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>React Router Example</title>
  </head>
  <body>
    <div id="root"></div>
  </body>
</html>
```

Take a quick glance at the HTML. There shouldn’t be anything surprising here. Next, we’ll create our JavaScript entry point. Inside the src folder, create a file called index.js and add the following contents into it:

```
import React from "react";
import ReactDOM from "react-dom";
import Main from "./Main";

ReactDOM.render(
  <Main/>,
  document.getElementById("root")
);
```

Our ReactDOM.render call lives here, and what we are rendering is our Main component...which doesn’t exist yet. The Main component will be the starting point for our SPA expedition using React Router, and we’ll see how starting with the next section.

BUILDING OUR SINGLE PAGE APP
The way we build our app is no different than all the apps we’ve been building so far. We will have a main parent component. Each of the individual "pages" of our app will be separate components that feed into the main component. The magic React Router brings to to the table is basically choosing which components to show and which to hide. To make this feel natural, all of this navigating is tied in with our browser’s address bar and back/forward buttons, so it is all made to look seamless.

Displaying the Initial Frame
When building a SPA, there will always be a part of your page that will remain static. This static part, also referred to as an app frame, could just be one invisible HTML element that acts as the container for all of your content, or could include some additional visual things like a header, footer, navigation, etc. In our case, our app frame will just be a component that contains UI elements for our navigation header and an empty area for content to load in.

Inside our src folder, create a new file called Main.js and add the following content into it:
```
import React, { Component } from "react";

class Main extends Component {
  render() {
    return (
        <div>
          <h1>Simple SPA</h1>
          <ul className="header">
            <li><a href="/">Home</a></li>
            <li><a href="/stuff">Stuff</a></li>
            <li><a href="/contact">Contact</a></li>
          </ul>
          <div className="content">

          </div>
        </div>
    );
  }
}

export default Main;
```
Once you have pasted this, take a look at what we have here. We have a component called Main that returns some HTML. That’s it. To see what we have so far in action, npm start it up and see what is going on in your browser.

You should see an unstyled version of an app title and some list items appear:

I know that this doesn’t look all fancy and styled, but that’s OK for now. We will deal with that later. The important thing to call out is that there is nothing React Router specific here. ABSOLUTELY NOTHING!

### Creating our Content Pages

Our app will have three pages of content. This content will just be a simple component that prints out some JSX. Let’s just get those created and out of the way! First, create a file called Home.js in our src directory and add the following content:
```
import React, { Component } from "react";

class Home extends Component {
  render() {
    return (
      <div>
        <h2>HELLO</h2>
        <p>Cras facilisis urna ornare ex volutpat, et
        convallis erat elementum. Ut aliquam, ipsum vitae
        gravida suscipit, metus dui bibendum est, eget rhoncus nibh
        metus nec massa. Maecenas hendrerit laoreet augue
        nec molestie. Cum sociis natoque penatibus et magnis
        dis parturient montes, nascetur ridiculus mus.</p>

        <p>Duis a turpis sed lacus dapibus elementum sed eu lectus.</p>
      </div>
    );
  }
}

export default Home;
```
Next, create a file called Stuff.js in the same location and add in the following:
```
import React, { Component } from "react";

class Stuff extends Component {
  render() {
    return (
      <div>
        <h2>STUFF</h2>
        <p>Mauris sem velit, vehicula eget sodales vitae,
        rhoncus eget sapien:</p>
        <ol>
          <li>Nulla pulvinar diam</li>
          <li>Facilisis bibendum</li>
          <li>Vestibulum vulputate</li>
          <li>Eget erat</li>
          <li>Id porttitor</li>
        </ol>
      </div>
    );
  }
}

export default Stuff;
```
We just have one more page left. Create a file called Contact.js in our src folder and make sure its contents are the following:
```
import React, { Component } from "react";

class Contact extends Component {
  render() {
    return (
      <div>
        <h2>GOT QUESTIONS?</h2>
        <p>The easiest thing to do is post on
        our <a href="http://forum.kirupa.com">forums</a>.
        </p>
      </div>
    );
  }
}

export default Contact;
```
That’s the last of our content we are going to add. If you took a look at what it is you were adding, you’ll see that these components can’t get any simpler. They just returned some boilerplate JSX content. Now, make sure to save all of your changes to these three files. We’ll look at how to make them useful shortly.

### Using React Router

We have our app frame in the form of our Main component. We have our content pages represented by the Home, Stuff, and Contact components. What we need to do is tie all of these together to create our app. This is where React Router comes in. To start using it, go back to Main.js, and ensure your import statements look as follows:

```
import React, { Component } from "react";
import {
  Route,
  NavLink,
  HashRouter
} from "react-router-dom";
import Home from "./Home";
import Stuff from "./Stuff";
import Contact from "./Contact";
```

We are importing Route, NavLink, and HashRouter from the react-router-dom NPM package we installed earlier. In addition, we are importing our Home, Stuff, and Contact components since we will be referencing them as part of loading our content.

The way React Router works is by defining what I call a routing region. Inside this region, you will have two things:

1. Your navigation links

2. The container to load your content into

There is a close correlation between what URL your navigation links specify and the content that ultimately gets loaded. There is no way to easily explain this without first getting our hands dirty and implementing what we just read about.

The first thing we are going to do is define our routing region. Inside our Main component’s render method, add the following highlighted lines:
```
class Main extends Component {
  render() {
    return (
      <HashRouter>
        <div>
          <h1>Simple SPA</h1>
          <ul className="header">
            <li><a href="/">Home</a></li>
            <li><a href="/stuff">Stuff</a></li>
            <li><a href="/contact">Contact</a></li>
          </ul>
          <div className="content">

          </div>
        </div>
      </HashRouter>
    );
  }
}
```
The HashRouter component provides the foundation for the navigation and browser history handling that routing is made up of. What we are going to do next is define our navigation links. We already have list elements with the a element defined. We need to replace them with the more specialized NavLink component, so go ahead and make the following highlighted changes:

class Main extends Component {
  render() {
    return (
      <HashRouter>
        <div>
          <h1>Simple SPA</h1>
          <ul className="header">
            <li><NavLink to="/">Home</NavLink></li>
            <li><NavLink to="/stuff">Stuff</NavLink></li>
            <li><NavLink to="/contact">Contact</NavLink></li>
          </ul>
          <div className="content">

          </div>
        </div>
      </HashRouter>
    );
  }
}
For each link, pay attention to the URL we are telling our router to navigate to. This URL value (defined by the to prop) acts as an identifier to ensure the right content gets loaded. The way we match the URL with the content is by using a Route component. Go ahead and add the following highlighted lines:

class Main extends Component {
  render() {
    return (
      <HashRouter>
        <div>
          <h1>Simple SPA</h1>
          <ul className="header">
            <li><NavLink to="/">Home</NavLink></li>
            <li><NavLink to="/stuff">Stuff</NavLink></li>
            <li><NavLink to="/contact">Contact</NavLink></li>
          </ul>
          <div className="content">
            <Route path="/" component={Home}/>
            <Route path="/stuff" component={Stuff}/>
            <Route path="/contact" component={Contact}/>
          </div>
        </div>
      </HashRouter>
    );
  }
}
As you can see, the Route component contains a path prop. The value you specify for the path determines when this route is going to be active. When a route is active, the component specified by the component prop gets rendered. For example, when we click on the Stuff link (whose path is /stuff as set by the NavLink component’s to prop), the route whose path value is also /stuff becomes active. This means the contents of our Stuff component get rendered.

You can see all of this for yourself. Jump back to your browser to see the live updates or run npm start again. Click around on the links to see the content loading in and out. Something seems off, though, right? The content for our home page seems to always display even if we are clicking on the Stuff or Contact links:

That seems problematic. We’ll look at how to fix that and do many more little housekeeping tasks in the next section when we go one level deeper into using React Router.

IT’S THE LITTLE THINGS
In the previous section, we got our SPA mostly up and running. We just wrapped our entire routing region inside a HashRouter component, and we separated our links and the place our links would load by using the NavLink and Route components respectively. Getting our example mostly up and running and fully up and running are two different things. In the following sections, we’ll close those differences.

Fixing our Routing
We ended the previous section by calling out that our routing has a bug in it. The contents of our Home component is always displaying. The reason for it is because the path for loading our Home component is /. Our Stuff and Contact components have the / character as part of their paths as well. This means our Home component always matches whatever path we are trying to navigate to. The fix for that is simple. In the Route component representing our home content, add the exact prop as highlighted below:

<div className="content">
  <Route exact path="/" component={Home}/>
  <Route path="/stuff" component={Stuff}/>
  <Route path="/contact" component={Contact}/>
</div>
This prop ensures the Route is active only if the path is an exact match for what is being loaded. If you preview your app now, you’ll see that the content loads correctly with the home content only displaying when our app is in the home view.

Adding Some CSS
Right now, our app is completely unstyled. The fix for that is easy. In your src folder, create a file called index.css and add the following style rules into it:

body {
  background-color: #FFCC00;
  padding: 20px;
  margin: 0;
}
h1, h2, p, ul, li {
  font-family: sans-serif;
}
ul.header li {
  display: inline;
  list-style-type: none;
  margin: 0;
}
ul.header {
  background-color: #111;
  padding: 0;
}
ul.header li a {
  color: #FFF;
  font-weight: bold;
  text-decoration: none;
  padding: 20px;
  display: inline-block;
}
.content {
  background-color: #FFF;
  padding: 20px;
}
.content h2 {
  padding: 0;
  margin: 0;
}
.content li {
  margin-bottom: 10px;
}
After you’ve done this, we need to reference this style sheet in our app. At the top of index.js, add the import statement to do just that:

import React from "react";
import ReactDOM from "react-dom";
import Main from "./Main";
import "./index.css";

ReactDOM.render(
  <Main/>,
  document.getElementById("root")
);
Save all of your changes if you haven’t done so yet. If you preview the app now, you’ll notice that it is starting to look a bit more like the example we started out with:

We are almost done here! There is just a few more things we need to do.

Highlighting the Active Link

Right now, it’s hard to tell which link corresponds to content that is currently loaded. It would be useful to have some sort of a visual cue to solve this. The creators of React Router have already thought of that! When you click on a link, a class value of active is automatically assigned to it.

For example, this is what the HTML for clicking on the Stuff link looks like:

<a aria-current="true" href="#/stuff" class="active">Stuff</a>
This means all we really have to do is add the appropriate CSS that lights up when an element has a class value of active set on it. To make this happen, go back to index.css and add the following style rule towards the bottom of your document:

.active {
  background-color: #0099FF;
}
Once you have added this rule and saved your document, go back to your browser and click around on the links in our example. You’ll see that the active link whose content is displayed from is highlighted with a blue color. What you will also see is our Home link always highlighted. That isn’t correct. The fix for that is simple. Just add the exact prop to our NavLink component representing our home content:

<li><NavLink exact to="/">Home</NavLink></li>
<li><NavLink to="/stuff">Stuff</NavLink></li>
<li><NavLink to="/contact">Contact</NavLink></li>
Once you have done that, go back to our browser. You’ll see that our Home link only gets the active color treatment when the home content is displayed:

At this point, we are also done with the code changes to build our SPA using React Router. Yay!!!

# CONCLUSION

By now, we’ve covered a good chunk of the cool functionality React Router has for helping you build your SPA. This doesn’t mean that there aren’t more interesting things for you to take advantage of. Our app was pretty simple with very modest demands on what routing functionality we needed to implement. There is a whole lot more that React Router provides (including variations of APIs for what you’ve seen here), so if you are building a more complex single-page app than what we’ve looked at so far, you should totally spend an afternoon taking a look the full React Router documentation (https://github.com/reactjs/react-router/) and examples.

