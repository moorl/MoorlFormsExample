import Plugin from 'src/plugin-system/plugin.class';

export default class MoorlFbExamplePlugin extends Plugin {
    static options = {};

    init() {
        this.el.querySelector("input").style.backgroundColor = "#FF0000";
    }
}
