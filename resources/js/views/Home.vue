<template>
    <v-container fluid grid-list-md>
        <v-layout row>
            <v-flex xs12 sm8 offset-sm2 md6 offset-md3>
                <div class="title text-center mb-3">
                    Add Shopping List
                </div>
                <v-form method="POST" id="listForm" @submit.prevent="addList" ref="form" lazy-validation>
                    <v-text-field hide-details color="white" label="New Shopping List" filled prepend-inner-icon="mdi-cart" id="name" name="name" type="text" maxlength="250" :rules="[v => !!v || 'List name is required']" required>
                        <template v-slot:append-outer>
                            <v-btn x-large style="top: -16px" class="pink darken-2 add">
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
                <v-list-item v-for="(list, index) in lists" :key="index" :to="'/list/' + list.id">
                    <v-list-item-content>
                        <v-list-item-title v-html="list.name"></v-list-item-title>
                        <v-list-item-subtitle v-html="list.created_at"></v-list-item-subtitle>
                    </v-list-item-content>
                    <v-list-item-action>
                        <v-btn icon @click="deleteList">
                            <v-icon color="grey lighten-1">mdi-close</v-icon>
                        </v-btn>
                    </v-list-item-action>
                </v-list-item>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: 'Home',
        data() {
            return {
                name: '',
                lists: [
                    {
                        id: 1,
                        name: 'Grocery List',
                        created_at: 'Saturday, August 3rd, 2019'
                    },
                    {
                        id: 2,
                        name: 'More Groceries',
                        created_at: 'Tuesday, August 6th, 2019'
                    },
                    {
                        id: 3,
                        name: 'Just Another Simple Grocery Shopping List',
                        created_at: 'Wednesday, August 7th, 2019'
                    }
                ]
            }
        },
        methods: {
            getLists() {

            },
            addList() {
                if (this.$refs.form.validate()) {
                    let name = this.name

                    axios.post('/api/projects', { name })
                    .then(response => {
                        this.getLists()

                        Event.$emit('success', response.data.message)
                    })
                    .catch(function (error) {
                        Event.$emit('error', response.data.message)
                    })

                    this.name = ''
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
        }
    }
</script>
