<template>
	<div class="panel-body" v-bind:class="{'mobile-panel-body' : mobile == 1}" id="chat-body">
		<ul class="chat">
		    <li class="left clearfix" v-for="message in messages">
		        <div 
		        	class="chat-body clearfix"
		        	v-bind:class="{'text-right' : user.id === message.user.id}" 
		        >
		            <div class="header">
		                <strong class="primary-font">
		                    {{ message.user.name }}
		                </strong>
		            </div>
		            <p>
		                {{ message.message }}
		            </p>
		        </div>
		    </li>
		</ul>
	</div>
</template>

<script>
  export default {
    props: ['messages', 'friend', 'user', 'mobile'],
    
    data() {
        return {
        	prevHeight: 0,
        	isMessagesUpdate: false
        }
    },
    
    created() {
    	this.$emit('getmessages', this.friend);
    	this.$emit('listenchat', this.user, this.friend);
    },
    
    mounted() {
    	$('#chat-body').scroll(this.scrollEvent);
    },
    
    updated() {
    	var curHeight = document.getElementById('chat-body').scrollHeight;
    	if (this.prevHeight === 0) {
    		this.prevHeight = document.getElementById('chat-body').scrollHeight;
    		return;
    	}
    	if (this.prevHeight < curHeight && this.isMessagesUpdate) {
    		console.log('--------------------------')
    		$('#chat-body').animate({ scrollTop: curHeight - this.prevHeight}, 0);
    		this.prevHeight = curHeight;
    		this.isMessagesUpdate = false;
    	}
    },
    
    methods: {
    	scrollEvent() {
    		if ( $('#chat-body').scrollTop() === 0 ) {
    			this.isMessagesUpdate = true;
    			this.$emit('getmessages', this.friend);
    		}
    	}
    }
  };
</script>
