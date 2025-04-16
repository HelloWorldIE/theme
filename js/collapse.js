document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-collapse]').forEach(control => {
        control.addEventListener('click', function (event) {
            event.stopPropagation();

            const targets = control.getAttribute('data-collapse').split(' ');
            const shouldOpen = targets.some(target => {
                const elements = document.querySelectorAll(`[data-collapse-target="${target}"]`);
                return Array.from(elements).some(element => !element.classList.contains('expanded'));
            });

            targets.forEach(target => {
                document.querySelectorAll(`[data-collapse-target="${target}"]`).forEach(element => {
                    element.classList.toggle('expanded', shouldOpen);
                });
            });

            document.querySelectorAll('[data-collapse]').forEach(btn => {
                if (!btn.hasAttribute('data-toggle-only')) {
                    const btnTargets = btn.getAttribute('data-collapse').split(' ');
                    const isAnyClosed = btnTargets.some(target => {
                        const elements = document.querySelectorAll(`[data-collapse-target="${target}"]`);
                        return Array.from(elements).some(element => !element.classList.contains('expanded'));
                    });

                    btn.textContent = isAnyClosed ? btn.getAttribute('data-open-text') : btn.getAttribute('data-close-text');
                }
            });
        });

       /* if (control.hasAttribute('data-toggle-only')) {
            control.classList.add('has-tooltip-arrow', 'has-tooltip-small', 'has-tooltip-secondary', 'has-tooltip-bottom');
            control.setAttribute('data-tooltip', 'Click to toggle');
        }*/
    });

    document.querySelectorAll('[data-collapse]').forEach(control => {
        if (!control.hasAttribute('data-toggle-only')) {
            const targets = control.getAttribute('data-collapse').split(' ');
            const isAnyClosed = targets.some(target => {
                const elements = document.querySelectorAll(`[data-collapse-target="${target}"]`);
                return Array.from(elements).some(element => !element.classList.contains('expanded'));
            });

            control.textContent = isAnyClosed ? control.getAttribute('data-open-text') : control.getAttribute('data-close-text');
        }
    });
});
