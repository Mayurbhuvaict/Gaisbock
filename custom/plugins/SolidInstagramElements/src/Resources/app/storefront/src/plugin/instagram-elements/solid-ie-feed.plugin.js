import Plugin from 'src/plugin-system/plugin.class';
import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

export default class SolidIEFeedPlugin extends Plugin {
    static options = {
        itemSelector: '[data-feed-item]',
        usernameSelector: '[data-insert-username]',
        dateSelector: '[data-insert-date]',
        captionSelector: '[data-insert-caption]',
        permalinkPlaceholderSelector: '[data-replace-permalink]',
        permalinkClass: 'stretched-link',
        imageContainerSelector: '[data-append-image]',
        snippets: {
            'en-GB': enGB,
            'de-DE': deDE,
        },
        fallbackLocale: 'en-GB',
    };

    async init() {
        const accessToken = this.getAccessToken();

        if (!accessToken) {
            this.handleMissingAccessToken();
            return;
        }

        const data = await this.fetchLatestFeedData(accessToken);

        if (!data) {
            this.handleErrorWhileFetching();
            return;
        }

        this.appendData(data);
    }

    getAccessToken() {
        if (window.solidIEAT) {
            return window.solidIEAT
                .replaceAll('-', 'Z')
                .replaceAll('+', 'A')
                .split('')
                .reverse()
                .join('');
        }

        return false;
    }

    getUsername() {
        if (window.solidIEU) {
            return window.solidIEU;
        }

        return false;
    }

    getErrorMessage() {
        if (window.solidIEEM) {
            return window.solidIEEM;
        }

        return 'An error occurred while trying to fetch your Instagram data.';
    }

    async fetchLatestFeedData(accessToken) {
        try {
            const response = await fetch(
                'https://graph.instagram.com/me/media?fields=username,caption,media_type,media_url,permalink,thumbnail_url,timestamp,children{media_url}&access_token=' +
                    accessToken
            );

            if (!response.ok) {
                throw Error('Response not ok, ' + response.status);
            }

            const data = await response.json();

            if (!data.data) {
                throw new Error('Unexpected structure in response: ' + data);
            }

            return data.data;
        } catch (error) {
            console.error('Error fetching posts: ' + error);
        }

        return false;
    }

    handleMissingAccessToken() {
        console.error('Instagram access token is missing.');
    }

    handleErrorWhileFetching() {
        console.error('Error while trying to fetch data.');

        const alertContent = document.createElement('div');
        alertContent.className = 'alert-content';
        alertContent.innerHTML = this.getErrorMessage();

        const alertContentContainer = document.createElement('div');
        alertContentContainer.className = 'alert-content-container';
        alertContentContainer.appendChild(alertContent);

        const alert = document.createElement('div');
        alert.className = 'alert alert-danger';
        alert.role = 'alert';
        alert.appendChild(alertContentContainer);

        this.el.parentElement.prepend(alert);
    }

