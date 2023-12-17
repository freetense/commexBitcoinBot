import Vue, { Component } from 'vue';
export interface NodeViewWrapperInterface extends Vue {
    as: string;
    decorationClasses: {
        value: string;
    };
    onDragStart: Function;
}
export declare const NodeViewWrapper: Component;
