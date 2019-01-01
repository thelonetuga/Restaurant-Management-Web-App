<template>
	<div>
		<v-toolbar>
			<v-toolbar-title>Pending Invoices</v-toolbar-title>
			<v-divider class="mx-2" inset vertical></v-divider>
			<v-spacer></v-spacer>
		</v-toolbar>
		<v-data-table :headers="headers" :items="pendingInvoices" class="elevation-1">
			<template slot="items" slot-scope="props">
				<td class="text-left">{{ props.item.meals.table_number }}</td>
				<td class="text-left">{{ props.item.waiter_name }}</td>
				<td class="text-left">{{ props.item.meals.total_price_preview }} €</td>
				<td class="text-left">
					<v-btn flat small color="warning" @click="changeState(props.item, props.item.meals.id)">Not Paid</v-btn>
				</td>
			</template>
		</v-data-table>
		<br>
		<listAllInvoices></listAllInvoices>
	</div>
</template>
<script>
    import ListDetailInvoices from '../../../cashier/ListDetailInvoices';
    export default {
        props: ['updateInvoiceTable'],
        data() {
            return {
                dialog: false,
                headers: [
                    { text: "Table Number", value: "table_number" },
                    { text: "Responsible Waiter", value: "responsible_waiter_id" },
                    { text: "Total Price (€)", value: "total_price_preview" },
                    { text: "Change State", value: "name", sortable: false }
                ],
                pendingInvoices: [],
            };
        },
        methods: {
            fetchPendingInvoices: function() {
                let vm = this;
                axios
                    .get("api/invoices/pending")
                    .then(response => {
                        vm.pendingInvoices = response.data.data;
                        //console.log(response.data.data);
                    })
                    .catch(function(error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function() {
                        // always executed
                    });
            },
		        changeState(invoice, meal_id){
                console.log(invoice, meal_id);
                axios
                    .patch("api/invoice/"+invoice.id+"/meal/"+ meal_id+"/change/state/pending")
                    .then(response => {
                        this.$socket.emit('invoice_changed', response.data.data);
                        this.$socket.emit('meal_changed', response.data.data.meals);
                        console.log('Estado alterado para Invoice:',response.data.data);
                        console.log('Estado alterado para Meals:',response.data.data.meals);
                    })
                    .catch(function(error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function() {
                        // always executed
                    });
		        },
            close() {
                this.dialog = false;
                setTimeout(() => {
                    this.editedInvoices = Object.assign({}, this.defaultTable);
                    this.editedIndex = -1;
                }, 300);
            },
        },
        components: {
						'listAllInvoices': ListDetailInvoices,
        },
        computed: {},
        watch: {
            dialog(val) {
                val || this.close();
            },
            updateInvoiceTable: function (newVal) {
                this.$toasted.show('Invoice "' + newVal.changedInvoice.id + 'has changed');
                this.fetchPendingInvoices();
            }
        },
        created() {
            this.fetchPendingInvoices();
        }
    };
</script>
