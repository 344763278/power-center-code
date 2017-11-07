import Trre from './src/main';

Trre.install = function(Vue) {
    Vue.component(Trre.name, Trre);
};

export default Trre;