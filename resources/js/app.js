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
import ToggleNotifications from './components/ToggleNotifications'
import SendNotification from './components/SendNotification'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    
    components: {
      ToggleNotifications,
      SendNotification
    },
    
    data: {
        messages: [],
        typing: false,
        typingClock: null,
        usersonline: [],
        clearTimerId: null,
        page: 1,
        last_page: 0
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
    },

    methods: {
        fetchMessages(friend) {
        	if (this.last_page === 0 || this.last_page >= this.page) {
		        axios.get('/messages/'+friend.id+'?page='+this.page).then(response => {
		        	this.messages = response.data.data.reverse().concat(this.messages);
		        	if (this.page === 1) {
		            	this.last_page = response.data.last_page;
		            	this.scrollToEnd();
		            }
		            this.page++;
		        });
            }
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
        	$('#chat-body').animate({ scrollTop: 999999}, 100);
        },
        
        onTyping(user, friend) {
        	Echo.private('chat.'+friend.id+'.'+user.id).whisper('typing', {});
        }

    }
});

