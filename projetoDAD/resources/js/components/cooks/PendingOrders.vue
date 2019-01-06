<template>
	<div v-if="loading" class="text-xs-center">
		<v-progress-circular
						:size="50"
						color="primary"
						indeterminate
		></v-progress-circular>
	</div>
	<div v-else>
		<div class="text-xs-center">
			<v-pagination v-model="currentPage" :length="lastPage" @input="makePagination" circle></v-pagination>
		</div>
		<v-toolbar flat>
			<v-toolbar-title>Confirmed Orders</v-toolbar-title>
		</v-toolbar>
		<v-data-table
						:headers="headers"
						:items="orders"
						hide-actions
						class="elevation-1">
			<template slot="items" slot-scope="props">
				<tr @click="props.expanded = !props.expanded">
					<td>
						{{props.item.id }}
					</td>
					<td class="text-xs-left">
						{{props.item.state }}
					</td>
					<td class="text-xs-left">
						{{props.item.item === null ? '':props.item.item.name }}
					</td>
					<td class="text-xs-left">
						{{props.item.created_at }}
					</td>
					<td class="text-xs-left">
						{{props.item.updated_at }}
					</td>
					<td class="text-xs-left">
						{{props.item.responsible_cook_id }}
					</td>
					<td class="text-xs-left">
						<v-icon>visibility</v-icon>
					</td>
				</tr>
			</template>
			<template slot="expand" slot-scope="props">
				<v-btn v-if="props.item.responsible_cook_id === null" v-on:click.prevent="setToME(props.item)"
				       style="margin-left: 15%"
				       color="success">Assign to me
				</v-btn>
			</template>
		</v-data-table>
	</div>
</template>


<script>

    export default {
        props: ['updateOrderTable'],
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
                    },
                    {
                        text: "Cook id", align: "left",
                        sortable: false, value: "updated_at"
                    },
                    {
                        text: "Assign to Me", align: "left",
                        sortable: false, value: "assign"
                    }
                ],
                orders: [],
                currentPage: 1,
                lastPage: 0,
                url: '',
                loading: true,
                order: {
                    id: "",
                    state: "",
                    item: "",
                    created_at: "",
                    updated_at: "",
                    responsible_cook_id: ""
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
                this.loading = true;
                this.url = "/api/cook/orders/pending";
                axios
                    .get("/api/cook/orders/pending")
                    .then(function (response) {
                        // handle success
                        vm.loading = false;
                        vm.orders = response.data.data;
                        vm.totalMeals = response.data.data.total;
                        vm.currentPage = response.data.meta.current_page;
                        vm.lastPage = response.data.meta.last_page;
                        vm.pagination = response.data.meta.links;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error.data);
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
                        this.orders = response.data.data;
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
            }, setToME(item) {
                item.responsible_cook_id = this.$store.state.user.id;
                axios
                    .put("api/orders/" + item.id, item)
                    .then(response => {
                        Object.assign(item, response.data.data);
                        this.$emit("item-saved", item);
                        this.$socket.emit('order_changed', response.data.data);
                        this.fetchOrders();
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            }
        },
        watch: {
            updateOrderTable: function () {
                this.$toasted.success('Orders updated');
                this.fetchOrders();
            }
        }
    };
</script>
<
style
scoped >

< /style>
