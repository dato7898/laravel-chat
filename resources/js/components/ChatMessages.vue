<template>
	<div class="panel-body" id="chat-body">
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
    props: ['messages', 'friend', 'user'],
    
    data() {
        return {
        	prevHeight: 0
        }
    },
    
    created() {
    	this.$emit('getmessages', this.friend);
    	this.$emit('listenchat', this.user, this.friend);
    },
    
    mounted() {
    	$('#chat-body').scroll(this.scrollEvent);
    	this.prevHeight = document.getElementById('chat-body').scrollHeight;
    },
    
    updated() {
    	var curHeight = document.getElementById('chat-body').scrollHeight;
    	if (this.prevHeight < curHeight) {
    		console.log('--------------------------')
    		$('#chat-body').animate({ scrollTop: curHeight - this.prevHeight}, 0);
    		this.prevHeight = curHeight;
    	}
    },
    
    methods: {
    	scrollEvent() {
    		if ( $('#chat-body').scrollTop() === 0 ) {
    			this.$emit('getmessages', this.friend);
    		}
    	}
    }
  };
</script>
