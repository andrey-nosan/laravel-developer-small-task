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
                        <td class="align-middle">{{ message.subject | truncate(30, '...') }}</td>
                        <td class="align-middle">{{ message.body_content | truncate(50, '...') }}</td>
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
                current_message_body: '',
            }
        },
        mounted() {
            this.update();
        },
        filters: {
            truncate: function (text, length, suffix) {
                if (text.length > length) {
                    return text.substring(0, length) + suffix;
                } else {
                    return text;
                }
            },
        },
        methods: {
            update: function (page = 1) {
                axios.get(route('api.message.index') + '?page=' + page).then(response => {
                    this.messages = response.data.messages;
                });
            },
            deleteMessage: function (id, index) {
                if (confirm("Do you really want to delete it?")) {
                    axios.delete(route('api.message.destroy', {message: id}))
                        .then(() => {
                            this.messages.data.splice(index, 1);
                        })
                        .catch(error => {
                            console.log(error.response.data.errors);
                            alert("Could not delete company");
                        });
                }
            },
            sendMessage: function (id) {
                axios.get(route('api.message.send', {message: id}))
                    .then(response => {
                        this.messages = response.data.messages;
                        this.$router.replace({path: '/'});
                    })
                    .catch(error => {
                        console.log(error.response.data.errors);
                        alert("Could not send message");
                    });
            }
        }
    }
</script>
