<template>

    <div class="container">

        <div class="row">

            <h1 class="text-center">{{family.name}} Family Tasks</h1>

        </div>

        <div class="row">

            <ul>

                <li v-for="task in tasks" v-text="task"></li>

            </ul>

        </div>

        <div class="row">

            <input type="text" v-model="body" @keyup="keyup">

        </div>

        <div class="row">

            <span v-show="activeUser">{{activeUser.name}} is typing...</span>

        </div>

        <div class="row">

            <button class="btn btn-success" @click="save">save</button>

        </div>

    </div>

</template>

<script>
    export default {
        props : ['family'],
        data(){
            return {
                tasks : [],
                body : '',
                activeUser : false,
                typingTimer : false

            }
        },
        methods:{
          save(){
              this.activeUser = false;
              axios.post('/families/' + this.family.id + '/tasks',{body : this.body});
              this.tasks.push(this.body);
              this.body = '';
          },
            keyup(){
                this.channel.whisper('typing',{
                 name : app.user.name
              });
            }
        },
        mounted() {
            axios.get('/families/' + this.family.id + '/tasks').then(respone => this.tasks = respone.data);

            this.channel

                .listen('TaskCreated',event => {this.tasks.push(event.task.body);})

                .listenForWhisper('typing', e => {

                    this.activeUser = e;

                    if(this.typingTimer) clearTimeout(this.typingTimer);

                    this.typingTimer = setTimeout(() => {

                        this.activeUser = false;

                    },3000);

                });
        },
        computed :{
            channel(){
                return Echo.private("tasks." + this.family.id);
            }
        }
    }
</script>
