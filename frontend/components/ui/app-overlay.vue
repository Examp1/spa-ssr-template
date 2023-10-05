<template>
    <div class="modal-overlay" @mousedown.self="closeModal">
        <div class="modal-content" @click.stop>
            <div class="close" @mousedown.self="closeModal">&times;</div>
            <slot />
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
    name: 'ModalOverlay',
    methods: {
        ...mapActions({
            setFormData: 'dinamic_form/setFormData',
            openForm: 'modal/openForm',
        }),
        closeModal() {
            this.openForm(false)
            this.$emit('close')
        },
    },
}
</script>

<style lang="scss" scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}
.modal-content {
    position: relative;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
}
.close {
    cursor: pointer;
    position: absolute;
    top: 20px;
    right: 20px;
    font-size: 35px;
    line-height: 20px;
}
</style>
