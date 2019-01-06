<template>
	<v-container>
		<v-layout row>
			<v-flex xs12 sm6 offset-sm3>
				<v-card>
					<v-card-text>
						<v-container>
							<form @submit.prevent="resetPassword">
								<v-layout row>
									<v-flex xs12>
										<v-text-field
														name="email"
														label="Mail"
														id="email"
														v-model="passConfirmation.email"
														type="email"
														:rules="emailRules"
														required></v-text-field>
									</v-flex>
								</v-layout>
								<v-layout row>
									<v-flex xs12>
										<v-text-field
														name="password"
														label="Password"
														id="password"
														v-model="passConfirmation.password"
														type="password"
														:rules="[rules.required, rules.min]"
														required></v-text-field>
									</v-flex>
								</v-layout>
								<v-layout row>
									<v-flex xs12>
										<v-text-field
														name="confirmPassword"
														label="Confirm Password"
														id="confirmPassword"
														v-model="passConfirmation.password_confirmation"
														type="password"
														:rules="[comparePasswords]"></v-text-field>
									</v-flex>
								</v-layout>
								<v-layout row>
									<v-flex xs12>
										<v-btn type="submit">Confirm Password</v-btn>
									</v-flex>
								</v-layout>
							</form>
						</v-container>
					</v-card-text>
				</v-card>
			</v-flex>
		</v-layout>
	</v-container>
</template>
<script>
    export default {
        data () {
            return {
                emailRules: [
                    (v) => !!v || 'E-mail is required',
                    (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
                ],
                rules: {
                    required: value => !!value || 'Required.',
                    min: v => v.length >= 8 || 'Min 8 characters',
                    emailMatch: () => ('The email and password you entered don\'t match')
                },
                passConfirmation:{
                    email: '',
                    password: '',
                    token: this.$route.query.test,
		                password_confirmation: ''
                },
            }
        },
        computed: {
            comparePasswords () {
                return this.passConfirmation.password !== this.passConfirmation.password_confirmation ? 'Passwords do not match' : ''
            },
            user () {
                return this.$store.getters.user
            }
        },
        watch: {
            user (value) {
                if (value !== null && value !== undefined) {
                    this.$router.push('/')
                }
            }
        },
        methods: {
            resetPassword(){
                axios
                    .post("api/password/reset", this.passConfirmation)
                    .then(response => {
                        console.log(response.data);
                        console.log('Reset Success');
                        vm.$toasted.show('Reset Success');
                        this.$router.push('/logout');
                    })
                    .catch(error => {

                    });
            }
        },
		    mounted(){
            console.log(this.$route.query.test);
            axios
                .get("api/password/find/"+this.$route.query.test)
                .then(response => {
                    console.log(response.data);
                    console.log('Premission Granted');
                })
                .catch(error => {

                });
		    }
    }
</script>

<style scoped>

</style>