<template>
    <div>
        <section>
            <v-parallax src="/storage/icons/background.jpg" height="500">
            <v-layout
                column
                align-center
                justify-center
                class="white--text"
            >
                <img src="/storage/icons/vuetify.png" alt="Vuetify.js" height="200">
                <h1 class="font-weight-bold white--text mb-2 display-2 text-xs-center">OLD BUT GOLD</h1>
                <div class="font-weight-bold white--text subheading mb-3 text-xs-center">Powered by OLD BUT GOLD</div>
                <v-btn
                class="blue lighten-2 mt-5"
                dark
                large
                href="/#/items"
                >
                Let's see the dishes and drinks!
                </v-btn>
            </v-layout>
            </v-parallax>
    </section>
        <section>
            <v-layout
            column
            wrap
            class="my-5"
            align-center
            >
            <v-flex xs12 sm4 class="my-3">
                <div class="text-xs-center">
                <h2 class="headline">The Best Place To Eat Delicious Food</h2>
                <span class="subheading">
                    The Gourmet Restaurant
                </span>
                </div>
            </v-flex>
            <v-flex xs12>
                <v-container grid-list-xl>
                <v-layout row wrap align-center>
                    <v-flex xs12 md4>
                    <v-card class="elevation-0 transparent">
                        <v-card-text class="text-xs-center">
                        <v-icon x-large class="blue--text text--lighten-2">color_lens</v-icon>
                        </v-card-text>
                        <v-card-title primary-title class="layout justify-center">
                        <div class="headline text-xs-center">Joao Marques</div>
                        </v-card-title>
                        <v-card-text>
                            Engenharia Informática Nº 2161647
                        </v-card-text>
                    </v-card>
                    </v-flex>
                    <v-flex xs12 md4>
                    <v-card class="elevation-0 transparent">
                        <v-card-text class="text-xs-center">
                        <v-icon x-large class="blue--text text--lighten-2">flash_on</v-icon>
                        </v-card-text>
                        <v-card-title primary-title class="layout justify-center">
                        <div class="headline">Vitor Guerreiro</div>
                        </v-card-title>
                        <v-card-text>
                        Engenharia Informática Nº 2161346
                        </v-card-text>
                    </v-card>
                    </v-flex>
                    <v-flex xs12 md4>
                    <v-card class="elevation-0 transparent">
                        <v-card-text class="text-xs-center">
                        <v-icon x-large class="blue--text text--lighten-2">build</v-icon>
                        </v-card-text>
                        <v-card-title primary-title class="layout justify-center">
                        <div class="headline text-xs-center">Vlad Adamovych</div>
                        </v-card-title>
                        <v-card-text>
                        Engenharia Informática Nº 2152158
                        </v-card-text>
                    </v-card>
                    </v-flex>
                </v-layout>
                </v-container>
            </v-flex>
            </v-layout>
        </section>
    </div>
</template>
<script>
export default {
  data: () => ({

  }),
  mounted() {
    this.$store.commit('loadTokenAndUserFromSession');
  }
};
</script>
