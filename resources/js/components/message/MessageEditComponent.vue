<template>
    <div class="container">
        <div class="form-group">
            <div class="row justify-content-end">
                <router-link :to="{name: 'createMessage'}" class="btn btn-primary">Create message</router-link>
            </div>
        </div>
        <div v-if="is_loaded" class="panel panel-default">
            <h2 class="panel-heading">Edit message</h2>
            <div class="panel-body">
                <form v-on:submit.prevent="saveForm()">
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label class="control-label">Subject</label>
                            <input type="text" v-model="message.subject" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <label class="control-label">Body</label>
                            <textarea class="form-control" v-model="message.body_content"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label>Teachers:</label>
                            <select class="form-control" v-model="teachers" :multiple="true">
                                <option v-for="teacher in teachersList"
                                        :value="teacher.id">{{ teacher.fullname }} ({{ teacher.email }})</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="recipient_student">Students:</label>
                            <select class="form-control" v-model="students" id="recipient_student" :multiple="true">
                                <option v-for="student in studentsList"
                                        :value="student.id">{{ student.fullname }} ({{ student.email }})</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <router-link to="/" class="btn btn-outline-primary">Cancel</router-link>
                            <button class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.is_loaded = false;
            this.messageId = this.$route.params.id;
            axios.get('/api/message/' + this.messageId + '/edit')
                .then((response) => {
                    this.message = response.data.message;
                    this.teachersList = response.data.teachers;
                    this.studentsList = response.data.students;

                    this.teachers = this.message.teachers.map(a => a.id);
                    this.students = this.message.students.map(a => a.id);

                    this.is_loaded = true;
                })
                .catch(() => {
                    alert("Could not load message")
                });
        },
        data() {
            return {
                is_loaded: false,
                messageId: null,
                message: {
                    subject: '',
                    body: '',
                    body_content: '',
                    is_sent: '',
                },
                teachers: [],
                teachersList: [],
                teacher: {
                    fullName: '',
                    email: '',
                },
                students: [],
                studentsList: {},
                student: {
                    fullname: '',
                    email: '',
                },
            }
        },
        methods: {
            saveForm() {
                this.message.teachers = this.teachers;
                this.message.students = this.students;
                axios.put('/api/message/' + this.messageId, this.message)
                    .then(() => {
                        this.$router.replace('/');
                    })
                    .catch((response) => {
                        console.log(response);
                        alert("Could not update message");
                    });
            }
        }
    }
</script>
