<template>
    <div class="container">
        <div class="row justify-content-end">
            <router-link :to="{name: 'createMessage'}" class="btn btn-primary">Create message</router-link>
        </div>
        <pagination :data="messages" @pagination-change-page="update"></pagination>
        <div class="row justify-content-center">
            <table class="table table-sm table-bordered table-striped">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Body</th>
                        <th scope="col">Sent</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(message, index) in messages.data" :key="message.id">
                        <!--todo: improve numbering-->
                        <th class="text-center align-middle" scope="row">{{ index + 1 + messages.per_page * (messages.current_page - 1) }}</th>
                        <td class="align-middle">{{ message.subject }}</td>
                        <td class="align-middle">{{ message.body_content }}</td>
                        <td class="align-middle">{{ message.is_sent }}</td>
                        <td>
                            <router-link :to="{name: 'editMessage', params: {id: message.id}}" class="btn btn-xs btn-primary">
                                Edit
                            </router-link>
                            <a href="#"
                               class="btn btn-xs btn-danger"
                               v-on:click="deleteMessage(message.id, index)">
                                Delete
                            </a>
                            <a href="#"
                               class="btn btn-xs btn-primary"
                               v-on:click="sendMessage(message.id)">
                                Send
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <pagination :data="messages" @pagination-change-page="update"></pagination>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                'messages': {},
            }
        },
        mounted() {
            this.update();
        },
        methods: {
            update: function (page = 1) {
                axios.get('/api/message?page=' + page).then((response) => {
                    this.messages = response.data.messages;
                });
            },
            deleteMessage: function (id, index) {
                if (confirm("Do you really want to delete it?")) {
                    axios.delete('/api/message/' + id)
                        .then((response) => {
                            this.messages.data.splice(index, 1);
                        })
                        .catch((response) => {
                            alert("Could not delete company");
                        });
                }
            },
            sendMessage: function (id) {
                axios.get('/api/message/' + id + '/send')
                    .then((response) => {
                        this.$router.replace({path: '/'});
                    })
                    .catch(function (response) {
                        console.log(response);
                        alert("Could not send message");
                    });
            }
        }
    }
</script>
