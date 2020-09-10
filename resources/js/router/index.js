import Vue from 'vue'
import VueRouter from 'vue-router'
import MessageIndexComponent from "../components/message/MessageIndexComponent";
import MessageCreateComponent from "../components/message/MessageCreateComponent";
import MessageEditComponent from "../components/message/MessageEditComponent";

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        components: {
            messageIndex: MessageIndexComponent
        }
    },
    {path: '/create', component: MessageCreateComponent, name: 'createMessage'},
    {path: '/message/edit/:id', component: MessageEditComponent, name: 'editMessage'},
]

const router = new VueRouter({
    mode: 'hash',
    routes
})

export default router
