import CheckedItem from '../components/list-items/CheckedItem.vue';
import UncheckedItem from '../components/list-items/UncheckedItem.vue';

export default {

    components:{
        CheckedItem,
        UncheckedItem
    },

    data: () => ({
        value: [],
    }),

    methods: {
        getItemType(option) {
            return (option.status)
                ? 'checked-item'
                : 'unchecked-item'
        },
        getAllOptions(){
            return this.field.options
                .map(option => {
                    return {
                        'status': this.value.includes(option.value),
                        'key': option.value,
                        'label': option.label
                    }
                })
                .sort((x, y) => y.status - x.status);
        },
        getCheckedOptions(){
            return this.value
                .map(optionValue => {
                    return {
                        'status': true,
                        'key': optionValue,
                        'label': this.field.options.find(o => o.value === optionValue)['label']
                    }
                });
        }
    },

    mounted() {
        this.value = this.field.value || [];
    }

}