<template>

    <div class="container">

        <div class="row">

            <div class="col-md-8">

                <div class="panel panel-default mx-auto">

                    <div class="panel-heading text-center">

                        <span class="glyphicon glyphicon-comment"></span> Chat Room

                        <span class="pull-right glyphicon glyphicon-trash" title="Delete All Messages" @click="truncate"></span>
                    </div>

                    <div class="panel-body">

                        <p v-for="message in messages"> {{message.user.name}} : {{message.body}}</p>

                    </div>

                    <div class="panel-footer">

                        <input type="text" v-model="body" class="form-control" placeholder="Your Message..." @keyup="flashUsers">

                        <span v-show="activeUser">{{activeUser.name}} is typing...</span>

                        <button class="btn btn-success btn-block mt-3" @click="save">Send</button>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <h1>Active Users</h1>

                <ul>

                    <li v-for="user in onlineUsers" v-text="user.name"></li>

                </ul>

            </div>

        </div>

    </div>

</template>

<script>

    export default {

        data() {
            return {
                messages: [],
                body: '',
                typingTimer : false,
                activeUser : false,
                onlineUsers : [],
            }
        },
        methods :{
            truncate(){
                axios.delete('/messages').then(this.messages = []);
            },
            save(){
                this.activeUser = false;
                axios.post('/messages',{body : this.body , user_id : app.user.id}).then(response => this.messages.push(response.data));
                this.body = '';
            },
            flashUsers(){
                this.channel.whisper('typing',{
                    name : app.user.name
                });
            }
        },
        computed: {
            channel() {
                return Echo.join('Messages');
            },
        },
        mounted() {
            axios.get('/messages').then(response => this.messages = response.data);

            this.channel

                .here(users => {
                    this.onlineUsers = users;
                })

                .joining(user => {
                    this.onlineUsers.push(user);
                })

                .leaving(user => {
                    this.onlineUsers.splice(this.onlineUsers.indexOf(user),1);
                })

                .listen('NewMessage', (e) => this.messages.push(e.message))

                .listenForWhisper('typing', e => {

                    this.activeUser = e;

                if(this.typingTimer) clearTimeout(this.typingTimer);

                this.typingTimer = setTimeout(() => {

                    this.activeUser = false;

                },2000);
            });
        }
    }

</script>
