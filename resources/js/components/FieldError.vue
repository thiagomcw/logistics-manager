<template>
    <div class="field-error">
        {{ getError() }}
    </div>
</template>

<script>
export default {
    name: 'FieldErrors',

    props: {
        name: String,
        errors: Object
    },

    methods: {
        getError() {
            const value = this.errors[this.name];

            return value && value.length > 0 ? value[0] : '';
        },
    }
}
</script>

<style lang="scss" scoped>
.field-error {
    font-size: 11px;
    color: red;
}
</style>
