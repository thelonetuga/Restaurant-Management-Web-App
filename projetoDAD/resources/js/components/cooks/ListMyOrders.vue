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
				<v-toolbar-title>My Orders</v-toolbar-title>
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
						<td class="text-xs-left"
						    v-bind:style="[props.item.state === 'confirmed' ? {'backgroundColor': theme.primary}: {}]">
							<v-icon>visibility</v-icon>
						</td>
					</tr>
				</template>
				<template slot="expand" slot-scope="props">
					<v-btn v-if="props.item.state === 'in preparation'" v-on:click.prevent="setPrepared(props.item, 'prepared')"
					       style="margin-left: 15%"
					       color="success">Set
						Prepared
					</v-btn>
					<v-btn v-if="props.item.state === 'confirmed'" v-on:click.prevent="setPrepared(props.item, 'in preparation')"
					       style="margin-left: 15%"
					       color="info">Set
						In Preparation
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
                        text: "Action", align: "left",
                        sortable: false, value: "updated_at"
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
            fetchOrders() {
                let vm = this;
                this.url = "/api/cook/orders/" + this.$store.state.user.id;
                axios
                    .get("/api/cook/orders/" + this.$store.state.user.id)
                    .then(function (response) {
                        // handle success
                        vm.orders = response.data.data;
                        vm.loading = false;
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
                let vm = this;
                axios.get(this.url + "?page=" + page)
                    .then(response => {
                        vm.loading = false;
                        vm.orders = response.data.data;
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
            }, setPrepared(item, state) {
                item.state = state;
                axios
                    .put("api/orders/" + item.id, item)
                    .then(response => {
                        Object.assign(item, response.data.data);
                        this.$emit("item-saved", item);
                        let data = {
                            type: item.state === 'prepared' ? 3 : 2,
                            order: response.data.data
                        };
                        this.$socket.emit('order_changed', data);
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
                this.$toasted.success('Table Orders updated');
                this.fetchOrders();
            }
        },
    };
</script>
<
style
scoped >

< /style>
