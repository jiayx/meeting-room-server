<template>
    <div>
        <left-nav :navShow="navShow"></left-nav>

        <div class="content" :class="{'nav-hide': !navShow}">
            <mu-appbar :title="title" class="title-bar">
                <mu-icon-button icon="menu" slot="left" @click="navToggle"/>
            </mu-appbar>

            <div class="content-wrapper">
                <h2>万达会议室预订管理后台</h2>
                <p>为获取良好体验，荐使用 Chrome 浏览器访问，或者 Firefox、Safari、IE 10+</p>
            </div>
        </div>
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
                title: '首页',
                open: true,
                docked: false,
                navShow: true,
            }
        },

        components: {
            leftNav: Nav,
        },

        methods: {
            navToggle() {
                this.navShow = !this.navShow
            },
        },
    }
</script>
<style scoped>
    @import 'https://fonts.googleapis.com/icon?family=Material+Icons';

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

</style>