<template>
    <DefaultField :field="field">
        <template #field>
            <ul class="list-reset">
                <li class="flex items-center mb-4" v-for="(option, index) in field.options" :key="index">
                    <checkbox
                        class="py-2 mr-4"
                        @input="handleChange(option.value)"
                        :name="field.name"
                        :checked="options[option.value]"
                    ></checkbox>
                    <label
                        v-text="option.label"
                        @click="handleChange(option.value)"
                    ></label>
                </li>
            </ul>

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </DefaultField>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova'

    export default {
        mixins: [FormField, HandlesValidationErrors],

        data: () => ({
            value: '',
            options: []
        }),

        props: ['resourceName', 'resourceId', 'field'],

        computed:{
            optionValues(){
                return this.field.options
                    .map(o => o.value)
                    .reduce((o, key) => ({ ...o, [key]: this.value.includes(key)}), {})
            }
        },

        methods: {

            setInitialValue() {
                this.value = this.field.value || '';
                this.$nextTick(() => {
                    this.options = (this.value)
                        ? JSON.parse(this.value)
                        : [];
                });
            },

            fill(formData) {
                formData.append(this.field.attribute, this.value || '')
            },

            handleChange(key) {
                this.options[key] = ! this.options[key];
            }
        },

        watch: {
            'options' : {
                handler: function (newOptions) {
                    this.value = JSON.stringify(newOptions);
                },
                deep: true
            }
        }
    }
</script>
