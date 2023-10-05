<template>
    <div class="formField">
        <ValidationProvider
            v-slot="{ errors }"
            :rules="{ required: { allowFalse: false } }"
        >
            <div
                :id="`${propsData.name}${type}`"
                class="checkbox"
                :class="{ active: value, error: errors[0] }"
                @click="change"
                v-html="propsData.title"
            ></div>
            <input v-model="value" type="checkbox" />
            <span v-if="errors[0]" class="error">{{ errors[0] }}</span>
        </ValidationProvider>
    </div>
</template>

<script>
export default {
    props: {
        validationRules: {
            type: Object,
            default: () => ({}),
        },
        type: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            value: false,
        }
    },
    methods: {
        change() {
            this.value = !this.value
            this.$emit('input', this.value)
        },
    },
}
</script>
