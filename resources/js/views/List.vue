<template>
    <v-container fluid grid-list-md>
        <v-layout row>
            <v-flex xs12 sm8 offset-sm2 md6 offset-md3>
                <div class="title text-center mb-3">
                    Add Item to List
                </div>
                <v-form method="POST" id="listForm" @submit.prevent="addItem" ref="form" lazy-validation>
                    <v-text-field hide-details color="white" v-model="name" label="New Item" filled prepend-inner-icon="mdi-basket" id="name" name="name" type="text" maxlength="250" :rules="[v => !!v || 'List name is required']" required></v-text-field>
                </v-form>
            </v-flex>
        </v-layout>
        <v-layout row class="mt-3">
            <v-flex xs12 sm8 offset-sm2 md6 offset-md3>
                <div class="title text-center mb-3">
                    {{ list.name }}
                </div>
                <div v-if="loadingItems" class="mt-3 text-center">
                    <v-progress-circular
                    :size="150"
                    width="15"
                    color="pink darken-2"
                    indeterminate
                    >Loading...</v-progress-circular>
                </div>
                <div v-else>
                    <div v-if="items.length > 0">
                        <v-list-item v-for="(item, index) in items" :key="index">
                            <v-list-item-content>
                                <v-list-item-title v-html="item.name"></v-list-item-title>
                                <v-list-item-subtitle>{{ item.price }} {{ item.quantity }}</v-list-item-subtitle>
                            </v-list-item-content>
                            <v-list-item-action>
                                <v-btn icon @click="deleteItem($event, item.id)">
                                    <v-icon color="grey lighten-1">mdi-close</v-icon>
                                </v-btn>
                            </v-list-item-action>
                        </v-list-item>
                    </div>
                    <div v-else class="body-2 text-center mt-6">
                        You have not added any items to your shopping list yet.
                    </div>
                </div>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import Event from './../events'

    export default {
        name: 'List',
        props: ['id'],
        data() {
            return {
                loadingItems: false,
                name: '',
                price: '',
                quantity: '',
                reminder: '',
                list: '',
                items: []
            }
        },
        methods: {
            getItems() {
                this.loadingItems = true

                axios.get('/api/items/' + this.id)
                .then(response => {
                    this.list = response.data[0]
                    this.items = this.list.items

                    this.loadingItems = false
                })
                .catch(function (error) {
                    Event.$emit('error', 'Failed to load items.')
                })
            },
            addItem() {
                if (this.$refs.form.validate()) {
                    let name = this.name
                    let price = this.price
                    let quantity = this.quantity
                    let reminder = this.reminder
                    let list_id = this.id

                    axios.post('/api/items', { list_id, name, price, quantity, reminder })
                    .then(response => {
                        this.getItems()

                        Event.$emit('success', response.data.message)
                    })
                    .catch(function (error) {
                        Event.$emit('error', response.data.message)
                    })

                    this.name = ''
                    this.quantity = ''
                    this.price = ''
                    this.reminder = ''
                    this.$refs.form.resetValidation()
                }
            },
            deleteItem(e, id) {
                e.preventDefault();

                axios.delete('/api/items/' + id)
                .then(response => {
                    this.getItems()

                    Event.$emit('success', response.data.message)
                })
                .catch(function (error) {
                    Event.$emit('error', response.data.message)
                })
            }
        },
        mounted() {
            this.getItems()
        }
    }
</script>
