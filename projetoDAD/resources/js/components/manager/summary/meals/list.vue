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
			<v-spacer></v-spacer>
			<v-btn flat small color="warning" class="text-left" @click="fetchMeals()">Reset</v-btn>
			<v-btn flat small color="success" class="text-left" @click="fetchMealsByState('active')">Active</v-btn>
			<v-btn flat small color="success" class="text-left" @click="fetchMealsByState('terminated')">Terminated</v-btn>
			<v-btn flat small color="error" class="text-left" @click="fetchMealsByState('paid')">Paid</v-btn>
			<v-btn flat small color="info" class="text-left" @click="fetchMealsByState('not paid')">Not Paid</v-btn>
			<date_filter @send_date="filterMealsByDate"></date_filter>
			<v-spacer></v-spacer>
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
					<td class="text-left" v-if="props.item.state === 'terminated'">
						<v-btn flat small color="warning" @click="changeState(props.item)">Not Paid</v-btn>
					</td>
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
	import datePicker from '../../../reuse/dateFilter'
    export default {
        props:['updateOrderTable','updateMealTable'],
        data() {
            return {
                mealsActiveAndTerminated: [],
		            date:'',
                totalMeals: 0,
                totalOrders: 0,
                loading: true,
                currentPage: 1,
                currentPageOrders: 1,
                lastPage: 0,
                lastPageOrders: 0,
		            url:'',
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
                    {text: "Change State", value: "name", sortable: false }
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
            filterMealsByDate: function(date){
                this.url= "api/meals/filter/date/"+date;
                axios.get("api/meals/filter/date/"+date)
                    .then(response => {
                        this.clearPagination();
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
		        clearPagination: function(){
                this.loading = false;
                this.mealsActiveAndTerminated =[];
                this.totalMeals = 0;
                this.currentPage = 0;
                this.lastPage = 0;
                this.pagination = {};
		        },
            fetchMeals: function () {
                this.loading = true;
                this.url= "api/meals/state/active_terminated";
                axios.get("api/meals/state/active_terminated")
                    .then(response => {
                        this.clearPagination();
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
            fetchMealsByState(state){
                this.loading = true;
	                this.url= "api/meals/state/"+state;
                axios.get("api/meals/state/"+state)
                    .then(response => {
                        this.clearPagination();
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
		        changeState(meal){
                axios
                    .patch("api/meal/"+meal.id+"/change/state/terminated")
                    .then(response => {
                        this.$socket.emit('invoice_changed', response.data.data);
                        this.$socket.emit('meal_changed', response.data.data);
                        console.log('Estado alterado para:',response.data.data.state);
                    })
                    .catch(function(error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function() {
                        // always executed
                    });
		        },
            makePagination(page) {
                this.loading = true;
                axios.get(this.url+"?page=" + page)
                    .then(response => {
                        this.clearPagination();
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
                setTimeout(function() {
                    this.loading = false;
                }, 10000);
            },
        },
        watch: {
            updateOrderTable: function (newVal) {
                this.$toasted.show('Order "' + newVal.changedOrder.id + 'has changed');
                this.$toasted.success('Table Meals updated');
		            this.fetchMeals();
            },
            updateMealTable: function (newVal) {
                this.$toasted.show('Meal "' + newVal.changedMeal.id + 'has changed');
                this.$toasted.success('Table Meals updated');
                this.fetchMeals();
            }
        },
        created() {
            this.fetchMeals();
        },
		    components:{
            'date_filter': datePicker
		    }
    }
</script>

<style scoped>

</style>