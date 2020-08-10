<template>
    <div class="input-group">
        <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage" @keydown="onTyping">

        <span class="input-group-btn">
            <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                Send
            </button>
        </span>
    </div>
</template>

<script>
    export default {
        props: ['user', 'friend'],
        
        data() {
            return {
                newMessage: '',
                canPublish: true
            }
        },

        methods: {
            sendMessage() {
                this.$emit('messagesent', {
                    user: this.user,
                    message: this.newMessage
                }, this.friend);

                this.newMessage = ''
            },
            
            onTyping() {
				if (this.canPublish) {
            		this.$emit('typing', this.user, this.friend);
            		this.canPublish = false;
            		setTimeout(() => {
            			this.canPublish = true;
            		}, 200);
            	}
            }
        }    
    }
</script>
