<template>
    <ul class="list-reset pt-2">
        <component
            v-for="option in optionList"
            :key="option.key"
            :is="getItemType(option)"
            :option="option"
        ></component>
    </ul>
</template>

<script>
    import CheckboxDisplay from '../mixins/CheckboxDisplay'

    export default {

        mixins: [ CheckboxDisplay ],

        props: ['resourceName', 'field'],

        computed: {
            optionList() {
                if (this.field.display_unchecked_on_index) {
                    return this.getAllOptions();
                }
                return this.getCheckedOptions();
            }
        }
    }
</script>
