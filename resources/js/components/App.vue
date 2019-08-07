<template>
    <div>
        <v-app class="inspire">
            <v-toolbar color="transparent" flat>
                <v-spacer></v-spacer>
                <v-btn outlined text to="/home" v-if="this.$router.currentRoute.name != 'home'">
                    <v-icon>mdi-home</v-icon>
                    <span class="ml-3">Home</span>
                </v-btn>
                <v-btn outlined text to="/account" class="ml-3">
                    <v-icon>mdi-account-circle</v-icon>
                    <span class="ml-3">My Account</span>
                </v-btn>
            </v-toolbar>

            <v-content>
                <router-view></router-view>

                <v-snackbar v-model="snackbar.enabled" :color="snackbar.color" :bottom="true" :right="true" :timeout="snackbar.timeout">
                    {{ snackbar.message }}
                    <v-btn color="white" text @click="snackbar.enabled = false"><v-icon>mdi-close</v-icon></v-btn>
                </v-snackbar>
            </v-content>

            <div class="footer text-center mt-5 mb-3">
                Shoplyst &copy; 2019
            </div>
        </v-app>
    </div>
</template>

<script>
import Event from './../events'

export default {
    name: 'App',
    data() {
        return {
            snackbar: {
                enabled: false,
                message: '',
                timeout: 5000,
                y: 'bottom',
                x: 'right',
                color: ''
            },
        }
    },
    methods: {
        logout() {
            axios.get('/api/logout')
            .then(function () {
                location.reload()
            });
        }
    },
    created() {
        Event.$on('success', message => {
            this.snackbar.message = message
            this.snackbar.color = 'success'
            this.snackbar.enabled = true
        })
        Event.$on('warning', message => {
            this.snackbar.message = message
            this.snackbar.color = 'warning'
            this.snackbar.enabled = true
        })
        Event.$on('error', message => {
            this.snackbar.message = message
            this.snackbar.color = 'error'
            this.snackbar.enabled = true
        })
    }
}
</script>
