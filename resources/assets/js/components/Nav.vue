<template>
    <mu-drawer class="nav" :open="navShow">
        <mu-appbar title="会议室预订管理">
            <a href="/admin" class="white">会议室预订管理</a>
        </mu-appbar>
        <mu-divider/>
        <mu-list>
            <mu-list-item title="首页" href="/admin"/>
            <mu-list-item title="预订管理" toggleNested>
                <mu-list-item slot="nested" href="/admin/bookings" title="列表"></mu-list-item>
                <mu-list-item slot="nested" href="/admin/bookings/export" title="导出"></mu-list-item>
            </mu-list-item>
            <mu-list-item title="楼栋管理" toggleNested>
                <mu-list-item slot="nested" title="列表"></mu-list-item>
                <mu-list-item slot="nested" title="新增"></mu-list-item>
            </mu-list-item>
            <mu-list-item title="员工信息" href="/admin/staff">
            </mu-list-item>
        </mu-list>
    </mu-drawer>
</template>

<script>
    export default {
        props: {
            navShow: {
                type: Boolean
            }
        },

        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style>
    .nav {
        opacity: .87;
    }

    .white {
        color: white;
    }
</style>



