const store = {};

function on(event, listener, thisArg, once = false) {
    if (!(
        event in store
    )) {
        store[event] = [];
    }

    store[event].push({
        scope: thisArg || this,
        listener,
        once
    });
}

function once(event, listener, thisArg) {
    return on(event, listener, thisArg, true);
}

function off(event, listener) {
    if (!(
        event in store
    )) {
        return;
    }

    if (listener) {
        const index = store[event].indexOf(listener);

        index > 0 && store[event].splice(index, 1);
    } else {
        delete store[event];
    }
}

function emit(event, ...args) {
    if (event in store) {
        for (let i = 0; i < store[event].length; i++) {
            const item = store[event][i];

            item.listener.apply(item.scope, args);

            if (item.once) {
                store[event].splice(i, 1);
            }
        }
    }
}

export {
    on,
    once,
    off,
    emit
};

export default {
    on,
    once,
    off,
    emit
};
