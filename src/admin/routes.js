import Admin from './Components/Admin.vue';
import CreateQr from './Pages/Create-qr.vue';
import MyQr from "./Pages/My-qr.vue";
import UpdateQr from "./Pages/Update-qr.vue";

export default [{
        path: '/',
        name: 'dashboard',
        component: Admin,
        meta: {
            active: 'dashboard'
        },
    },
    {
        path: '/create-qr',
        name: 'CreateQr',
        component: CreateQr
    },

    {
        path: '/my-qr',
        name: 'MyQr',
        component: MyQr
    },

    {
        path: '/update-qr/:id',
        name: 'UpdateQr',
        component: UpdateQr
    }
];