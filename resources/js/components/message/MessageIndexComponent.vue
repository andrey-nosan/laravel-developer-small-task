<template>
    <div class="container">
        <div class="row justify-content-center">
            <table class="table table-sm table-bordered">
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
                    <th>{{ index + 1 + messages.per_page * (messages.current_page - 1) }}</th>
                    <td>{{ message.subject }}</td>
                    <td>{{ message.body_content }}</td>
                    <td>{{ message.is_sent }}</td>
                    <td>
                        <a class="btn btn-primary" href="#" title="Edit message">Edit</a>
                        <button type="button" class="btn btn-danger">Delete</button>
                        <a class="btn btn-info" href="#" title="Send message">Send</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <pagination :data="messages" @pagination-change-page="update"></pagination>
        </div>
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
                axios.get('/message?page=' + page).then((response) => {
                    console.log(response.data.messages);
                    this.messages = response.data.messages;
                });
            }
        }

    }
</script>
