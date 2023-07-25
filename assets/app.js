/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// require jQuery normally
const $ = require('./scripts/jquery.js');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

require('./scripts/lightbox.js');
require('./scripts/frontend.js');

// any CSS you import will output into a single css file (app.css in this case)
import './styles/normalize.css'
import './styles/lightbox.css';
import './styles/frontend.less';
