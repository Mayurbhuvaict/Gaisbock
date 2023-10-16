import template from './template.twig';
import './style.scss';

const { Criteria } = Shopware.Data;

Shopware.Component.register('neti-store-locator-business-hours', {
    template,

    inject: [
        'repositoryFactory',
    ],

    data() {
        return {
            weekDays: [],
        };
    },

    props: {
        businessHours: {
            type: Object,
            required: true,
        },
        store: {
            type: Object,
            required: true
        },
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        }
    },

    computed: {
        weekDayRepository() {
            return this.repositoryFactory.create('neti_business_weekday');
        },

        weekDayCriteria() {
            const criteria = new Criteria();

            criteria.addSorting(Criteria.sort('number', 'ASC'));

            return criteria;
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.getWeekDays();
        },

        getWeekDays() {
            this.weekDayRepository.search(this.weekDayCriteria, Shopware.Context.api).then(result => {
                this.weekDays = result;
            });
        },

        addDay(weekDayId) {
            this.$set(this.businessHours, weekDayId, this.businessHours[weekDayId] || []);

            this.businessHours[weekDayId].push({ start: '', end: '' });
        },

        addSpecialDay(special) {
            this.$set(this.businessHours, special, this.businessHours[special] || []);

            this.businessHours[special].push({
                specialDate: '',
                start: '',
                end: '',
                description: '',
                annual: false,
            });
        },

        removeBusinessHour(type, index) {
            this.businessHours[type].splice(index, 1);
        }
    }
});
