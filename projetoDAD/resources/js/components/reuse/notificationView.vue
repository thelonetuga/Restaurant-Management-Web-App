<template>
	<v-list fixed two-line>
		<template v-for="(item, index) in items">
			<v-divider
							:inset="true"
							:key="index"
			></v-divider>
			<v-list-tile
							:key="item.subtitle"
							avatar
							@click=""
			>
				<v-list-tile-avatar v-if="item.icon">
					<v-icon :class="[item.iconClass]">{{ item.icon }}</v-icon>
				</v-list-tile-avatar>
				<v-list-tile-avatar v-else>
					<img :src="'/storage/profiles/'+item.avatar">
				</v-list-tile-avatar>
				<v-list-tile-content>
					<v-list-tile-title v-html="item.title"></v-list-tile-title>
					<v-list-tile-sub-title v-html="item.subtitle"></v-list-tile-sub-title>
				</v-list-tile-content>
				<v-list-tile-action>
						<v-btn flat small color="primary" @click="takeMethere(item.actionRoute)">See</v-btn>
					  <v-btn flat small color="error" @click="clean(index)">Clean</v-btn>
				</v-list-tile-action>
			</v-list-tile>
		</template>
	</v-list>
</template>

<script>
    export default {
        props: ['arrayNotificacoes'],
        data: () => ({
            items: [],
		        layout:{
                header: 'Notifications',
				        divider: true,
				        inset: true,
		        }
        }),
		    methods:{
            takeMethere: function (rota) {
                console.log(rota);
		            this.$router.push(""+rota);
            },
				    clean(index){
                this.items.splice(index,1);
                this.$emit('clean', -1);
				    }
		    },
        watch: {
            arrayNotificacoes: function (newVal) {
                this.$toasted.show('New Notification Arrived Check!');
                this.items.push(newVal);
                console.log(this.items);
                this.isOrderUpdated = true;
                return this.items.push();
            },
        }
    }
</script>
<style scoped>

</style>