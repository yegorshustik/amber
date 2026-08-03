import { render, type VNode } from 'vue';

export function mountTemporary(vnodeFactory: () => VNode) {
    const container = document.createElement('div');
    document.body.appendChild(container);

    const unmount = () => {
        //console.log(container);
        render(null, container);
        container.remove();
    };

    render(vnodeFactory(), container);

    return { unmount };
}
