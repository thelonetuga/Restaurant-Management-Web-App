<template>
	<v-layout row wrap>
		<v-flex xs12 sm6 md4>
			<v-menu
							ref="menu"
							:close-on-content-click="false"
							v-model="menu"
							:nudge-right="40"
							:return-value.sync="date"
							lazy
							transition="scale-transition"
							offset-y
							full-width
							min-width="290px"
			>
				<v-text-field
								slot="activator"
								v-model="date"
								prepend-icon="event"
								readonly
				></v-text-field>
				<v-date-picker v-model="date" no-title scrollable>
					<v-spacer></v-spacer>
					<v-btn flat color="primary" @click="menu = false">Cancel</v-btn>
					<v-btn flat color="primary" @click="getDate(date)">OK</v-btn>
				</v-date-picker>
			</v-menu>
		</v-flex>
	</v-layout>
</template>
<script>
    export default {
        data: () => ({
            date: new Date().toISOString().substr(0, 10),
            menu: false,
        }),
		    methods:{
            getDate: function(){
                this.menu = false;
                this.$emit('send_date', this.date);
            },
		    },
    }
</script>