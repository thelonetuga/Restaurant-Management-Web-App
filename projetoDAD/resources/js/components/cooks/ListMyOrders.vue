<template>
    <v-app>
        <div class="text-xs-center">
            <v-pagination
                v-model="pagination.current_page"
                :length="pagination.last_page"
                :input="pagination.current_page"
                @next="fetchOrders(pagination.next_page_url)"
                @previous="fetchOrders(pagination.prev_page_url)"
                circle>
            </v-pagination>
        </div>
        <v-toolbar flat>
            <v-toolbar-title>List of Orders</v-toolbar-title>
        </v-toolbar>
        <v-data-table
            :headers="headers"
            :items="orders"
            hide-actions
            class="elevation-1">
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
                <v-btn v-if="props.item.state === 'in preparation'" v-on:click.prevent="setPrepared(props.item, 'prepared')" style="margin-left: 15%"
                       color="success">Set
                    Prepared
                </v-btn>
                <v-btn v-if="props.item.state === 'confirmed'" v-on:click.prevent="setPrepared(props.item, 'in preparation')" style="margin-left: 15%"
                       color="info">Set
                    In Preparation
                </v-btn>
            </template>
        </v-data-table>
    </v-app>
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
                order_id: "",
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
            this.fetchOrders();
        },
        methods: {
            fetchOrders(page_url) {
                let vm = this;
                page_url = page_url || "/api/cook/orders/" + this.$store.state.user.id;
                axios
                    .get(page_url)
                    .then(function (response) {
                        // handle success
                        vm.orders = response.data.data;
                        vm.makePagination(response.data.meta, response.data.links);
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
            }, setPrepared(item, state) {
                item.state = state;
                axios
                    .put("api/orders/" + item.id, item)
                    .then(response => {
                        Object.assign(item, response.data.data);
                        this.$emit("item-saved", item);
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            }

        }
    }
    ;
</script>
<
style
scoped >

< /style>
