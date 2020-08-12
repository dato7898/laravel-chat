/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueTimeago from 'vue-timeago'

window.Vue = require('vue');

Vue.use(VueTimeago, {
  locale: 'en-US',
  locales: { 'en-US': require('vue-timeago/locales/en-US.json') }
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('chat-messages', require('./components/ChatMessages.vue').default);
Vue.component('chat-form', require('./components/ChatForm.vue').default);
import NotificationsDemo from './components/NotificationsDemo'
import NotificationsDropdown from './components/NotificationsDropdown'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    
    components: {
      NotificationsDemo,
      NotificationsDropdown
    },
    
    data: {
        messages: [],
        typing: false,
        typingClock: null,
        usersonline: [],
        clearTimerId: null
    },
    
    mounted () {
      this.registerServiceWorker()
    },
    
    created() {
    	Echo.join('chatty')
    		.here((users) => {
    			this.usersonline = users;
    		})
    		.joining((user) => {
    			this.usersonline.push(user);
    		})
    		.leaving((user) => {
    			this.usersonline.splice(this.usersonline.indexOf(user), 1);
    		});
    	this.subscribe();
    },

    methods: {
        fetchMessages(friend) {
            axios.get('/messages/'+friend.id).then(response => {
                this.messages = response.data;
                this.scrollToEnd();
            });
        },
        
        listenChat(user, friend) {
        	Echo.private('chat.'+user.id+'.'+friend.id)
        	  .listenForWhisper('typing', (e) => {
        	  	this.typing = true;
        	  	
        	  	clearTimeout(this.clearTimerId);
        	  	
        	  	this.clearTimerId = setTimeout(() => {
        	  		this.typing = false;
        	  	}, 900);
        	  })
			  .listen('MessageSent', (e) => {
				this.messages.push({
				  message: e.message.message,
				  user: e.user
				});
				this.scrollToEnd();
			  });
        },

        addMessage(message, friend) {
            this.messages.push(message);
            this.scrollToEnd();

            axios.post('/messages/'+friend.id, message).then(response => {
              console.log(response.data);
            });
        },
        
        scrollToEnd() {
        	$('#chat-body').animate({ scrollTop: 999999}, 1000);
        },
        
        onTyping(user, friend) {
        	Echo.private('chat.'+friend.id+'.'+user.id).whisper('typing', {});
        },
        
        /**
		 * Register the service worker.
		 */
		registerServiceWorker () {
		  if (!('serviceWorker' in navigator)) {
		    console.log('Service workers aren\'t supported in this browser.')
		    return
		  }

		  navigator.serviceWorker.register('/sw.js')
		    .then(() => this.initialiseServiceWorker())
		},
		
		initialiseServiceWorker () {
		  if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
		    console.log('Notifications aren\'t supported.')
		    return
		  }

		  if (Notification.permission === 'denied') {
		    console.log('The user has blocked notifications.')
		    return
		  }

		  if (!('PushManager' in window)) {
		    console.log('Push messaging isn\'t supported.')
		    return
		  }

		  navigator.serviceWorker.ready.then(registration => {
		    registration.pushManager.getSubscription()
		      .then(subscription => {

		        if (!subscription) {
		          return
		        }

		        this.updateSubscription(subscription)

		      })
		      .catch(e => {
		        console.log('Error during getSubscription()', e)
		      })
		  })
		},
		
		/**
		 * Subscribe for push notifications.
		 */
		subscribe () {
		  navigator.serviceWorker.ready.then(registration => {
		    const options = { userVisibleOnly: true }
		    const vapidPublicKey = window.Laravel.vapidPublicKey

		    if (vapidPublicKey) {
		      options.applicationServerKey = this.urlBase64ToUint8Array(vapidPublicKey)
		    }

		    registration.pushManager.subscribe(options)
		      .then(subscription => {
		        this.updateSubscription(subscription)
		      })
		      .catch(e => {
		        if (Notification.permission === 'denied') {
		          console.log('Permission for Notifications was denied')
		        } else {
		          console.log('Unable to subscribe to push.', e)
		        }
		      })
		  })
		},
		
		/** Send a request to the server to update user's subscription.
		 *
		 * @param {PushSubscription} subscription
		 */
		updateSubscription (subscription) {
		  const key = subscription.getKey('p256dh')
		  const token = subscription.getKey('auth')
		  const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]
		  
		  console.log(key)

		  const data = {
		    endpoint: subscription.endpoint,
		    publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
		    authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
		    contentEncoding
		  }

		  axios.post('/subscriptions', data)
		    .then(() => { })
		},

		/**
		 * https://github.com/Minishlink/physbook/blob/02a0d5d7ca0d5d2cc6d308a3a9b81244c63b3f14/app/Resources/public/js/app.js#L177
		 *
		 * @param  {String} base64String
		 * @return {Uint8Array}
		 */
		urlBase64ToUint8Array (base64String) {
		  const padding = '='.repeat((4 - base64String.length % 4) % 4)
		  const base64 = (base64String + padding)
		    .replace(/\-/g, '+')
		    .replace(/_/g, '/')

		  const rawData = window.atob(base64)
		  const outputArray = new Uint8Array(rawData.length)

		  for (let i = 0; i < rawData.length; ++i) {
		    outputArray[i] = rawData.charCodeAt(i)
		  }

		  return outputArray
		}

    }
});

