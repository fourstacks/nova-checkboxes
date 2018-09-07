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
                        'status': this.value[option.value],
                        'key': option.value,
                        'label': option.label
                    }
                })
                .sort((x, y) => y.status - x.status);
        },
        getCheckedOptions(){
            return this.field.options
                .filter(option => {
                    return this.value[option.value];
                })
                .map(option => {
                    return {
                        'status': true,
                        'key': option.value,
                        'label': option.label
                    }
                });
        }
    },

    mounted() {
        this.value = JSON.parse(this.field.value) || {};
    }

}