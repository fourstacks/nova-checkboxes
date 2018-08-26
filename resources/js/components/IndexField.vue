<template>
    <ul class="list-reset py-2">
        <component
            v-for="option in optionList"
            :is="getItemType(option)"
            :option="option"
        ></component>
    </ul>
</template>

<script>
    import CheckedItem from './list-items/CheckedItem.vue';
    import UncheckedItem from './list-items/UncheckedItem.vue';

    export default {

        components:{
            CheckedItem,
            UncheckedItem
        },

        props: ['resourceName', 'field'],

        data: () => ({
            value: [],
        }),

        computed: {
            optionList() {
                if (this.field.display_unchecked) {
                    return this.field.options
                        .map(option => {
                            return {
                                'status': this.value.includes(option.value),
                                'key': option.value,
                                'label': option.label
                            }
                        })
                        .sort((x, y) => y.status - x.status);
                }
                return this.value
                    .map(optionValue => {
                        return {
                            'status': this.value.includes(optionValue),
                            'key': optionValue,
                            'label': this.field.options.find(o => o.value === optionValue)
                        }
                    });
            }
        },

        methods: {
            getItemType(option) {
                return (option.status)
                    ? 'checked-item'
                    : 'unchecked-item'
            }
        },

        mounted() {
            this.value = this.field.value || [];
        }
    }
</script>
