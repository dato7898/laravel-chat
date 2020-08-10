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
                newMessage: ''
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
            	console.log('---', 'onTyping', 'chat.'+this.friend.id+'.'+this.user.id);
            	this.$emit('typing', this.user, this.friend);
            }
        }    
    }
</script>
