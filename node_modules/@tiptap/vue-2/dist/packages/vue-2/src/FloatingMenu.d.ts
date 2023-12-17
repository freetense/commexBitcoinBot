import { FloatingMenuPluginProps } from '@tiptap/extension-floating-menu';
import Vue, { Component } from 'vue';
export interface FloatingMenuInterface extends Vue {
    pluginKey: FloatingMenuPluginProps['pluginKey'];
    tippyOptions: FloatingMenuPluginProps['tippyOptions'];
    editor: FloatingMenuPluginProps['editor'];
    shouldShow: FloatingMenuPluginProps['shouldShow'];
}
export declare const FloatingMenu: Component;
