import { BubbleMenuPluginProps } from '@tiptap/extension-bubble-menu';
import Vue, { Component } from 'vue';
export interface BubbleMenuInterface extends Vue {
    pluginKey: BubbleMenuPluginProps['pluginKey'];
    editor: BubbleMenuPluginProps['editor'];
    tippyOptions: BubbleMenuPluginProps['tippyOptions'];
    updateDelay: BubbleMenuPluginProps['updateDelay'];
    shouldShow: BubbleMenuPluginProps['shouldShow'];
}
export declare const BubbleMenu: Component;
