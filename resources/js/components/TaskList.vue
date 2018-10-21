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

            <input type="text" v-model="body">

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
                body : ''
            }
        },
        methods:{
          save(){
              axios.post('/families/' + this.family.id + '/tasks',{body : this.body});
              this.tasks.push(this.body);
              this.body = '';
          }
        },
        mounted() {
            axios.get('/families/' + this.family.id + '/tasks').then(respone => this.tasks = respone.data);

            Echo.private('tasks.' + this.family.id).listen('TaskCreated',event => {
                console.log(event);
                this.tasks.push(event.task.body);
            });
        }
    }
</script>
