const Constants = {
    TIME: 500
};
Constants.install = function (Vue) {
    Vue.prototype.$getConst = (key) => {
        return Constants[key];
    }
};
export default Constants;