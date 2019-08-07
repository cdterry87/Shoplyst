<template>
    <v-container fluid grid-list-md>
        <v-layout row>
            <v-flex xs12 sm8 offset-sm2 md6 offset-md3>
                <div class="title text-center mb-3">
                    Add Shopping List
                </div>
                <v-form method="POST" id="listForm" @submit.prevent="addList" ref="form" lazy-validation>
                    <v-text-field hide-details color="white" v-model="name" label="New Shopping List" filled prepend-inner-icon="mdi-cart" id="name" name="name" type="text" maxlength="250" :rules="[v => !!v || 'List name is required']" required>
                        <template v-slot:append-outer>
                            <v-btn type="submit" x-large style="top: -16px" class="pink darken-2 add">
                                <v-icon>mdi-plus</v-icon>
                                <span v-if="$vuetify.breakpoint.mdAndUp">Add</span>
                            </v-btn>
                        </template>
                    </v-text-field>
                </v-form>
            </v-flex>
        </v-layout>
        <v-layout row class="mt-3">
            <v-flex xs12 sm8 offset-sm2 md6 offset-md3>
                <div class="title text-center mb-3">
                    My Shopping Lists
                </div>
                <div v-if="loadingLists" class="mt-3 text-center">
                    <v-progress-circular
                    :size="150"
                    width="15"
                    color="pink darken-2"
                    indeterminate
                    >Loading...</v-progress-circular>
                </div>
                <div v-else>
                    <div v-if="lists.length > 0">
                        <v-list-item v-for="(list, index) in lists" :key="index" :to="'/list/' + list.id">
                            <v-list-item-content>
                                <v-list-item-title v-html="list.name"></v-list-item-title>
                                <v-list-item-subtitle v-html="list.created_date"></v-list-item-subtitle>
                            </v-list-item-content>
                            <v-list-item-action>
                                <v-btn icon @click="deleteList($event, list.id)">
                                    <v-icon color="grey lighten-1">mdi-close</v-icon>
                                </v-btn>
                            </v-list-item-action>
                        </v-list-item>
                    </div>
                    <div v-else class="body-2 text-center mt-6">
                        You have not created any shopping lists yet.  Add one above and start shopping!
                    </div>
                </div>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import Event from './../events'

    export default {
        name: 'Home',
        data() {
            return {
                loadingLists: true,
                name: '',
                lists: []
            }
        },
        methods: {
            getLists() {
                this.loadingLists = true

                axios.get('/api/lists')
                .then(response => {
                    this.lists = response.data
                    this.loadingLists = false
                })
                .catch(function (error) {
                    Event.$emit('error', 'Failed to load lists.')
                })
            },
            addList() {
                if (this.$refs.form.validate()) {
                    let name = this.name

                    axios.post('/api/lists', { name })
                    .then(response => {
                        this.getLists()

                        Event.$emit('success', response.data.message)
                    })
                    .catch(function (error) {
                        Event.$emit('error', response.data.message)
                    })

                    this.name = ''
                    this.$refs.form.resetValidation()
                }
            },
            deleteList(e, id) {
                e.preventDefault();

                axios.delete('/api/lists/' + id)
                .then(response => {
                    this.getLists()

                    Event.$emit('success', response.data.message)
                })
                .catch(function (error) {
                    Event.$emit('error', response.data.message)
                })
            }
        },
        mounted() {
            this.getLists()
        }
    }
</script>
