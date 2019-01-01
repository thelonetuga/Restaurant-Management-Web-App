<template>
	<div mr-3 v-if="currentUser.type === 'cashier'">
		<br>
		<v-toolbar>
			<v-toolbar-title>Detail Invoices</v-toolbar-title>
			<v-divider class="mx-2" inset vertical></v-divider>
		</v-toolbar>
		<v-data-table :headers="headers" hide-actions :items="pendingInvoices" class="elevation-1">
			<template slot="items" slot-scope="props">
				<td class="text-left">{{ props.item.state }}</td>
				<td class="text-left" v-if="props.item.state === 'paid' ">
					<v-btn small color="primary" class="text-left" @click="createPDF(props.item)">Get PDF</v-btn>
				</td>
				<td class="text-left" v-else>
					------
				</td>
				<td class="text-left">{{ props.item.date }}</td>
				<td class="text-left">{{ props.item.meals.table_number }}</td>
				<td class="text-left">{{ props.item.waiter_name }}</td>
				<td class="text-left">{{ props.item.meals.total_price_preview }} €</td>
				<td class="text-left">
					<v-menu
									transition="slide-y-transition"
									bottom
					>
						<v-btn slot="activator" color="primary" dark>See items</v-btn>
						<v-container
										id="scroll-target"
										style="max-height: 400px"
										class="scroll-y"
						>
							<v-layout
											v-scroll:#scroll-target="onScroll"
											column
											align-center
											justify-center
											style="height: 1000px"
							>
								<v-list >
									<v-list-tile
													v-for="item in props.item.item"
													:key="item.name"
													no-action
									>
									</v-list-tile>
								</v-list>
								<v-list three-line>
									<template v-for="(item, index) in props.item.item">
										<v-list-tile
														:key="item.name"
														avatar
														ripple
														@click="toggle(index)"
										>
											<v-list-tile-content>
												<v-list-tile-title>{{ item.name }}</v-list-tile-title>
												<v-list-tile-sub-title class="text--primary"> Type: {{ item.type }}</v-list-tile-sub-title>
												<v-list-tile-sub-title>Price: {{ item.price }}</v-list-tile-sub-title>
											</v-list-tile-content>
											<v-list-tile-content>
												<v-list-tile-sub-title>Quantity: {{item.pivot.quantity }}</v-list-tile-sub-title>
												<v-list-tile-sub-title>Unit Price: {{item.pivot.unit_price }}</v-list-tile-sub-title>
												<v-list-tile-sub-title>Sub Total: {{item.pivot.sub_total_price }}</v-list-tile-sub-title>
											</v-list-tile-content>
										</v-list-tile>
										<v-divider
														v-if="index + 1 < props.item.item.length"
														:key="index"
										></v-divider>
									</template>
								</v-list>
							</v-layout>
						</v-container>
					</v-menu>
				</td>
			</template>
		</v-data-table>
		<div class="text-xs-center">
			<v-pagination v-model="currentPage" :length="lastPage" @input="makePagination" circle></v-pagination>
		</div>
	</div>
	<div mr-3 v-else-if="currentUser.type === 'manager'">
		<br>
		<v-toolbar>
			<v-toolbar-title>Detail Invoices</v-toolbar-title>
			<v-divider class="mx-2" inset vertical></v-divider>
			<v-spacer></v-spacer>
			<v-btn flat small color="warning" class="text-left" @click="fetchPendingInvoices()">All</v-btn>
			<v-btn flat small color="success" class="text-left" @click="fetchInvoicesByState('pending')">Pending</v-btn>
			<v-btn flat small color="error" class="text-left" @click="fetchInvoicesByState('paid')">Paid</v-btn>
			<v-btn flat small color="info" class="text-left" @click="fetchInvoicesByState('not paid')">Not Paid</v-btn>
			<date_filter @send_date="filterInvoiceByDate"></date_filter>
			<v-spacer></v-spacer>
		</v-toolbar>
		<v-data-table :headers="headers" hide-actions :items="pendingInvoices" class="elevation-1">
			<template slot="items" slot-scope="props">
				<td class="text-left">{{ props.item.state }}</td>
				<td class="text-left" v-if="props.item.state === 'paid' ">
					<v-btn small color="primary" class="text-left" @click="createPDF(props.item)">Get PDF</v-btn>
				</td>
				<td class="text-left" v-else>
					------
				</td>
				<td class="text-left">{{ props.item.date }}</td>
				<td class="text-left">{{ props.item.meals.table_number }}</td>
				<td class="text-left">{{ props.item.waiter_name }}</td>
				<td class="text-left">{{ props.item.meals.total_price_preview }} €</td>
				<td class="text-left">
					<v-menu
									transition="slide-y-transition"
									bottom
					>
						<v-btn slot="activator" color="primary" dark>See items</v-btn>
						<v-container
										id="scroll-target2"
										style="max-height: 400px"
										class="scroll-y"
						>
							<v-layout
											v-scroll:#scroll-target="onScroll"
											column
											align-center
											justify-center
											style="height: 1000px"
							>
								<v-list >
									<v-list-tile
													v-for="item in props.item.item"
													:key="item.name"
													no-action
									>
									</v-list-tile>
								</v-list>
								<v-list three-line>
									<template v-for="(item, index) in props.item.item">
										<v-list-tile
														:key="item.name"
														avatar
														ripple
														@click="toggle(index)"
										>
											<v-list-tile-content>
												<v-list-tile-title>{{ item.name }}</v-list-tile-title>
												<v-list-tile-sub-title class="text--primary"> Type: {{ item.type }}</v-list-tile-sub-title>
												<v-list-tile-sub-title>Price: {{ item.price }}</v-list-tile-sub-title>
											</v-list-tile-content>
											<v-list-tile-content>
												<v-list-tile-sub-title>Quantity: {{item.pivot.quantity }}</v-list-tile-sub-title>
												<v-list-tile-sub-title>Unit Price: {{item.pivot.unit_price }}</v-list-tile-sub-title>
												<v-list-tile-sub-title>Sub Total: {{item.pivot.sub_total_price }}</v-list-tile-sub-title>
											</v-list-tile-content>
										</v-list-tile>
										<v-divider
														v-if="index + 1 < props.item.item.length"
														:key="index"
										></v-divider>
									</template>
								</v-list>
							</v-layout>
						</v-container>
					</v-menu>
				</td>
			</template>
		</v-data-table>
		<div class="text-xs-center">
			<v-pagination v-model="currentPage" :length="lastPage" @input="makePagination" circle></v-pagination>
		</div>
	</div>
