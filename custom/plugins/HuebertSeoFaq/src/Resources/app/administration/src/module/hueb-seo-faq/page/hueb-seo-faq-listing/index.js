import template from './hueb-seo-faq-listing.html.twig';
import './hueb-seo-faq-listing.scss';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('hueb-seo-faq-listing', {
    template,

    inject: ['repositoryFactory'],

    mixins: [ Mixin.getByName('notification') ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            groups: [],
            questions: [],
            salesChannel: [],
            activeList: true,
            processSuccess: false,
            total: 0,
            totalGroups: 0,
            totalQuestions: 0,
        };
    },

    computed: {
        salesChannelRepository() {
            return this.repositoryFactory.create('sales_channel');
        },
        groupRepository () {
            return this.repositoryFactory.create('hueb_seo_faq_group');
        },

        questionsRepository() {
            return this.repositoryFactory.create('hueb_seo_faq_questions');
        },

        groupTranslationsRepository() {
            return this.repositoryFactory.create('hueb_seo_faq_group_translation');
        },

        groupQuestionsTranslationsRepository() {
            return this.repositoryFactory.create('hueb_seo_faq_questions_translation');
        },

        columnsGroup() {
            return [{
                property: 'active',
                dataIndex: 'active',
                label: this.$tc('hueb-seo-faq.page.list.group.labelActive'),
                inlineEdit: 'boolean',
                identifier: 'bool',
                allowResize: true,
                align: 'center',
                width: "100px"
            }, {
                property: 'position',
                dataIndex: 'position',
                label: this.$tc('hueb-seo-faq.page.list.group.labelPosition'),
                inlineEdit: 'number',
                allowResize: true
            }, {
                property: 'name',
                dataIndex: 'name',
                label: this.$tc('hueb-seo-faq.page.list.group.labelName'),
                inlineEdit: 'string',
                allowResize: true,
                primary: true
            }, {
                property: 'salesChannelId',
                dataIndex: 'salesChannelId',
                label: this.$tc('hueb-seo-faq.page.list.group.labelSalesChannelId'),
                inlineEdit: 'string',
                allowResize: true,
            }];
        },
        columnsQuestions() {
            return [{
                property: 'group',
                dataIndex: 'group',
                label: this.$tc('hueb-seo-faq.page.list.questions.labelGroup'),
                inlineEdit: 'string',
                primary: true,
                allowResize: true,
                width: "150px"
            },
            /*    {
                property: 'name',
                dataIndex: 'name',
                label: this.$tc('hueb-seo-faq.page.list.questions.labelName'),
                inlineEdit: 'string',
                allowResize: true
            },*/
               {
                property: 'questionPosition',
                dataIndex: 'questionPosition',
                label: this.$tc('hueb-seo-faq.page.list.questions.labelQuestionPosition'),
                inlineEdit: 'number',
                allowResize: true
            }, {
                property: 'question',
                dataIndex: 'question',
                label: this.$tc('hueb-seo-faq.page.list.questions.labelQuestion'),
                inlineEdit: 'string',
                allowResize: true,
                width: "300px"
            }, {
                property: 'answer',
                dataIndex: 'answer',
                label: this.$tc('hueb-seo-faq.page.list.questions.labelAnswer'),
                inlineEdit: 'string',
                allowResize: true,
                width: "300px"
            }, {
                property: 'metaTitle',
                dataIndex: 'metaTitle',
                label: this.$tc('hueb-seo-faq.page.list.questions.labelMetaTitle'),
                inlineEdit: 'string',
                allowResize: true
            }, {
                property: 'metaDescription',
                dataIndex: 'metaDescription',
                label: this.$tc('hueb-seo-faq.page.list.questions.labelMetaDescription'),
                inlineEdit: 'string',
                allowResize: true
            }, {
                property: 'keywords',
                dataIndex: 'keywords',
                label: this.$tc('hueb-seo-faq.page.list.questions.labelKeywords'),
                inlineEdit: 'string',
                allowResize: true
            }];
        },
        groupOptions() {
            let options = [];
            this.groups.forEach((entity) => {
                options.push({value: entity.id, label: entity.name});
            });

            return options;
        },
        groupLabel() {
            let groupArr = this.groupOptions;
            if(groupArr.length) {
                let groupFound = groupArr.find(entry => entry.value === this.groupId);
                if(groupFound) {
                    return groupFound.label;
                }
            }
            return '';
        }
    },


    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.setSalesChannel();
            this.setGroup();
            this.setQuestions();
        },
        setGroup() {

            const criteria = new Criteria();
            criteria.addSorting(Criteria.sort('createdAt', 'DESC'));
            this.groupRepository.search(criteria, Shopware.Context.api)
                .then((entities) => {
                    this.groups = entities;
                    this.totalGroups = entities.total;
                    this.total = entities.total;
                    this.setSalesChannelNames(this.groups);
                }).catch((exception) => {
                this.createNotificationError({
                    title: this.$tc('hueb-seo.general.placeholderError'),
                    message: exception
                });
            });
        },
        setQuestions() {
            const criteria = new Criteria();
            criteria.addSorting(Criteria.sort('createdAt', 'DESC'));
            this.questionsRepository.search(criteria, Shopware.Context.api)
                .then((entities) => {
                    this.questions = entities;
                    this.totalQuestions = entities.total;
                }).catch((exception) => {
                this.createNotificationError({
                    title: this.$tc('hueb-seo.general.placeholderError'),
                    message: exception
                });
            });
        },
        setSalesChannel() {
             this.salesChannelRepository.search(new Criteria(), Shopware.Context.api)
                .then((entities) => {
                    this.salesChannel = entities;
                }).catch((exception) => {
                this.createNotificationError({
                    title: this.$tc('hueb-seo.general.placeholderError'),
                    message: exception
                })
            });
        },
        setSalesChannelNames(data) {
            data.forEach((entity) => {
                if(entity.salesChannelId) {
                    entity.salesChannelName = this.salesChannel.find(entry => entry.id === entity.salesChannelId).name;
                } else {
                    entity.salesChannelName = this.$tc('sw-sales-channel-switch.labelDefaultOption');
                }
            });
        },
        onClickAddGroup() {
            let entry = this.groupRepository.create();
            entry.active = true;
            entry.position = 1;

            let translation = this.groupTranslationsRepository.create(Shopware.Context.api);
            translation.languageId = Shopware.Context.api.systemLanguageId;
            translation.name = this.$tc('hueb-seo-faq.page.list.group.newEntry');
            entry.translations.add(translation);

            this.groupRepository.save(entry, Shopware.Context.api)
                .then(() => {
                    this.processSuccess = true;
                    this.createNotificationSuccess({
                        message: this.$tc('hueb-seo-faq.page.list.group.entrySuccessMessage'),
                    });

                    this.setGroup();
            });
        },
        onClickAddQuestion() {
            let entry = this.questionsRepository.create();
            entry.active = true;
            entry.name = 'ID';

            let translation = this.groupQuestionsTranslationsRepository.create(Shopware.Context.api);
            translation.languageId = Shopware.Context.api.systemLanguageId;
            translation.questionPosition = 1;
            translation.keywords = "";
            entry.translations.add(translation);

            this.questionsRepository.save(entry, Shopware.Context.api)
                .then(() => {
                    this.processSuccess = true;
                    this.createNotificationSuccess({
                        message: this.$tc('hueb-seo-faq.page.list.questions.entrySuccessMessage'),
                    });
                    this.setQuestions();
                });
        },
        onClickTabGroup() {
            this.activeList = true;
            this.total = this.totalGroups
        },
        onClickTabQuestion() {
            this.activeList = false;
            this.total = this.totalQuestions
        },
        onChangeLanguage(languageId) {
            Shopware.State.commit('context/setApiLanguageId', languageId);
            this.setQuestions();
            this.setGroup();
        },
        onSalesChannelChanged(salesChannelId, item) {
            item.salesChannelId = salesChannelId;
        },
        updateRecordsGroup(entries) {
            this.setSalesChannelNames(entries);
        }
    }
});
