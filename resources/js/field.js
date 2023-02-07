Nova.booting((Vue, router) => {
  Vue.component("index-nova-checkboxes", require("./components/IndexField").default);
  Vue.component("detail-nova-checkboxes", require("./components/DetailField").default);
  Vue.component("form-nova-checkboxes", require("./components/FormField").default);
});
