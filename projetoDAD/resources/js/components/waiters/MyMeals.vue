<template>
    <div>
        <v-toolbar flat>
            <v-toolbar-title>List of Meals</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialogCreate" fullscreen>
                <v-btn slot="activator" color="primary" dark class="mb-2">New Meal</v-btn>
                <create_meal @closeDialog="closeDialog"></create_meal>
            </v-dialog>
        </v-toolbar>
        <v-data-table
                :headers="headers"
                :items="meals"
                hide-actions
                class="elevation-1"
        >
            <template slot="items" slot-scope="props">
                <td class="text-xs-right">{{ props.item.state }}</td>
                <td class="text-xs-right">{{ props.item.table_number }}</td>
                <td class="text-xs-right">{{ props.item.start }}</td>
                <td class="text-xs-right">{{ props.item.total_price_preview }}</td>
                <td class="justify-center">
                    <v-dialog v-model="dialog" scrollable max-width="80%">
                        <v-icon
                                slot="activator"
                                class="mr-2"
                                @click="editItem(props.item.id)"
                        > edit
                        </v-icon>
                        <v-card>
                            <v-card-title>Select Items</v-card-title>
                            <v-divider></v-divider>
                            <div class="text-xs-center">
                                <v-pagination v-model="currentPage" :length="lastPage" @input="makePagination" circle></v-pagination>
                            </div>
                            <v-card-text style="height: 300px;">
                                <v-container fluid>
                                    <div v-for='item in items'>
                                        <v-checkbox
                                                type='checkbox'
                                                :key='item.id'
                                                :value='item.id'
                                                :label='item.name'
                                                v-model='edited_meal.items'
                                        ></v-checkbox>
                                    </div>
                                </v-container>
                            </v-card-text>
                            <v-divider></v-divider>
                            <v-card-actions>
                                <v-btn color="blue darken-1" flat @click="dialog = false">Close</v-btn>
                                <v-btn color="blue darken-1" flat @click="saveMeal()">Save</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-dialog v-model="info_dialog" scrollable max-width="80%">
                        <v-icon
                                slot="activator"
                                class="mr-2"
                                @click="infoItem(props.item)"
                        >info
                        </v-icon>
                        <v-card>
                            <v-card-title>Table Nº {{info_meal.meal.table_number}}</v-card-title>
                            <v-divider></v-divider>
                            <v-card-text style="height: 300px;">
                                <v-container fluid>
                                    <div>Total Price : {{total_priceCalc()}}</div>
                                    <div>Orders:</div>
                                    <v-list two-line>
                                        <template v-for="(item, index) in info_meal.orders">
                                            <v-subheader
                                                    v-if="item.header"
                                                    :key="item.header"
                                            >
                                                {{ item.header }}
                                            </v-subheader>

                                            <v-divider
                                                    v-else-if="item.divider"
                                                    :inset="item.inset"
                                                    :key="index"
                                            ></v-divider>

                                            <v-list-tile
                                                    v-else
                                                    :key="item.title"
                                                    avatar
                                                    @click=""
                                            >
                                                <v-list-tile-content>
                                                    <v-list-tile-title
                                                            v-html="item.item === null ? '' :item.item.name"></v-list-tile-title>
                                                    <v-list-tile-sub-title
                                                            v-bind:style="{ color: item.state === 'delivered' ? '#33cc33': item.state === 'prepared' ? '#00ccff': '' }"

                                                            v-html="item.state"></v-list-tile-sub-title>
                                                </v-list-tile-content>
                                                <div>{{item.item === null ? '' : item.item.price}} €</div>
                                            </v-list-tile>
                                        </template>
                                    </v-list>

                                </v-container>
                            </v-card-text>
                            <v-divider></v-divider>
                            <v-card-actions>
                                <v-btn color="blue darken-1" flat @click="info_dialog = false">Close</v-btn>
                                <v-btn color="error" flat @click="setTerminated(true)">Set Terminated</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </td>
            </template>
        </v-data-table>
        <template>
            <v-layout row justify-center>
                <v-dialog v-model="youSure" persistent max-width="290">
                    <v-card>
                        <v-card-title class="headline">Are you sure?</v-card-title>
                        <v-card-text>You have undelivered orders.</v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" flat @click="setTerminated(false)">Terminate</v-btn>
                            <v-btn color="green darken-1" flat @click="youSure = false">No</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-layout>
        </template>
    </div>
</template>

