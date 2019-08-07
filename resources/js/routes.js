import Home from './views/Home'
import Account from './views/Account'
import List from './views/List'
import NotFound from './views/NotFound'

export default [
    {
        path: '/home',
        name: 'home',
        component: Home
    },
    {
        path: '/account',
        name: 'account',
        component: Account
    },
    {
        path: '/list/:id',
        name: 'list',
        component: List,
        props: true
    },
    {
        path: '*',
        component: NotFound
    }
];
