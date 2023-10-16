Shopware.Component.extend('neti-store-locator-tabs-item', 'sw-tabs-item', {
    props: {
        activeRoutePrefix: {
            type: Array,
            required: false,
            default() {
                return [];
            }
        }
    },

    computed: {
        tabsItemClasses() {
            const classes = this.$super('tabsItemClasses');

            this.activeRoutePrefix.forEach(prefix => {
                if (this.$route.name.indexOf(prefix) === 0) {
                    classes['sw-tabs-item--active'] = true;
                }
            })

            return classes;
        }
    }
});