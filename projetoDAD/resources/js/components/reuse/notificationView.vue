<template>
	<v-list two-line>
		<template v-for="(item, index) in items">
			<v-subheader
							v-if="item.header"
							:key="item.header"
			>
				{{ item.header }}
			</v-subheader>

			<v-divider
							v-else-if="item.divider"
							:inset="item.inset"
							:key="index"
			></v-divider>

			<v-list-tile
							v-else
							:key="item.title"
							avatar
							@click=""
			>
				<v-list-tile-avatar>
					<img :src="'/storage/profiles/'+item.avatar">
				</v-list-tile-avatar>
				<v-list-tile-content>
					<v-list-tile-title v-html="item.title"></v-list-tile-title>
					<v-list-tile-sub-title v-html="item.subtitle"></v-list-tile-sub-title>
				</v-list-tile-content>
			</v-list-tile>
		</template>
	</v-list>
</template>

<script>
    export default {
        props: ['arrayNotificacoes'],
        data: () => ({
						items: [{ header: 'Today' }],
        }),
		    watch:{
            arrayNotificacoes: function (newVal) {
                console.log("View:", newVal);
                this.items.push(newVal);
		            return this.items.push( { divider: true, inset: true });
            }
		    }
    }
</script>

<style scoped>

</style>