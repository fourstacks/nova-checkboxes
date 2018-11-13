<template>
    <panel-item :field="field">
        <div class="flex" slot="value">
            <ul :class="['list-reset', 'items-top', width]" v-for="options in chunkedOptions">
                <component
                    v-for="option in options"
                    :key="option.key"
                    :is="getItemType(option)"
                    :option="option"
                ></component>
            </ul>
        </div>
    </panel-item>
</template>

<script>
import CheckboxDisplay from '../mixins/CheckboxDisplay';

export default {
    mixins: [CheckboxDisplay],

    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data() {
        return {
            columns: this.field.columns || 1
        };
    },

    computed: {
        width() {
            return this.columns == 1 ? 'w-full' : `w-1/${this.columns}`;
        },
        chunkedOptions() {
            return this.optionList.reduce((result, option, index) => {
                index %= this.columns;
                result[index] || (result[index] = []);

                return result[index].push(option), result;
            }, []);
        },
        optionList() {
            if (this.field.display_unchecked_on_detail) {
                return this.getAllOptions();
            }
            return this.getCheckedOptions();
        }
    }
};
</script>
