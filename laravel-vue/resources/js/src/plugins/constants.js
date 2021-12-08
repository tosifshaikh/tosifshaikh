const Constants = {
    TIME: 5000
};
Constants.install = function (Vue) {
    Vue.prototype.$getConst = (key) => {
        return Constants[key];
    }
};
export default Constants;