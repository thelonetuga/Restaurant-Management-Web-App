<template>
	<div v-if="loading" class="text-xs-center">
		<v-progress-circular
						:size="50"
						color="primary"
						indeterminate
		></v-progress-circular>
	</div>
	<div v-else>
		<v-toolbar>
			<v-toolbar-title>Meals Active and Terminated List</v-toolbar-title>
			<v-divider class="mx-2" inset vertical></v-divider>
		</v-toolbar>
		<v-data-table
						:headers="headers"
						:items="mealsActiveAndTerminated"
						item-key="id"
						hide-actions
		>
			<template slot="items" slot-scope="props">
				<tr @click="props.expanded = !props.expanded">
					<td class="text-xs-left">{{ props.item.id }}</td>
					<td class="text-xs-left">{{ props.item.state_invoice }}</td>
					<td class="text-xs-left">{{ props.item.state }}</td>
					<td class="text-xs-left">{{ props.item.table_number }}</td>
					<td class="text-xs-left">{{ props.item.waiter_name }}</td>
				</tr>
			</template>
			<template slot="expand" slot-scope="props" light>
				<v-container align-center>
					<v-toolbar>
						<v-toolbar-title>Orders of Meal {{props.item.id}}</v-toolbar-title>
						<v-divider class="mx-2" inset vertical></v-divider>
					</v-toolbar>
					<v-data-table
									:headers="headersOrders"
									:items="props.item.orders"
									class="elevation-1">
						<template slot="items" slot-scope="props">
							<td class="text-xs-left">{{ props.item.id }}</td>
							<td class="text-xs-left">{{ props.item.state }}</td>
							<td class="text-xs-left ">{{ props.item.item.name }}</td>
						</template>
					</v-data-table>
				</v-container>
			</template>
		</v-data-table>
		<div class="text-xs-center">
			<v-pagination
							v-model="currentPage"
							:length=lastPage
							@input="makePagination"
							circle
			></v-pagination>
		</div>
	</div>
</template>

<script>
    export default {
        data() {
            return {
                mealsActiveAndTerminated: [],
                totalMeals: 0,
                totalOrders: 0,
                loading: true,
                currentPage: 1,
                currentPageOrders: 1,
                lastPage: 0,
                lastPageOrders: 0,
                pagination: {},
                paginationOrders: {},
                currentUser: this.$store.state.user,
                headers: [
                    {
                        text: 'Id Meal',
                        align: 'left',
                        sortable: false,
                        value: 'id'
                    },
                    {text: 'State Invoice', value: 'state'},
                    {text: 'State Meal', value: 'state'},
                    {text: 'Table Number', value: 'table'},
                    {text: 'Responsible Waiter', value: 'state'},
                ],
                headersOrders: [
                    {
                        text: 'Id Order',
                        align: 'left',
                        sortable: false,
                        value: 'id'
                    },
                    {text: 'State Order', value: 'state'},
                    {text: 'Order Item', value: 'order'},
                ]
            }
        },
        methods: {
            fetchMeals: function () {
                this.loading = true;
                axios.get("api/meals/state/active_terminated")
                    .then(response => {
                        this.loading = false;
                        this.mealsActiveAndTerminated = response.data.data;
                        this.totalMeals = response.data.data.total;
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
            },
            makePagination(page) {
                this.loading = true;
                axios.get("api/meals/state/active_terminated?page=" + page)
                    .then(response => {
                        this.loading = false;
                        this.mealsActiveAndTerminated = response.data.data;
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
            },
        },
        watch: {},
        created() {
            this.fetchMeals();
        }
    }
</script>

<style scoped>

</style>