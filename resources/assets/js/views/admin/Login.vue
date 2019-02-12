<template>
    <div>
        <mu-paper class="login" :zDepth="1">
            <div class="title"><h1>登录</h1></div>
            <div class="form">
                <div>
                    <mu-text-field
                        label="用户名"
                        v-model="username"
                        :maxLength="maxLength.username"
                        :errorText="errorText.username"
                        @textOverflow="usernameOverflow"
                        hintText="请输入用户名"
                        labelFloat
                        fullWidth
                    />
                </div>
                <div>
                    <mu-text-field
                        label="密码"
                        v-model="password"
                        :maxLength="maxLength.password"
                        :errorText="errorText.password"
                        @textOverflow="passwordOverflow"
                        hintText="请输入密码"
                        type="password"
                        labelFloat
                        fullWidth
                    />
                </div>
                <div class="remember">
                    <mu-switch label="记住登录" v-model="remember" />
                </div>
                <div class="button">
                    <mu-raised-button @click="login" label="登录" type="submit" primary fullWidth />
                </div>
            </div>
        </mu-paper>
        <mu-toast v-if="toast" :message="message" />
    </div>

</template>

<script>
    export default {
        name: 'login',

        data() {
            return {
                toast: false,
                message: '',
                maxLength: {
                    username: 30,
                    password: 30,
                },
                errorText: {
                    username: '',
                    password: '',
                },
                username: 'admin',
                password: 'wanda123',
                remember: false,
            }
        },

        methods: {
            usernameOverflow (isOverflow) {
                this.errorText.username = isOverflow ? `用户名最长 ${this.maxLength.username} 个字符` : ''
            },
            passwordOverflow (isOverflow) {
                this.errorText.password = isOverflow ? `用户名最长 ${this.maxLength.password} 个字符` : ''
            },

            login() {
                if (this.username.length === 0 || this.password.length === 0) {
                    this.showToast('请输入用户名和密码')
                    return
                }
                if (this.username.length > 30) {
                    this.showToast('用户名长度不能大于 30')
                    return
                }

                if (this.password.length > 30) {
                    this.showToast('密码长度不能大于 30')
                    return
                }

                axios.post('/admin/login', {
                    username: this.username,
                    password: this.password,
                    remember: this.remember,
                }).then(response => {
                    console.log(response)
                    if (response.code !== '200') {
                        this.showToast(response.message)
                    } else {
                        if (response.data.location) {
                            window.location = response.data.location
                        }
                    }
                }).catch(error => {
                    console.log(error)
                    this.showToast('服务器错误')
                })
            },

            showToast(message) {
                if (this.toastTimer) {
                    clearTimeout(this.toastTimer)
                }
                this.toastTimer = setTimeout(() => {
                    this.toast = false
                }, 2000)

                this.message = message
                this.toast = true
            },

            hideToast() {
                this.toast = false
                if (this.toastTimer) {
                    clearTimeout(this.toastTimer)
                }
            }
        }
    }
</script>

<style>
    body {
        background: #f1f1f1;
        color: rgba(0, 0, 0, .87);
    }
</style>
<style scoped>

    .login {
        width: 500px;
        margin: 100px auto 10px;
        padding: 30px 100px;
        text-align: center;
    }
    .button {
        margin-top: 30px;
    }
    .remember {
        text-align: left;
    }
</style>
