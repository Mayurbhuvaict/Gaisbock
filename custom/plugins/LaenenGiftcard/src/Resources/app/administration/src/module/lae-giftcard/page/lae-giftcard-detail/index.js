import template from './lae-giftcard-detail.html.twig';
import './lae-giftcard-detail.scss';

const {Criteria} = Shopware.Data;

Shopware.Component.register('lae-giftcard-detail', {
    template,

    inject: ['repositoryFactory', 'giftcardApiService'],

    props: {
        giftcardId: {
            required: true,
            type: String
        }
    },

    metaInfo() {
        return {
            title: this.$createTitle(this.giftcardLabel),
        };
    },

    data() {
        return {
            isLoading: true,
            isDownloadLoading: false,
            hideCode: true,
            giftcard: null,
            showTransactionModal: false,
            newTransaction: null,
            newTransactionType: 'redeem'
        }
    },

    computed: {
        giftcardLabel() {
            if (!this.giftcard) {
                return 'Giftcard';
            }

            let label = '';
            const length = this.giftcard.code.length;

            if (this.hideCode) {
                for (let i in this.giftcard.code) {
                    i = parseInt(i);
                    if (i < (length - 4)) {
                        label = label + '*';
                    } else {
                        label = label + this.giftcard.code[i];
                    }
                }
            } else {
                label = this.giftcard.code;
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
        },

        repository() {
            return this.repositoryFactory.create('lae_giftcard');
        },

        transactionRepository() {
            return this.repositoryFactory.create('lae_giftcard_transaction');
        },

        defaultCriteria() {
            const criteria = new Criteria();
            criteria.addAssociation('currency');
            criteria.addAssociation('salesChannel');
            criteria.getAssociation('transactions')
                .addSorting(Criteria.sort('createdAt', 'DESC'));

            return criteria;
        },

        newTransactionTypeOptions() {
            return [
                {value: 'redeem', label: this.$tc('lae-giftcard.transaction.typeRedeem')},
                {value: 'topup', label: this.$tc('lae-giftcard.transaction.typeTopUp')}
            ];
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.loadGiftcard();
        },

        loadGiftcard() {
            this.isLoading = true;

            return this.repository.get(this.giftcardId, Shopware.Context.api, this.defaultCriteria).then((item) => {
                this.giftcard = item;
                this.isLoading = false;

                return item;
            }).catch(() => {
                this.isLoading = false;
            });
        },

        getTransactionColumns() {
            return [
                {
                    property: 'amount',
                    dataIndex: 'amount',
                    label: 'lae-giftcard.transaction.columnAmount',
                },
                {
                    property: 'comment',
                    dataIndex: 'comment',
                    label: 'lae-giftcard.transaction.columnComment',
                },
                {
                    property: 'createdAt',
                    dataIndex: 'createdAt',
                    label: 'lae-giftcard.transaction.columnCreatedAt',
                }
            ];
        },

        openTransactionModal() {
            this.newTransaction = this.transactionRepository.create();
            this.newTransaction.giftcardId = this.giftcardId;

            this.showTransactionModal = true;
        },

        closeTransactionModal() {
            this.showTransactionModal = false;
        },

        saveNewTransaction() {
            this.showTransactionModal = false;

            this.$nextTick(() => {
                this.isLoading = true;

                if (this.newTransactionType === 'topup') {
                    this.newTransaction.amount = - this.newTransaction.amount;
                }

                this.transactionRepository.save(this.newTransaction).then(() => {
                    this.loadGiftcard();
                });
            });
        },

        onDownloadButtonClick() {
            this.isDownloadLoading = true;

            const id = this.giftcardId;
            const initContainer = Shopware.Application.getContainer('init');
            initContainer.httpClient.get(
                `_action/lae-giftcard/download/${id}`,
                {
                    responseType: 'blob',
                    headers: {
                        Accept: 'application/vnd.api+json',
                        Authorization: `Bearer ${Shopware.Service('loginService').getToken()}`,
                        'Content-Type': 'application/json',
                    },
                },
            ).then((response) => {
                if (response.data) {
                    const filename = response.headers['content-disposition'].split('filename=')[1];
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(new Blob([response.data], {type: response.headers['content-type']}));
                    link.download = filename;
                    link.dispatchEvent(new MouseEvent('click'));
                }

                this.isDownloadLoading = false;
            });
        }
    }
})
