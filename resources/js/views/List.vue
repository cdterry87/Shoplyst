<template>
    <v-container fluid grid-list-md>
        <v-layout row>
            <v-flex xs12 sm10 offset-sm1 md8 offset-md2 lg6 offset-lg3>
                <div class="text-center mb-3">
                    <h1 class="headline">{{ list.name }}</h1>
                    <h2 class="body-2 grey--text text--lighten-1">{{ list.created_date }}</h2>
                </div>
                <v-form method="POST" id="totalForm" @submit.prevent="updateTotal" ref="form" lazy-validation>
                    <div class="text-center mb-3">
                        <v-btn color="cyan" v-if="!showTotalForm" @click="showTotalForm = true">
                            <v-icon>mdi-currency-usd</v-icon>
                            {{ total }}
                        </v-btn>
                        <v-layout row v-else>
                                <v-flex xs7 sm8 md9>
                                    <v-text-field hide-details color="white" v-model="total" label="Shopping Total" filled prepend-inner-icon="mdi-currency-usd" id="total" name="total" type="text" maxlength="11" autocomplete="off" :rules="[v => !!v || 'Total is required']" required></v-text-field>
                                </v-flex>
                                <v-flex xs5 sm4 md3>
                                    <v-btn type="submit" color="green" icon>
                                        <v-icon>mdi-check</v-icon>
                                    </v-btn>
                                    <v-btn icon @click="showTotalForm = !showTotalForm" color="red">
                                        <v-icon>mdi-close</v-icon>
                                    </v-btn>
                                </v-flex>
                        </v-layout>
                    </div>
                </v-form>
                <div>
                    <v-btn block class="pink darken-2 mb-2" @click="showAddForm = !showAddForm">
                        <span v-if="!showAddForm">
                            <v-icon>mdi-plus</v-icon>
                            <span>Add Item to List</span>
                        </span>
                        <span v-else>
                            <v-icon>mdi-minus</v-icon>
                            <span>Cancel</span>
                        </span>
                    </v-btn>
                    <div v-if="showAddForm">
                        <v-form method="POST" id="listForm" @submit.prevent="addItem" ref="form" lazy-validation>
                            <v-layout row>
                                <v-flex xs12>
                                    <v-text-field hide-details color="white" v-model="name" label="New Item" filled prepend-inner-icon="mdi-basket" id="name" name="name" type="text" maxlength="250" :rules="[v => !!v || 'Item name is required']" autocomplete="off" required></v-text-field>
                                </v-flex>
                                <v-flex xs8>
                                    <v-text-field hide-details color="white" v-model="quantity" label="Quantity" filled prepend-inner-icon="mdi-numeric" id="quantity" name="quantity" type="text" :rules="[v => v > 0 || 'Quantity must be greater than 0']" autocomplete="off" required></v-text-field>
                                </v-flex>
                                <v-flex xs4>
                                    <v-btn type="submit" block x-large class="pink darken-2 add">
                                        <v-icon>mdi-plus</v-icon>
                                        <span v-if="$vuetify.breakpoint.mdAndUp">Add</span>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-form>
                    </div>
                </div>
                <Loading v-if="loadingItems" />
                <div v-else>
                    <div v-if="items.length > 0">
                        <v-list dense color="transparent">
                            <v-list-item v-for="(item, index) in items" :key="index">
                                <v-list-item-icon v-if="item.complete">
                                    <v-btn icon class="list-icon" @click="incompleteItem(item.id)">
                                        <v-icon>mdi-checkbox-marked</v-icon>
                                    </v-btn>
                                </v-list-item-icon>
                                <v-list-item-icon v-else @click="completeItem(item.id)">
                                    <v-btn icon class="list-icon">
                                        <v-icon>mdi-checkbox-blank-outline</v-icon>
                                    </v-btn>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>{{ item.name }}</v-list-item-title>
                                    <v-list-item-subtitle>
                                        Qty: {{ item.quantity }}
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                                <v-list-item-icon>
                                    <v-btn icon class="list-icon" @click="deleteItem($event, item.id)">
                                        <v-icon color="grey lighten-1">mdi-close</v-icon>
                                    </v-btn>
                                </v-list-item-icon>
                            </v-list-item>
                        </v-list>
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
    import Loading from '../components/Loading'

    export default {
        name: 'List',
        props: ['id'],
        components: {
            Loading
        },
        data() {
            return {
                showAddForm: false,
                showTotalForm: false,
                loadingItems: true,
                name: '',
                quantity: '1',
                list: '',
                total: '0.00',
                items: []
            }
        },
        methods: {
            getList() {
                axios.get('/api/lists/' + this.id)
                .then(response => {
                    this.list = response.data
                    if (this.list.total > 0) {
                        this.total = this.list.total
                    }
                })
            },
            getItems() {
                axios.get('/api/items/' + this.id)
                .then(response => {
                    this.items = response.data

                    this.loadingItems = false
                })
                .catch(function (error) {
                    Event.$emit('error', 'Failed to load items.')
                })
            },
            addItem() {
                if (this.$refs.form.validate()) {
                    let name = this.name
                    let quantity = this.quantity
                    let list_id = this.id

                    axios.post('/api/items', { list_id, name, quantity })
                    .then(response => {
                        this.getItems()

                        Event.$emit('success', response.data.message)
                    })
                    .catch(function (error) {
                        Event.$emit('error', response.data.message)
                    })

                    this.name = ''
                    this.quantity = '1'
                    this.$refs.form.resetValidation()
                    this.$refs.form.$el.name.focus()
                }
            },
            updateTotal() {
                let total = this.total

                axios.post('/api/lists/' + this.id + '/total', { total })
                .then(response => {
                    this.total = total

                    Event.$emit('success', response.data.message)
                })
                .catch(function (error) {
                    Event.$emit('error', response.data.message)
                })

                this.showTotalForm = false
            },
            completeItem(item_id) {
                axios.post('/api/items/' + item_id + '/complete')
                .then(response => {
                    this.getItems()
                })
                .catch(function (error) {
                    Event.$emit('error', response.data.message)
                })
            },
            incompleteItem(item_id) {
                axios.post('/api/items/' + item_id + '/incomplete')
                .then(response => {
                    this.getItems()
                })
                .catch(function (error) {
                    Event.$emit('error', response.data.message)
                })
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
            this.getList()
            this.getItems()
        }
    }
</script>
