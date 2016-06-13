/**
 * This file contains any directive that should be loaded **for every page**
 * on document.ready(). It should also contain global function definitions
 * used by `app.js` later on. Any global actions that need to occur "inline"
 * as the last element of the page before </body> should be made from app.js.
 */


// Initialize Foundation
$(document).foundation();


// Define actions that should fire on `window.onload()`.
function init() {
}

window.onload = init();