</template>
<script>
    import jsPDF from 'jspdf';
    import autoTable from 'jspdf-autotable';
    import datePicker from '../reuse/dateFilter'

    export default {
        data() {
            return {
                pendingInvoices: [],
                currentUser: this.$store.state.user,
                currentPage: 1,
                lastPage: 0,
                dialog: false,
                pagination: {},
                headers: [
                    {text: "State", value: "state"},
                    {text: "Get PDF", value: "pdf"},
                    {
                        text: "Date",
                        align: "left",
                        sortable: false,
                        value: "date"
                    },
                    {text: "Table Number", value: "table_number"},
                    {text: "Responsible Waiter", value: "responsible_waiter_id"},
                    {text: "Total Price (€)", value: "total_price_preview"},
                    {text: "Items Consumed ", value: "name"}
                ]
            };
        },
        methods: {
            onScroll (e) {
                this.offsetTop = e.target.scrollTop
            },
            filterInvoiceByDate: function(date){
                axios.get("api/invoice/filter/date/"+date)
                    .then(response => {
                        this.loading = false;
                        this.pendingInvoices = response.data.data;
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
            createPDF: function (invoice) {

                var columnsInvoice = [
                    {title: "ID", dataKey: "id"},
                    {title: "State", dataKey: "state"},
                    {title: "Date", dataKey: "date"},
                    {title: "Waiter", dataKey: "waiter_name"},
                    {title: "Total Price", dataKey: "total_price"},
                ];
                var rowsInvoice = [
		                {
		                    "id": invoice.id,
		                    "state": invoice.state,
		                    "date": invoice.date,
		                    "waiter_name": invoice.waiter_name,
		                    "total_price": invoice.meals.total_price_preview,
		                }
                ];

                var columnsItems = [
                    {title: "Type", dataKey: "type"},
                    {title: "Name", dataKey: "name"},
                    {title: "Price", dataKey: "price"},
                    {title: "Order State", dataKey: "state"},
                ];
                var rowsItems = [];
                invoice.item.forEach(function(element) {
                    let item = {
                        "type": element.type,
                        "name": element.name,
                        "price": element.price,
                        "state": invoice.order_state,
                    };
                    rowsItems.push(item);
                });
                var doc = new jsPDF('p', 'pt');
                var docItems = new jsPDF('p', 'pt');
                doc.autoTable(columnsInvoice, rowsInvoice, {
                    styles: {fillColor: [100, 255, 255]},
                    columnStyles: {
                        id: {fillColor: 255}
                    },
                    margin: {top: 60},
                    addPageContent: function(data) {
                        doc.text("Invoice", 40, 30);
                    }
                });

                docItems.autoTable(columnsItems, rowsItems, {
                    styles: {fillColor: [100, 255, 255]},
                    columnStyles: {
                        id: {fillColor: 255}
                    },
                    margin: {top: 60},
                    addPageContent: function(data) {
                        docItems.text("Items Consumed", 40, 30);
                    }
                });
                doc.save('Invoice'+ invoice.id +'.pdf');
                docItems.save('ItemsofInvoice'+invoice.id+'.pdf');
            },
            fetchPendingInvoices: function () {
                let vm = this;
                axios
                    .get("api/invoices/all")
                    .then(response => {
                        vm.pendingInvoices = response.data.data;
                        //console.log(response.data.data);
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            fetchInvoicesByState: function (state) {
                let vm = this;
                axios.get("api/invoices/state/" + state)
                    .then(response => {
                        vm.pendingInvoices = response.data.data;
                        //console.log(response.data.data);
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
                axios
                    .get("api/invoices/all?page=" + page)
                    .then(response => {
                        this.pendingInvoices = response.data.data;
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
            }
        },
        created() {
            this.fetchInvoicesByState('pending');
            this.makePagination();
        },
        components:{
            'date_filter': datePicker
        }

    };
</script>
