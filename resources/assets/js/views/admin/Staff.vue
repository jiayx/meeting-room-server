<template>
    <div class="layout">
        <left-nav :navShow="navShow"></left-nav>

        <div class="content" :class="{'nav-hide': !navShow}">
            <mu-appbar :title="title" class="title-bar">
                <mu-icon-button icon="menu" slot="left" @click="navToggle"/>
            </mu-appbar>
            <mu-divider/>

            <div class="content-wrapper">
                <div class="staff">
                    <h2>上传员工信息</h2>
                    <mu-raised-button class="demo-raised-button" type="file" label="选择文件" icon="folder" primary>
                        <input type="file" ref="uploader" class="file-button" @change="selectFile">
                    </mu-raised-button>

                    <div class="selected">已选择：{{ file.name }}</div>

                    <div>
                        <mu-raised-button label="上传" @click="upload"/>
                    </div>

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

    export default {
        data() {
            return {
                title: '员工信息',
                open: true,
                docked: false,
                navShow: true,
                file: {},
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

            selectFile() {
                let inputDOM = this.$refs.uploader
                console.log(inputDOM.files[0])
                this.file = inputDOM.files[0]
            },

            upload() {
                if (!this.file.name) {
                    this.$refs.toast.show('请选择文件')
                    return
                }

                let formData = new FormData();
                formData.append('file', this.file)

                axios.post('/admin/staff/upload', formData, {
                    headers: {
                        'Content-Type': 'multiple/form-data',
                    }
                }).then(response => {
                    this.$refs.toast.show(response.message)
                }).catch(error => {
                    console.log(error)
                    this.$refs.toast.show('上传出错')
                })
            },
        }
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

    .file-button{
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        opacity: 0;
    }

    .selected {
        margin: 20px 0;
    }
</style>
