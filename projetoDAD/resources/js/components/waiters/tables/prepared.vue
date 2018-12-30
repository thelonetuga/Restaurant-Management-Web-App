<template>
        <v-data-table
                :headers="headers"
                :items="orders"
                hide-actions
                class="elevation-1"
                >

            <template slot="items" slot-scope="props">

                <tr @click="props.expanded = !props.expanded">
                    <td v-bind:style="[props.item.state === 'confirmed' ? {'backgroundColor': theme.primary}: {}]">
                        {{props.item.id }}
                    </td>
                    <td class="text-xs-left"
                        v-bind:style="[props.item.state === 'confirmed' ? {'backgroundColor': theme.primary}: {}]">
                        {{props.item.state }}
                    </td>
                    <td class="text-xs-left"
                        v-bind:style="[props.item.state === 'confirmed' ? {'backgroundColor': theme.primary}: {}]">
                        {{props.item.item === null ? '':props.item.item.name }}
                    </td>
                    <td class="text-xs-left"
                        v-bind:style="[props.item.state === 'confirmed' ? {'backgroundColor': theme.primary}: {}]">
                        {{props.item.created_at }}
                    </td>
                    <td class="text-xs-left"
                        v-bind:style="[props.item.state === 'confirmed' ? {'backgroundColor': theme.primary}: {}]">
                        {{props.item.updated_at }}
                    </td>
                </tr>
            </template>
            <template slot="expand" slot-scope="props">
                <v-btn v-if="props.item.state === 'prepared'" v-on:click.prevent="setDelivered(props.item)"
                       style="margin-left: 15%"
                       color="info">Set Delivered
                </v-btn>
            </template>
        </v-data-table>
</template>


<script>
    export default {
        data() {
            return {
                headers: [
                    {
                        text: "ID",
                        align: "left",
                        sortable: false,
                        value: "name"
                    },
                    {
                        text: "State", align: "left",
                        sortable: false, value: "state"
                    },
                    {
                        text: "Item", align: "left",
                        sortable: false, value: "item"
                    },
                    {
                        text: "Created At", align: "left",
                        sortable: false, value: "created_at"
                    },
                    {
                        text: "Updated At", align: "left",
                        sortable: false, value: "updated_at"
                    }
                ],
                orders: [],
                order: {
                    id: "",
                    state: "",
                    item: "",
                    created_at: "",
                    updated_at: ""
                },
                pagination: {},
                theme: {
                    primary: '#3f51b5',
                    secondary: '#b0bec5',
                    accent: '#0B610B',
                    error: '#b71c1c'
                },
            };
        },
        created() {
            this.fetchPreparedOrders();
        },
        methods: {
            fetchPreparedOrders(page_url) {
                let vm = this;
                page_url = page_url || "/api/waiter/" +this.$store.state.user.id + "/orders/prepared";
                axios
                    .get(page_url)
                    .then(function (response) {
                        // handle success
                        vm.orders = response.data.data;
                        console.log(vm.orders);
                        vm.makePagination(response.data.meta, response.data.links);
                        this.$refs.table.refresh();
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error.data);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
                this.pagination = pagination;
            },
            setDelivered(order) {
                order.state = "delivered";
                axios.put("api/orders/"+ order.id, order)
                    .then(response => {
                        let data ={
                            type: 4,
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
            },
        },
        sockets: {
            order_changed(){
                this.fetchPreparedOrders();
                console.log('preparedOrders')
            },
        },
    }
    ;
</script>