<template>
	<v-content>
		<v-container fill-height>
			<v-layout justify-center align-center>
				<v-flex shrink>
					<v-text-field
									disabled
									name="last_shift_start"
									label="Start Shift"
									id="last_shift_start"
									type="last_shift_start"
									v-model="user.last_shift_start"
					>{{user.last_shift_start}}
					</v-text-field>
					<v-layout column v-if="isDisable">
						<v-btn
										v-on:click.prevent="shift('start')"
										color="success"
										v-if="user.shift_active===0"
										type="submit"
						>Start Shift
						</v-btn>
					</v-layout>
				</v-flex>
			<v-layout justify-center align-center>
				<v-flex shrink>
					<v-text-field
									disabled
									name="last_shift_end"
									label="End Shift"
									id="last_shift_end"
									type="last_shift_end"
									v-model="user.last_shift_end"
					>{{user.last_shift_end}}
					</v-text-field>
					<v-layout column v-if="isDisable">
						<v-btn
										v-on:click.prevent="shift('end')"
										color="error"
										v-if="user.shift_active===1"
										type="submit"
						>End Shift
						</v-btn>
					</v-layout>
				</v-flex>
			</v-layout>
			<v-flex shrink>
				<v-text-field disabled label="Time Elapsed" v-model="time">{{time}}</v-text-field>
			</v-flex>
			</v-layout>
		</v-container>
	</v-content>
</template>

<script>
    export default {
        data: () => ({
            drawer: null,
            isDisable: true,
            authenticated: false
        }),

        props: {
            source: String
        },
        created() {
        },
        methods: {
            shift(shift) {
                if (shift === "start") {
                    this.user.last_shift_start = new Date(Date.now())
                        .toISOString()
                        .replace("Z", " ")
                        .replace("T", " ")
                        .slice(0, 19);

                    this.user.shift_active = 1;
                }
                if (shift === "end") {
                    this.user.last_shift_end = new Date(Date.now())
                        .toISOString()
                        .replace("Z", " ")
                        .replace("T", " ")
                        .slice(0, 19);

                    this.user.shift_active = 0;
                }
                axios
                    .put("api/users/" + this.user.id, this.user)
                    .then(response => {
                        Object.assign(this.user, response.data.data);
                        this.$store.commit("setUser", this.user);
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error.response.data.message);
                    })
                    .then(function () {
                        // always executed
                    });
                this.isDisable = false;
            },
            timeElapsed() {
                let timepassed = Math.abs(
                    new Date(Date.now()) -
                    Date.parse( this.$store.state.user.shift_active === 1 ? this.user.last_shift_start : this.user.last_shift_end)
                );
                return this.parseMillisecondsIntoReadableTime(timepassed);
            },

            millisToMinutesAndSeconds(millis) {
                var minutes = Math.floor(millis / 60000);
                var seconds = ((millis % 60000) / 1000).toFixed(0);
                return seconds == 60
                    ? minutes + 1 + ":00"
                    : minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
            },

            parseMillisecondsIntoReadableTime(milliseconds) {
                //Get hours from milliseconds
                var hours = milliseconds / (1000 * 60 * 60);
                var absoluteHours = Math.floor(hours);
                var h = absoluteHours > 9 ? absoluteHours : "0" + absoluteHours;

                //Get remainder from hours and convert to minutes
                var minutes = (hours - absoluteHours) * 60;
                var absoluteMinutes = Math.floor(minutes);
                var m = absoluteMinutes > 9 ? absoluteMinutes : "0" + absoluteMinutes;

                //Get remainder from minutes and convert to seconds
                var seconds = (minutes - absoluteMinutes) * 60;
                var absoluteSeconds = Math.floor(seconds);
                var s = absoluteSeconds > 9 ? absoluteSeconds : "0" + absoluteSeconds;

                return h + ":" + m + ":" + s;
            }
        },
        computed: {
            user: function () {
                return this.$store.state.user;
            },
            time: function () {
                return this.timeElapsed();
            }
        }
    };
</script>
