import template from './lae-giftcard-list.html.twig';
import './lae-giftcard-list.scss';

const {Component, Mixin} = Shopware;
const {Criteria} = Shopware.Data;

Shopware.Component.override('lae-giftcard-list', {
    template,

    inject: ['repositoryFactory', 'giftcardApiService'],

    mixins: [
        Mixin.getByName('listing'),
    ],

    metaInfo() {
        return {
            title: this.$createTitle(),
        };
    },

    data() {
        return {
            overviewLoading: true,
            overviewData: {},
            giftcards: null,
            isLoading: false,
            sortBy: 'createdAt',
            sortDirection: 'DESC'
        };
    },

    computed: {
        repository() {
            return this.repositoryFactory.create('lae_giftcard');
        },

        orderRepository(){
          return this.repositoryFactory.create('order');
        },

        defaultCriteria() {
            const criteria = new Criteria(this.page, this.limit);

            criteria.addAssociation('currency');
            criteria.setTerm(this.term);
            criteria.addSorting(Criteria.sort(this.sortBy, this.sortDirection, this.useNaturalSorting));

            return criteria;
        },

        useNaturalSorting() {
            return this.sortBy === 'createdAt';
        },

        systemCurrencyISOCode() {
            return Shopware.Context.app.systemCurrencyISOCode;
        },
    },

    created() {
        this.loadOverview();
        this.getList();
    },

    methods: {
        loadOverview() {
            this.overviewLoading = true;

            this.giftcardApiService.overview().then((response) => {
                this.overviewData = response.data;

                this.overviewLoading = false;
            })
        },

        getList() {
            this.isLoading = true;

            return this.repository.search(this.defaultCriteria).then((items) => {
                items.forEach((value, index) => {
                    let orderId = value.originOrderId;
                    let criteria = new Criteria();
                    criteria.addFilter(Criteria.equals('id', orderId));
                    this.orderRepository.search(criteria, Shopware.Context.api).then((item) => {
                        console.log(item[0].orderNumber)
                        items[index]['orderNumber'] = item[0].orderNumber;
                    });

                });
                this.total = items.total;
                this.giftcards = items;
                this.isLoading = false;
                return items;
            }).catch(() => {
                this.isLoading = false;
            });
        },

        getColumns() {
            return [
                {
                    property: 'orderNumber',
                    dataIndex: 'orderNumber',
                    label: 'orderNumber',
                    sortable: false,
                },
                {
                    property: 'code',
                    dataIndex: 'code',
                    label: 'lae-giftcard.list.columnCode',
                    sortable: false,
                },
                {
                    property: 'balance',
                    dataIndex: 'balance',
                    label: 'lae-giftcard.list.columnBalance',
                    sortable: false,
                },
                {
                    property: 'initialAmount',
                    dataIndex: 'initialAmount',
                    label: 'lae-giftcard.list.columnInitialAmount',
                    sortable: false,
                },
                {
                    property: 'createdAt',
                    dataIndex: 'createdAt',
                    label: 'lae-giftcard.list.columnCreatedAt',
                    sortable: false,
                },
            ];
        },

        giftcardLabel(giftcard) {
            let label = '';
            const length = giftcard.code.length;

            for (let i in giftcard.code) {
                i = parseInt(i);
                if (i < (length - 4)) {
                    label = label + '*';
                } else {
                    label = label + giftcard.code[i];
                }
            }

            let spacedLabel = '';
            for (let i in label) {
                i = parseInt(i);
                spacedLabel += label[i];

                if ((i + 1) % 4 === 0) {
                    spacedLabel += ' ';
                }
            }

            return spacedLabel;
        }
    }
});
