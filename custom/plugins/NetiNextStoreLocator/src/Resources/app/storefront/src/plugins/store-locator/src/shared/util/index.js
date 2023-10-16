function nl2br(str, is_xhtml) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Philip Peterson
    // +   improved by: Onno Marsman
    // +   improved by: Atli Þór
    // +   bugfixed by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Maximusya
    // *     example 1: nl2br('Kevin\nvan\nZonneveld');
    // *     returns 1: 'Kevin<br />\nvan<br />\nZonneveld'
    // *     example 2: nl2br("\nOne\nTwo\n\nThree\n", false);
    // *     returns 2: '<br>\nOne<br>\nTwo<br>\n<br>\nThree<br>\n'
    // *     example 3: nl2br("\nOne\nTwo\n\nThree\n", true);
    // *     returns 3: '<br />\nOne<br />\nTwo<br />\n<br />\nThree<br />\n'
    var breakTag = (
        is_xhtml || typeof is_xhtml === 'undefined'
    ) ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display

    return (
        str + ''
    ).replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

/**
 * Calculates the distance between two coordinates.
 *
 * @param p1
 * @param p2
 * @returns {number}
 */
function distanceBetweenPoints(p1, p2, distanceUnit) {
    if (!p1 || !p2) {
        return 0;
    }

    let R = 6371; // Radius of the Earth in km

    if (distanceUnit === 'miles') {
        R = R / 1.609344;
    }

    const dLat = (
        p2.lat() - p1.lat()
    ) * Math.PI / 180;
    const dLon = (
        p2.lng() - p1.lng()
    ) * Math.PI / 180;
    const a    = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c    = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const d    = R * c;

    return d;
}

function easeInOutQuad(currentTime, startValue, difference, duration) {
    currentTime /= duration / 2;
    if (currentTime < 1) {
        return difference / 2 * currentTime * currentTime + startValue;
    }
    currentTime--;
    return -difference / 2 * (
        currentTime * (
            currentTime - 2
        ) - 1
    ) + startValue;
}

function scrollTo(to, duration, el, direction = 'top') {
    const element   = el || document.scrollingElement || document.documentElement;
    const property  = direction === 'top' ? 'scrollTop' : 'scrollLeft';
    const start     = element[property];
    const change    = to - start;
    const startDate = +new Date();

    element._scrolling = true;

    const animateScroll = function() {
        const currentDate = +new Date();
        const currentTime = currentDate - startDate;
        element[property] = parseInt(easeInOutQuad(currentTime, start, change, duration));
        if (currentTime < duration) {
            requestAnimationFrame(animateScroll);
        } else {
            element[property]  = to;
            element._scrolling = false;
        }
    };
    animateScroll();
}

function serializeFormToFormData(el) {
    const result = new FormData();

    el.querySelectorAll('input, textarea, select').forEach(field => {
        const name = field.getAttribute('name');
        let value  = field.value;

        switch (field.getAttribute('type')) {
            case 'checkbox':
                value = field.checked;
                break;
            case 'radio':
                value = result.hasOwnProperty(name) && result[name]
                    ? result[name]
                    : (
                        field.checked ? value : null
                    );
                break;
            case 'file':
                if (field.files.length === 1) {
                    value = field.files[0];
                }
                break;
        }

        result.append(name, value);
    });

    return result;
}

function isObject(item) {
    return (
        item && typeof item === 'object' && !Array.isArray(item)
    );
}

function mergeDeep(target, ...sources) {
    if (!sources.length) {
        return target;
    }
    const source = sources.shift();

    if (isObject(target) && isObject(source)) {
        for (const key in source) {
            if (isObject(source[key])) {
                if (!target[key]) {
                    Object.assign(target, { [key]: {} });
                }
                mergeDeep(target[key], source[key]);
            } else {
                Object.assign(target, { [key]: source[key] });
            }
        }
    }

    return mergeDeep(target, ...sources);
}

function inheritComponent(component, config) {
    if ('data' in config) {
        const originalData   = component.data;
        const additionalData = config.data;

        component.data = function() {
            return {
                ...originalData(),
                ...additionalData()
            };
        };

        delete config.data;
    }

    const result = mergeDeep(component, config);

    delete result.el;
    delete result.template;

    return result;
}

export {
    nl2br,
    distanceBetweenPoints,
    scrollTo,
    serializeFormToFormData,
    inheritComponent
};
