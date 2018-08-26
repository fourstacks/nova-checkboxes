Nova.booting((Vue, router) => {
    Vue.component('index-nova-checkboxes', require('./components/IndexField'));
    Vue.component('detail-nova-checkboxes', require('./components/DetailField'));
    Vue.component('form-nova-checkboxes', require('./components/FormField'));
})
