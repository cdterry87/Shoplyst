<template>
    <v-container fluid grid-list-md>
        <v-layout row>
            <v-flex xs12 sm8 offset-sm-2 md6 offset-md-3>
                <v-card class="box">
                    <div class="title text-center">
                        Add Shopping List
                    </div>
                    <v-text-field hide-details color="white" label="New Shopping List" filled prepend-inner-icon="mdi-cart" id="name" name="name" type="text">
                        <template v-slot:append-outer>
                            <v-btn x-large style="top: -14px" class="pink darken-2">
                                <v-icon>mdi-plus</v-icon>
                                Add
                            </v-btn>
                        </template>
                    </v-text-field>
                </v-card>
            </v-flex>
        </v-layout>
        <v-layout row class="mt-3">
            <v-flex xs12 sm8 offset-sm-2 md6 offset-md-3>
                <v-card class="box">
                    <div class="title text-center mb-3">
                        My Shopping Lists
                    </div>
                    <v-list-item two-line v-for="(list, index) in lists" :key="index" :to="'/list/' + list.id">
                        <v-list-item-content>
                            <v-list-item-title v-html="list.name"></v-list-item-title>
                            <v-list-item-subtitle v-html="list.created_at"></v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                    <v-divider></v-divider>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: 'Home',
        data() {
            return {
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
                    }
                ]
            }
        }
    }
</script>
