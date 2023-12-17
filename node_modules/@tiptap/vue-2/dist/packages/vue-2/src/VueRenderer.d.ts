import Vue from 'vue';
import { VueConstructor } from 'vue/types/umd';
export declare class VueRenderer {
    ref: Vue;
    constructor(component: Vue | VueConstructor, props: any);
    get element(): Element;
    updateProps(props?: Record<string, any>): void;
    destroy(): void;
}
