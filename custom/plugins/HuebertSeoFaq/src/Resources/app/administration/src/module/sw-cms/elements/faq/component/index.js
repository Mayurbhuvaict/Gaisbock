import template from './sw-cms-el-faq.html.twig';
import './sw-cms-el-faq.scss';

const { Component, Context } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('sw-cms-el-faq', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        'cms-element'
    ],

    created() {
        this.createdComponent();
    },

    computed: {
        faqGroup() {
            let faqGroupRepository = this.repositoryFactory.create('hueb_seo_faq_group'),
                criteria = new Criteria();

            criteria.setIds([this.element.config.group.value]);

            return faqGroupRepository.search(criteria, Context.api).then((result) => {
                return result.id;
            });
        }
    },

    methods: {
        createdComponent() {
            this.initElementConfig('hueb-faq-element');
        }
    }
});
