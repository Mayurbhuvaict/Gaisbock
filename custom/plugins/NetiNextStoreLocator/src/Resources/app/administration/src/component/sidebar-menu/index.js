import template from './template.html.twig';
import './style.scss';

Shopware.Component.register('neti-store-locator-sidebar-menu', {
    template,

    data() {
        return {
            items: []
        }
    },

    mounted() {
        this.enrichItems();
    },

    methods: {
        isActive(item) {
            const route = this.$route.name;

            if (route === item.route) {
                return true;
            }

            if (
                'activeRoutePrefix' in item
                && Array.isArray(item.activeRoutePrefix)
            ) {
                return item.activeRoutePrefix.filter(prefix => {
                    return route.indexOf(prefix) === 0;
                }).length > 0;
            }

            return false;
        },

        hasActiveChildren(children) {
            return children.filter(child => this.isActive(child)).length > 0;
        },

        enrichItems() {
            this.add(
                'store-locator',
                this.$t('neti-next-store-locator.sidebar-menu.store-locator.title'),
                [
                    {
                        title: this.$t('neti-next-store-locator.sidebar-menu.store-locator.stores'),
                        route: 'neti.store_locator.overview',
                        activeRoutePrefix: [ 'neti.store_locator.create', 'neti.store_locator.detail' ]
                    },
                    {
                        title: this.$t('neti-next-store-locator.sidebar-menu.store-locator.contactForm'),
                        route: 'neti.store_locator.contact_form.overview',
                        activeRoutePrefix: [ 'neti.store_locator.contact_form.' ]
                    },
                    {
                        title: this.$t('neti-next-store-locator.sidebar-menu.store-locator.filter'),
                        route: 'neti.sl.filter.overview',
                        activeRoutePrefix: [ 'neti.sl.filter.' ]
                    }
                ],
                -100
            );
        },

        add(name, title, children, position) {
            const item = this.get(name);

            if (!item) {
                children = children || [];
                position = position || this._getNextPosition(this.items);

                this.items.push({
                    name,
                    title,
                    children: [],
                    position
                });

                this.items.sort((a, b) => a.position - b.position);

                children.forEach(child => {
                    this.addChildren(name, child);
                });
            }
        },

        get(name) {
            return this.items.find(i => i.name === name);
        },

        addChildren(name, children) {
            const item = this.get(name);

            if (item) {
                item.children.push({
                    position: this._getNextPosition(item.children),
                    ...children
                });
            }
        },

        _getNextPosition(list) {
            if (list.length > 0) {
                return list[list.length - 1].position + 1;
            }

            return 0;
        }
    }
});