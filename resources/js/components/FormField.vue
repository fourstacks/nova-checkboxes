<template>
    <default-field :field="field">
        <template slot="field">
            <ul class="list-reset">
                <li class="flex items-center" v-for="option in field.options">
                    <checkbox
                        class="py-2 mr-4"
                        @input="handleChange(option.value)"
                        :id="field.name"
                        :name="field.name"
                        :checked="optionValues[option.value]"
                    ></checkbox>
                    <label
                        :for="field.name"
                        v-text="option.label"
                    ></label>
                </li>
            </ul>

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova'

    export default {
        mixins: [FormField, HandlesValidationErrors],

        data: () => ({
            value: [],
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
                this.value = this.field.value || []
            },

            fill(formData) {
                formData.append(this.field.attribute, this.value || [])
            },

            handleChange(key) {
                if(this.value.includes(key)){
                    let index = this.value.indexOf(key);
                    this.value.splice(index, 1);
                }
                else {
                    this.value.push(key);
                }
            }
        },
    }
</script>
