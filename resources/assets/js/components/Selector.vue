<template>
    <div class="selector" :class="{inline: inline}">
        <div style="vertical-align: middle;">
            <div style="display: inline-block; vertical-align:middle;">
                <mu-date-picker v-model="date" @input="dateChange" label="请选择日期" container="inline" mode="landscape" hintText="请选择日期" autoOk/>
            </div>
            <div style="display: inline-block; vertical-align:middle;">
                <mu-select-field v-model="locationId" @input="locationChange" :labelFocusClass="['label-foucs']" label="请选择楼栋">
                    <mu-menu-item v-for="location, index in locations" :key="index" :value="location.id" :title="location.name" />
                </mu-select-field>
            </div>
        </div>

        <toast ref="toast"></toast>
    </div>
</template>

<script>
    import fecha from 'fecha'
    import Toast from './Toast.vue'

    export default {
        props: {
            inline: {
                type: Boolean,
                default: true,
            }
        },

        data() {
            return {
                locations: [],
                date: fecha.format(new Date(), 'YYYY-MM-DD'),
                locationId: 0,
            }
        },

        components: {
            toast: Toast,
        },

        computed: {
            propLocations() {
                return this.locations
            }
        },

        watch: {
            locationId(val) {
                console.log(val)
            }
        },

        methods: {
            loadLocations() {
                axios.get('/api/locations').then(response => {
                    if (response.code !== '200') {
                        this.$refs.toast.show(response.message)
                    } else {
                        this.locations = response.data
                        this.locationId = 1
                    }

                }).catch(error => {
                    console.log(error)
                    this.$refs.toast.show('获取楼栋信息出错')
                })
            },

            dateChange(value) {
                console.log(value)
                this.$emit('date-change', value)
            },

            locationChange(value) {
                console.log(value)
                this.$emit('location-change', value)
            }
        },

        created() {
            this.loadLocations()
        }
    }
</script>

<style scoped>
    .inline {
        display: inline-block;
    }
</style>