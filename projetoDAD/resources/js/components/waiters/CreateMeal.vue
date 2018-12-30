<template>
    <v-app>
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4>
                        <v-card class="elevation-12">
                            <v-toolbar dark color="success">
                                <v-toolbar-title>New Meal</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <v-card-text>
                                <v-form>
                                    <v-flex xs12 sm6 d-flex>
                                        <v-select v-model="meal.table_number"
                                                  :items="freeTables"
                                                  label="Table Nº"
                                                  solo
                                        ></v-select>
                                    </v-flex>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn v-on:click.prevent="addMeal()">Add Item</v-btn>
                                <v-btn v-on:click.prevent="cancel()">Cancel</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>
<script>
    export default {
        data() {
            return {
                meal: {
                    table_number: "",
                    state: "active",
                    start: "",
                    end: "",
                    responsible_waiter_id: "",
                    total_price_preview: "0",

                },
                freeTables: [],
            };
        },
        created() {
            this.fetchFreeTables();
        },
        methods: {
            addMeal() {
                let innermeal = this.meal;
                innermeal.responsible_waiter_id = this.$store.state.user.id;
                innermeal.start = new Date(Date.now())
                    .toISOString()
                    .replace("Z", " ")
                    .replace("T", " ")
                    .slice(0, 19);
                axios
                    .post("api/meals/create", innermeal)
                    .then(response => {
                        this.$socket.emit('meal_created', this.meal);
                        Object.assign(innermeal, response.data.data);
                        this.$emit("closeDialog");
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error.response.data.message);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            fetchFreeTables(page_url) {
                let vm = this;
                page_url = page_url || "/api/freetables";
                axios
                    .get(page_url)
                    .then(function (response) {
                        // handle success
                        vm.freeTables = response.data;
                        //console.log(freeTables);
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error.data);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            cancel() {
                this.$emit("closeDialog");
                this.$router.push('/privatedashboard/waiter');
            },
        },
    };
</script>
