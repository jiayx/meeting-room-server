<template>
    <div>
        <left-nav :navShow="navShow"></left-nav>

        <div class="content" :class="{'nav-hide': !navShow}">
            <mu-appbar :title="title" class="title-bar">
                <mu-icon-button icon="menu" slot="left" @click="navToggle"/>
            </mu-appbar>

            <div class="content-wrapper">
                <div style="display: inline-block; vertical-align:middle;">
                    <mu-date-picker v-model="start" label="请选择开始日期" container="inline" mode="landscape" hintText="请选择开始日期" autoOk/>
                </div>
                <div style="display: inline-block; vertical-align:middle;">
                    <mu-date-picker v-model="end" label="请选择结束日期" container="inline" mode="landscape" hintText="请选择结束日期" autoOk/>
                </div>
                <div class="submit-btn">
                    <mu-raised-button @click="exportData" label="导出" class="demo-raised-button" primary/>
                </div>
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
                title: '导出',
                open: true,
                docked: false,
                navShow: true,

                start: '',
                end: '',
            }
        },

        components: {
            leftNav: Nav,
            toast: Toast,
        },

        methods: {
            navToggle() {
                this.navShow = !this.navShow
            },

            exportData() {
                console.log(2121);
                const form = document.createElement("form");
                form.setAttribute('method', 'post');
                form.setAttribute('action', '/admin/bookings/export');
                form.setAttribute('target', '_blank');

                form.setAttribute('style', 'display: none;');

                const fieldStart = document.createElement('input');
                fieldStart.setAttribute('name', 'start');
                fieldStart.setAttribute('value', this.start);

                const fieldEnd = document.createElement('input');
                fieldEnd.setAttribute('name', 'end');
                fieldEnd.setAttribute('value', this.end);

                form.appendChild(fieldStart);
                form.appendChild(fieldEnd);
                document.body.appendChild(form);
                form.submit();
            }
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