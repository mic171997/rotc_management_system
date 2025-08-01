/** @format */

window.Swal = require('sweetalert2')

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

axios.interceptors.response.use(
  function (response) {
    return response
  },
  function (error) {
    if (error.response.status === 401 || error.response.status === 419) {
      Swal.fire({
        icon: 'info',
        title: 'Information',
        text: 'Session expired, the system will redirect you to login in a few seconds',
        timer: 3500
      })
      setTimeout(() => {
        location.assign('/login')
      }, 3500)
    } else if (error.response.status >= 500) {
      Swal.fire({
        icon: 'error',
        title: "Something isn't working.",
        text: 'Server connection unresponsive',
        timer: 120000
      })
    }

    return Promise.reject(error)
  }
)

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
