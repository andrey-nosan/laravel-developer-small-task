window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

try {
    var routes = require('./routes.json');
    window.route = function() {
        let args = Array.prototype.slice.call(arguments);
        let name = args.shift();
        args = args.shift();
        let key;

        if (routes[name] === undefined) {
            console.warn('Unknown route ', name);
        } else {
            let url = '/' + routes[name];

            for (key in args) {
                let reg = "(\\{" + key.toString() + "\\?\\})|(\\{" + key.toString() + "\\})";
                url = url.replace(new RegExp(reg, 'g'), args[key]);
            }
            let notFound = url.match(new RegExp('\\{[a-z,A-Z]{0,}\\}', 'g'));
            if (notFound !== null) {
                alert('You did not pass the parameters: ' + notFound);
                console.warn('You did not pass the parameters: ', notFound);
                return false;
            }

            return url.replace(/\{[a-z,A-Z]{0,}\?\}/g, '')
                .replace(/\/\/\//, '/')
                .replace(/\/\//, '/')
                .replace(/\/$/, '');
        }
    };
} catch (e) {
    console.warn(e);
}
