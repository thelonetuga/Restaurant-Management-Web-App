<template>
	<v-container >
			<v-layout align-start justify-center row fill-height>
			<v-flex xs6>
				<v-select
								:items="arrayFill"
								label="Choose a Filter"
								outline
								v-model="filter"
								required
								@input="fillChart"
				></v-select>
			</v-flex>
		</v-layout>
		<ve-histogram class="chart" :data="chartData" resizeable :settings="chartSettings" :toolbox="toolbox" :data-zoom="dataZoom"></ve-histogram>
	</v-container>
</template>
<script>
    export default {
        data() {
            this.chartSettings = {
                legendName: {
                    'cost': 'costmoney'
                }
            }
            return {
                chartData: {
                    columns: ['cook', 'orders'],
                    rows: [],
                },
                dataZoom: [
                    {
                        type: 'slider',
                        start: 0,
                        end: 50
                    }
                ],
		            arrayFill:[
		                'meal',
				            'cook',
				            'waiter',
		            ],
		            filter: '',
                toolbox:{
                    feature: {
                        magicType: {type: ['line', 'bar']},
                        saveAsImage: {}
                    }
                },
		            dates:[],
		            values:[],
                date: '',
                cookId: 37,
                averageCook: 0,
                menu: false,
                modal: false,
            }
        },
        methods: {
            fillChart(){
                console.log(this.filter);
                if (this.filter === 'waiter'){
                    this.fetchOrdersByWaiter();
                } else if (this.filter === 'meals'){
                    this.fetchMealsByWaiter();
                } else{
                    this.fetchOrdersByCook();
                }
            },
            fetchOrdersByCook: function () {
                let vm = this;
                console.log(this.date);
                axios.get("api/cook/orders/average/day")
                    .then(function (response) {
                        vm.chartData.rows = [];
                        for (var i = 0; i < response.data[1].length; i++) {
                            vm.chartData.rows.push({cook: response.data[1][i].resp, orders: response.data[1][i].total/response.data[0]});
                        }
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            fetchOrdersByWaiter: function () {
                let vm = this;
                axios.get("api/waiter/orders/average/day")
                    .then(function (response) {
                        vm.chartData.rows = [];
                        for (var i = 0; i < response.data[1].length; i++) {
                            vm.chartData.rows.push({cook: response.data[1][i].resp, orders: response.data[1][i].total/response.data[0]});
                        }
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            fetchMealsByWaiter: function () {
                let vm = this;
                axios.get("api/waiter/orders/average/day")
                    .then(function (response) {
                        vm.chartData.rows = [];
                        for (var i = 0; i < response.data[1].length; i++) {
                            vm.chartData.rows.push({cook: response.data[1][i].resp, orders: response.data[1][i].total/response.data[0]});
                        }
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
		    created(){
            this.fetchOrdersByCook();
		    }
    }
</script>
<style>
	.chart{
		background-color: white;
	}
</style>
