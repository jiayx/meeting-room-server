<template>
    <mu-toast v-if="display" :message="message" />
</template>

<script>
    export default {
        data() {
            return {
                display: false,
                message: '',
            }
        },
        methods: {
            show(message) {
                if (this.toastTimer) {
                    clearTimeout(this.toastTimer)
                }
                this.toastTimer = setTimeout(() => {
                    this.display = false
                }, 2000)

                this.message = message
                this.display = true
            },

            hide() {
                if (this.toastTimer) {
                    clearTimeout(this.toastTimer)
                }
                this.display = false
            }
        }
    }
</script>
