'use strict';

import { markdownToHtml } from "../app/markdown";

export class MarkdownElement extends HTMLElement {
    static get observedAttributes() {
        return ['content', 'animate'];
    }

    constructor() {
        super();

        // Create a container element
        this.container = document.createElement('div');
        this.appendChild(this.container);

        this.content = '';
    }

    setContent(content) {
        this.content = content;
        this.render();
    }

    connectedCallback() {
        this.render();
    }

    attributeChangedCallback(name, oldValue, newValue) {
        if (name == 'content') {
            this.setContent(newValue);
        }
    }

    render() {
        let content = markdownToHtml(this.content, this.hasAttribute('animate'));

        const newContent = new DOMParser().parseFromString(content, 'text/html').body;
        this.updateElement(this.container, newContent);
    }

    updateElement(oldEl, newEl) {
        // Sync attributes
        const oldAttrs = Array.from(oldEl.attributes);
        const newAttrs = Array.from(newEl.attributes);
        const newAttrMap = new Map(newAttrs.map(attr => [attr.name, attr.value]));

        oldAttrs.forEach(attr => {
            const newValue = newAttrMap.get(attr.name);
            if (newValue === undefined) {
                oldEl.removeAttribute(attr.name);
            } else if (attr.value !== newValue) {
                oldEl.setAttribute(attr.name, newValue);
            }
        });
        newAttrs.forEach(attr => {
            if (!oldEl.hasAttribute(attr.name)) {
                oldEl.setAttribute(attr.name, attr.value);
            }
        });

        // Diff children
        const oldChildren = Array.from(oldEl.childNodes);
        const newChildren = Array.from(newEl.childNodes);

        let oldIdx = 0, newIdx = 0;
        while (newIdx < newChildren.length || oldIdx < oldChildren.length) {
            const oldChild = oldChildren[oldIdx];
            const newChild = newChildren[newIdx];

            if (!oldChild && newChild) {
                oldEl.appendChild(newChild.cloneNode(true));
                newIdx++;
            } else if (oldChild && !newChild) {
                oldEl.removeChild(oldChild);
                oldIdx++;
            } else if (
                oldChild.nodeType === newChild.nodeType &&
                (oldChild.nodeType !== Node.ELEMENT_NODE ||
                    oldChild.nodeName === newChild.nodeName)
            ) {
                // Same type and tag, recurse or update text
                if (oldChild.nodeType === Node.TEXT_NODE) {
                    if (oldChild.textContent !== newChild.textContent) {
                        oldChild.textContent = newChild.textContent;
                    }
                } else {
                    this.updateElement(oldChild, newChild);
                }
                oldIdx++;
                newIdx++;
            } else {
                // Node type or tag changed, replace only this node
                oldEl.replaceChild(newChild.cloneNode(true), oldChild);
                oldIdx++;
                newIdx++;
            }
        }
    }
}


