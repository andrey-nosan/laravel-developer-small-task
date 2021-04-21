<template>
    <div class="container">
        <div v-if="is_loaded" class="panel panel-default">
            <h2 class="panel-heading">New message</h2>
            <div class="panel-body">
                <form v-on:submit.prevent="saveForm()">
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <div class="form-line">
                                <label for="subject" class="control-label">Subject</label>
                                <input type="text" v-model="message.subject"
                                       class="form-control" id="subject"
                                       :class="{'is-invalid': errors.subject }">
                            </div>
                            <span v-for="error in errors.subject" class="error text-danger">{{error}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <div class="form-line">
                                <label for="body" class="control-label">Body</label>
                                <textarea class="form-control" v-model="message.body" id="body"
                                          :class="{'is-invalid': errors.body }"></textarea>
                            </div>
                            <span v-for="error in errors.body" class="error text-danger">{{error}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="recipient_teacher">Teachers:</label>
                            <select class="form-control" v-model="teachers" id="recipient_teacher" multiple
                                    :class="{'is-invalid': errors.teachers }">
                                <option v-for="teacher in teachersList" :value="teacher.id">{{ teacher.fullname }} ({{ teacher.email }})</option>
                            </select>
                            <span v-for="error in errors.teachers" class="error text-danger">{{error}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="recipient_student">Students:</label>
                            <select class="form-control" v-model="students" id="recipient_student" multiple
                                    :class="{'is-invalid': errors.students }">
                                <option v-for="student in studentsList" :value="student.id">{{ student.fullname }} ({{ student.email }})</option>
                            </select>
                            <span v-for="error in errors.students" class="error text-danger">{{error}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 form-group">
                            <router-link to="/" class="btn btn-outline-primary">Cancel</router-link>
                            <button class="btn btn-success">Create</button>
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
            axios.get(route('api.v1.message.create'))
                .then(response => {
                    this.teachersList = response.data.teachers;
                    this.studentsList = response.data.students;

                    this.is_loaded = true;
                })
                .catch(error => {
                    console.log(error.response.data.errors);
                    alert("Could not load message")
                });
        },
        data() {
            return {
                errors: {
                    body: null,
                    subject: null,
                },
                is_loaded: false,
                message: {
                    subject: '',
                    body: '',
                    teachers: [],
                    students: [],
                },
                teachers: [],
                teachersList: {},
                teacher: {
                    fullname: '',
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
                axios.post(route('api.v1.message.store'), this.message)
                    .then(() => {
                        this.$router.push({path: '/'});
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                    });
            }
        }
    }
</script>
