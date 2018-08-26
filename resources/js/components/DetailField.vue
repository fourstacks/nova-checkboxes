<template>
    <panel-item :field="field">
        <ul slot="value" class="list-reset">
            <component
                v-for="option in optionList"
                :key="option.key"
                :is="getItemType(option)"
                :option="option"
            ></component>
        </ul>
    </panel-item>
</template>

<script>
    import CheckboxDisplay from '../mixins/CheckboxDisplay'

    export default {

        mixins: [ CheckboxDisplay ],

        props: ['resource', 'resourceName', 'resourceId', 'field'],

        computed: {
            optionList() {
                if (this.field.display_unchecked_on_detail) {
                    return this.getAllOptions();
                }
                return this.getCheckedOptions();
            }
        }

    }
</script>