<script>
    import CreateMeal from './CreateMeal.vue'

    export default {
        props: ['updateMealTable'],
        data() {
            return {
                headers: [
                    {
                        text: "State",
                        align: "right",
                        sortable: false,
                        value: "state"
                    },
                    {
                        text: "Table Nº", align: "right",
                        sortable: false, value: "table_number"
                    },
                    {
                        text: "Start", align: "right",
                        sortable: false, value: "type"
                    },
                    {
                        text: "Total Price", align: "right",
                        sortable: false, value: "start"
                    },
                    {
                        text: "Actions", align: "left",
                        sortable: false, value: "start"
                    },
                ],
                dialog: false,
                dialogCreate: false,
                info_dialog: false,
                currentPage: 1,
                lastPage: 0,
                url: '',
                loading: true,
                items: [],
                meals: [],
                edited_meal: {
                    edit_meal: "",
                    items: []
                },
                user_id: "",
                pagination: {},
                info_meal: {
                    meal: "",
                    orders: [],
                },
                total_price: "",
                youSure: false,
                theme: {
                    primary: '#3f51b5',
                    secondary: '#b0bec5',
                    accent: '#0B610B',
                    error: '#b71c1c'
                },
                invoice: {
                    meal_id: "",
                    total_price: "",
                    state: "pending"
                },
            };
        },
        created() {
            this.fetchMeals();
        },
        methods: {
            fetchMeals(page_url) {
                let vm = this;
                //this.loading = true;
                this.url = "/api/waiter/" + this.$store.state.user.id + "/meals";
                axios.get("/api/waiter/" + this.$store.state.user.id + "/meals")
                    .then(function (response) {
                       // vm.loading = false;
                        // handle success
                        vm.meals = response.data.data;
                        vm.totalMeals = response.data.data.total;
                        vm.currentPage = response.data.meta.current_page;
                        vm.lastPage = response.data.meta.last_page;
                        vm.pagination = response.data.meta.links;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            makePagination(page) {
                this.loading = true;
                axios.get(this.url + "?page=" + page)
                        .then(response => {
                            this.loading = false;
                            this.items = response.data.data;
                            this.currentPage = response.data.meta.current_page;
                            this.lastPage = response.data.meta.last_page;
                            this.pagination = response.data.meta.links;
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                        })
                        .then(function () {
                            // always executed
                        });
            }, editItem(item) {
                this.edited_meal.edit_meal = item;
                this.fetchItems();
            },
            fetchItems() {
                let vm = this;
                this.url = "/api/items";
                axios.get("/api/items").then(function (response) {
                    // handle success
                    vm.items = response.data.data;
                    vm.currentPage = response.data.meta.current_page;
                    vm.lastPage = response.data.meta.last_page;
                    vm.pagination = response.data.meta.links;
                })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            saveMeal() {
                this.dialog = false;
                this.postMeal(this.edited_meal);
            },
            closeDialog() {
                this.dialogCreate = false;
                this.fetchMeals();
            },
            postMeal(edited_meal) {
                let vm = this;
                axios
                    .post("api/orders/multiple", edited_meal)
                    .then(response => {
                        response.data.forEach((order) => {
                            setTimeout(function() {
                                axios.patch("api/order/change/state/confirmed/"+order.id)
                                        .then(response => {
                                            let data = {
                                                type: 1,
                                                order: response.data.data
                                            };
                                            vm.$socket.emit('order_changed', data);
                                        })
                                        .catch(function (error) {
                                            // handle error
                                            console.log(error.response.data.message);
                                        })
                                        .then(function () {
                                            // always executed
                                        });
                            }, 5000);
                            this.$socket.emit('order_created', order);
                        });
                        Object.assign(edited_meal, response.data);
                        this.$emit("order-saved", edited_meal);
                        vm.edited_meal.items = [];
                        this.fetchMeals();
                        this.fetchItems();
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            }, infoItem(item) {
                let vm = this;
                axios
                    .get("/api/orders/meal/" + item.id)
                    .then(function (response) {
                        // handle success
                        vm.info_meal.orders = response.data.data;
                        vm.info_meal.meal = item;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            }, setTerminated(check) {
                var orders = this.info_meal.orders;
                let vm = this;
                for (var i = 0; i < orders.length; i++) {
                    if (orders[i].state !== "delivered" && check) {
                        this.youSure = true;
                    }
                }
                if (this.youSure === false || !check) {
                    this.youSure = false;
                    this.info_dialog = false;
                    this.info_meal.meal.state = "terminated";
                    this.info_meal.meal.total_price_preview = this.total_price;
                    this.invoice.total_price = this.total_price;
                    this.invoice.meal_id = this.info_meal.meal.id;
                    axios.put("api/meals/" + this.info_meal.meal.id, this.info_meal.meal)
                        .then(response => {
                          this.$socket.emit('meal_changed', response.data.data);
                            console.log('meal updated');
                            this.createInvoice();
                            this.fetchMeals();
                            this.fetchItems();
                            this.infoItem();
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error.response.data.message);
                        })
                        .then(function () {
                            // always executed
                        });
                    for (var i = 0; i < orders.length; i++) {
                        if (orders[i].state !== "delivered") {
                            this.closeOrder(orders[i]);
                        }
                    }
                }
            },
            closeOrder(order) {
                if (order.state !== "delivered") {
                    order.state = "not delivered";
                    axios.put("api/orders/" + order.id, order)
                        .then(response => {
                            let data ={
                                type: 5,
                                order: response.data.data
                            };
                            this.$socket.emit('order_changed', data);
                            console.log('order updated');
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error.response.data.message);
                        })
                        .then(function () {
                            // always executed
                        });
                }
            },
            total_priceCalc() {
                var price = new Number(0);
                let orders = this.info_meal.orders;
                for (var i = 0; i < orders.length; i++) {
                    if (orders[i].state === "delivered") {
                        price += parseFloat(orders[i].item.price);
                    }
                }
                this.total_price = price.toFixed(2).toString();
                return price.toFixed(2);
            },
            createInvoice() {
                axios.post("api/invoices/create", this.invoice)
                    .then(response => {
                        console.log("CRIACAO INVOICE:",response);
                        this.$socket.emit('invoice_created', response.data.data);
                        console.log('invoice created');
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error.response.data.message);
                    })
                    .then(function () {
                        // always executed
                    });
            },
        },
        components: {
            'create_meal': CreateMeal,
        },
        watch: {
            updateMealTable: function (newVal) {
                this.$toasted.show('Meal "' + newVal.changedMeal.id + 'has changed');
                this.$toasted.success('Table Meals updated');
                this.fetchMeals();
            }
        }
    };
</script>