    appendData(data) {
        const feedItems = this.el.querySelectorAll(this.options.itemSelector);
        feedItems.forEach((feedItem, index) => {
            if (data[index]) {
                // Insert username
                const username = this.getUsername();
                const usernameElement = feedItem.querySelector(
                    this.options.usernameSelector
                );

                if (username && usernameElement) {
                    usernameElement.innerHTML = username;
                }

                // Insert date
                const dateElement = feedItem.querySelector(
                    this.options.dateSelector
                );

                if (dateElement) {
                    const date = this.getInstagramFormatedDate(
                        new Date(data[index].timestamp)
                    );
                    dateElement.innerHTML = date;
                }

                // Insert caption
                const captionElement = feedItem.querySelector(
                    this.options.captionSelector
                );

                if (captionElement) {
                    captionElement.innerHTML = data[index].caption;
                }

                // Replace permalink
                const permalinkPlaceholder = feedItem.querySelector(
                    this.options.permalinkPlaceholderSelector
                );

                if (permalinkPlaceholder) {
                    const permalink = document.createElement('a');
                    permalink.href = data[index].permalink;
                    permalink.className =
                        'permalink ' + this.options.permalinkClass;
                    permalink.title = permalinkPlaceholder.title || 'Instagram';
                    permalink.innerHTML = permalinkPlaceholder.innerHTML;
                    permalink.target = '_blank';
                    permalink.rel = 'noopener noreferrer';
                    permalinkPlaceholder.parentElement.append(permalink);
                    permalinkPlaceholder.remove();
                }

                // Append image
                const imageContainer =
                    feedItem.querySelector('.image-container');

                if (imageContainer) {
                    const image = document.createElement('img');
                    const mediaType = data[index].media_type;

                    switch (mediaType) {
                        /* Hotfix for missing media_url property on carousel albums */
                        case 'CAROUSEL_ALBUM':
                            if (!data[index].media_url) {
                                if (data[index].children && data[index].children.data && data[index].children.data[0] && data[index].children.data[0].media_url) {
                                    image.src = data[index].children.data[0].media_url;
                                    break;
                                }
                            }

                            image.src = data[index].media_url;
                            break;

                        case 'VIDEO':
                            image.src = data[index].thumbnail_url;
                            break;

                        default:
                            image.src = data[index].media_url;
                            break;
                    }

                    image.alt = 'Instagram';
                    image.title = 'Instagram';

                    image.addEventListener('load', () => {
                        feedItem.classList.remove('loading');
                    });

                    imageContainer.classList.add(
                        mediaType.toLowerCase().replaceAll('_', '-')
                    );

                    imageContainer.appendChild(image);
                }
            }
        });
    }

    getInstagramFormatedDate(date) {
        const currentDate = new Date();
        let delta = currentDate - date;

        // Get locale
        let locale = document.documentElement.getAttribute('lang');

        if (
            !Object.prototype.hasOwnProperty.call(this.options.snippets, locale)
        ) {
            locale = this.options.fallbackLocale;
        }

        // Seconds
        delta = delta / 1000;

        if (delta < 60) {
            return this.options.snippets[locale].seconds;
        }

        // Minutes
        delta = delta / 60;

        if (delta < 60) {
            const minutes = parseInt(delta, 10);

            if (minutes === 1) {
                return this.options.snippets[
                    locale
                ].minutes.singular.replaceAll('%m', minutes);
            }

            return this.options.snippets[locale].minutes.plural.replaceAll(
                '%m',
                minutes
            );
        }

        // Hours
        delta = delta / 60;

        if (delta < 24) {
            const hours = parseInt(delta, 10);

            if (hours === 1) {
                return this.options.snippets[locale].hours.singular.replaceAll(
                    '%h',
                    hours
                );
            }

            return this.options.snippets[locale].hours.plural.replaceAll(
                '%h',
                hours
            );
        }

        // Days
        delta = delta / 24;

        if (delta < 7) {
            const days = parseInt(delta, 10);

            if (days === 1) {
                return this.options.snippets[locale].days.singular.replaceAll(
                    '%d',
                    days
                );
            }

            return this.options.snippets[locale].days.plural.replaceAll(
                '%d',
                days
            );
        }

        // Same year
        delta = delta / 365;
        const dateRegularDigits = date.getDate();
        const month =
            this.options.snippets[locale].date.months[date.getMonth()];
        let dayOf = '';

        switch (dateRegularDigits % 10) {
            case 1:
                dayOf = 'st';
                break;
            case 2:
                dayOf = 'nd';
                break;
            case 3:
                dayOf = 'rd';
                break;
            default:
                dayOf = 'th';
                break;
        }

        if (delta < 1) {
            return this.options.snippets[locale].date.short
                .replaceAll('%do', dateRegularDigits + dayOf)
                .replaceAll('%d', dateRegularDigits)
                .replaceAll('%mmmm', month);
        }

        // Full date
        return this.options.snippets[locale].date.long
            .replaceAll('%do', dateRegularDigits + dayOf)
            .replaceAll('%d', dateRegularDigits)
            .replaceAll('%mmmm', month)
            .replaceAll('%yyyy', date.getFullYear());
    }
}
