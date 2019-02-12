<template>
    <div class="layout">
        <left-nav :navShow="navShow"></left-nav>

        <div class="content" :class="{'nav-hide': !navShow}">
            <mu-appbar :title="title" class="title-bar">
                <mu-icon-button icon="menu" slot="left" @click="navToggle"/>
            </mu-appbar>
            <mu-divider/>
            <div class="content-wrapper">
                <selector @date-change="selectDate" @location-change="selectLocation"></selector>
                <div class="submit-btn">
                    <mu-raised-button @click="query" label="查询" class="demo-raised-button" primary/>
                </div>

                <mu-table :showCheckbox="false" fixedHeader ref="table">
                    <mu-thead>
                        <mu-tr>
                            <mu-th>时间段</mu-th>
                            <mu-th v-for="meetingRoom in meetingRooms" :key="meetingRoom.id">{{ meetingRoom.name }}</mu-th>
                        </mu-tr>
                    </mu-thead>
                    <mu-tbody>
                        <mu-tr v-for="time in times" :key="time.id">
                            <mu-td>
                                <p>{{ time.start }}</p>
                                <p>{{ time.end }}</p>
                            </mu-td>

                            <mu-td v-for="booking in time.bookings" :key="booking.id">
                                <div v-if="booking.subject">
                                    <p>会议主题：{{ booking.subject }}</p>
                                    <p>预订人：{{ booking.user.name }}</p>
                                    <p>万信号：{{ booking.user.wanxin }}</p>
                                    <p>手机号：{{ booking.user.mobile }}</p>
                                    <p>预定时间：{{ booking.created_at.substr(0, booking.created_at.length - 3) }}</p>
                                </div>
                            </mu-td>
                        </mu-tr>
                    </mu-tbody>
                </mu-table>
            </div>
        </div>

        <toast ref="toast"></toast>
    </div>
</template>
<script>
    import fecha from 'fecha'
    import Nav from '../../components/Nav.vue'
    import Toast from '../../components/Toast.vue'
    import Selector from '../../components/Selector.vue'

    export default {
        data() {
            return {
                title: '预订情况',
                open: true,
                docked: false,
                navShow: true,
                date: fecha.format(new Date(), 'YYYY-MM-DD'),
                meetingRooms: [],
                times: [],
                locationId: 0,
            }
        },

        components: {
            leftNav: Nav,
            toast: Toast,
            selector: Selector,
        },

        methods: {
            navToggle() {
                this.navShow = !this.navShow
            },

            loadMeetingRooms() {
                axios.get('/admin/meeting_rooms', {
                    params: {
                        date: this.date,
                        location_id: this.locationId,
                    },
                }).then(response => {
                    console.log(response)
                    if (response.code !== '200') {
                        this.$refs.toast.show(response.message)
                    } else {
                        this.meetingRooms = response.data.meeting_rooms
                        this.times = response.data.times
                    }
                }).catch(error => {
                    console.log(error)
                    this.$refs.toast.show('获取会议室信息出错')

                })
            },

            selectDate(date) {
                this.date = date

            },
            selectLocation(locationId) {
                this.locationId = locationId
            },

            query() {
                this.loadMeetingRooms()
            }

        },

        created() {
            // this.loadMeetingRooms()
        }


    }
</script>
<style scoped>
    @import 'https://fonts.googleapis.com/icon?family=Material+Icons';

    .layout {
    }

    .content {
        overflow: hidden;
        padding-left: 256px;
        min-height: 500px;
        transition: all .45s;
    }

    .title-bar {
        position: fixed;
        opacity: .87;
        width: 100%;
    }

    .nav-hide {
        padding-left: 0;
    }

    .content-wrapper {
        padding: 48px 72px;
        margin-top: 64px;
    }

    .submit-btn {
        display: inline-block;
        vertical-align: middle;
        margin-left: 20px;
    }
    .used {
        background: #ef5350;
        color: #fff;
        padding: 1px 5px;
    }

    .footer {
        padding: 10px 0;
        text-align: center;
    }
</style>