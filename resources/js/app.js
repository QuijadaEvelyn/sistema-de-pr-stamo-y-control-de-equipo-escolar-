//

/* ==========================================
   STOCK TECNOLÓGICO
   JAVASCRIPT PRINCIPAL
========================================== */

document.addEventListener('DOMContentLoaded', () => {

    // ======================================
    // MODO CLARO Y OSCURO
    // ======================================

    const themeButtons = document.querySelectorAll(
        '[data-theme-toggle]'
    );

    function getSavedTheme() {
        try {
            return localStorage.getItem('stock-theme');
        } catch (error) {
            return null;
        }
    }

    function saveTheme(theme) {
        try {
            localStorage.setItem('stock-theme', theme);
        } catch (error) {
            // El tema seguirá funcionando durante esta sesión.
        }
    }

    function applyTheme(theme) {
        const selectedTheme = theme === 'dark'
            ? 'dark'
            : 'light';

        document.documentElement.setAttribute(
            'data-theme',
            selectedTheme
        );

        themeButtons.forEach(button => {
            button.setAttribute(
                'aria-label',
                selectedTheme === 'dark'
                    ? 'Activar modo claro'
                    : 'Activar modo oscuro'
            );

            button.setAttribute(
                'aria-pressed',
                String(selectedTheme === 'dark')
            );

            const sun = button.querySelector(
                '[data-icon-sun]'
            );

            const moon = button.querySelector(
                '[data-icon-moon]'
            );

            if (sun) {
                sun.hidden = selectedTheme !== 'dark';
            }

            if (moon) {
                moon.hidden = selectedTheme === 'dark';
            }
        });
    }

    const savedTheme = getSavedTheme();

    const systemDark = window.matchMedia(
        '(prefers-color-scheme: dark)'
    ).matches;

    applyTheme(
        savedTheme === 'dark' || savedTheme === 'light'
            ? savedTheme
            : systemDark ? 'dark' : 'light'
    );

    themeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const currentTheme =
                document.documentElement.getAttribute(
                    'data-theme'
                );

            const newTheme = currentTheme === 'dark'
                ? 'light'
                : 'dark';

            applyTheme(newTheme);
            saveTheme(newTheme);
        });
    });


    // ======================================
    // SIDEBAR RESPONSIVE
    // ======================================

    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector(
        '.sidebar-overlay'
    );

    const menuButtons = document.querySelectorAll(
        '[data-sidebar-toggle]'
    );

    function setSidebar(open) {
        if (!sidebar) return;

        sidebar.classList.toggle('open', open);

        if (sidebarOverlay) {
            sidebarOverlay.classList.toggle('show', open);
        }

        menuButtons.forEach(button => {
            button.setAttribute(
                'aria-expanded',
                String(open)
            );
        });

        document.body.style.overflow = open
            ? 'hidden'
            : '';
    }

    menuButtons.forEach(button => {
        button.addEventListener('click', () => {
            setSidebar(
                !sidebar.classList.contains('open')
            );
        });
    });

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            setSidebar(false);
        });
    }

    document.querySelectorAll(
        '.sidebar-link'
    ).forEach(link => {
        link.addEventListener('click', () => {
            setSidebar(false);
        });
    });


    // ======================================
    // MODALES
    // ======================================

    let activeModal = null;
    let previousFocus = null;

    function openModal(modalId) {
        const modal = document.getElementById(modalId);

        if (!modal || !modal.classList.contains('modal')) {
            return;
        }

        if (activeModal && activeModal !== modal) {
            closeModal(activeModal);
        }

        previousFocus = document.activeElement;

        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');

        activeModal = modal;
        document.body.style.overflow = 'hidden';

        const firstInput = modal.querySelector(
            'input:not([type="hidden"]), select, textarea, button'
        );

        if (firstInput) {
            firstInput.focus();
        }
    }

    function closeModal(modal = activeModal) {
        if (!modal) return;

        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');

        if (activeModal === modal) {
            activeModal = null;
        }

        document.body.style.overflow =
            sidebar && sidebar.classList.contains('open')
                ? 'hidden'
                : '';

        if (
            previousFocus &&
            typeof previousFocus.focus === 'function'
        ) {
            previousFocus.focus();
        }
    }

    document.querySelectorAll(
        '[data-modal-open]'
    ).forEach(button => {
        button.addEventListener('click', () => {
            openModal(
                button.dataset.modalOpen
            );
        });
    });

    document.querySelectorAll(
        '[data-modal-close]'
    ).forEach(button => {
        button.addEventListener('click', () => {
            closeModal(
                button.closest('.modal')
            );
        });
    });

    document.querySelectorAll(
        '.modal'
    ).forEach(modal => {
        modal.addEventListener('click', event => {
            if (event.target === modal) {
                closeModal(modal);
            }
        });
    });


    // ======================================
    // CONFIRMACIONES
    // ======================================

    document.querySelectorAll(
        'form[data-confirm]'
    ).forEach(form => {
        form.addEventListener('submit', event => {
            const message = form.dataset.confirm ||
                '¿Deseas continuar con esta operación?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });


    // ======================================
    // ALERTAS
    // ======================================

    document.querySelectorAll(
        '[data-alert-close]'
    ).forEach(button => {
        button.addEventListener('click', () => {
            const alert = button.closest('.alert');

            if (alert) {
                alert.remove();
            }
        });
    });


    // ======================================
    // MOSTRAR U OCULTAR CONTRASEÑA
    // ======================================

    document.querySelectorAll(
        '[data-password-toggle]'
    ).forEach(button => {
        button.addEventListener('click', () => {
            const inputId = button.dataset.passwordToggle;
            const input = document.getElementById(inputId);

            if (!input) return;

            const show = input.type === 'password';

            input.type = show ? 'text' : 'password';

            button.setAttribute(
                'aria-label',
                show ? 'Ocultar contraseña' : 'Mostrar contraseña'
            );

            button.setAttribute(
                'aria-pressed',
                String(show)
            );
        });
    });


    // ======================================
    // EDICIÓN DE REGISTROS
    // ======================================

    // Los botones de edición tendrán:
    // data-edit-modal="editCategoryModal"
    // data-edit-url="/categories/1"
    // data-edit-name="Laptops"
    // data-edit-description="Computadoras"

    document.querySelectorAll(
        '[data-edit-modal]'
    ).forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.dataset.editModal;
            const modal = document.getElementById(modalId);

            if (!modal) return;

            const form = modal.querySelector('form');

            if (!form) return;

            if (button.dataset.editUrl) {
                form.action = button.dataset.editUrl;
            }

            Object.entries(button.dataset).forEach(
                ([key, value]) => {
                    if (!key.startsWith('edit')) return;

                    if (
                        key === 'editModal' ||
                        key === 'editUrl'
                    ) {
                        return;
                    }

                    const fieldName =
                        key.charAt(4).toLowerCase() +
                        key.slice(5);

                    const input = Array.from(
                        form.querySelectorAll(
                            'input[name], select[name], textarea[name]'
                        )
                    ).find(field => field.name === fieldName);

                    if (input) {
                        if (input.type === 'checkbox') {
                            input.checked =
                                value === '1' ||
                                value === 'true';
                        } else {
                            input.value = value;
                        }
                    }
                }
            );

            openModal(modalId);
        });
    });


    // ======================================
    // SOLICITUDES CON VARIOS EQUIPOS
    // ======================================

    const loanItemsContainer = document.getElementById(
        'loan-items-container'
    );

    const addLoanItemButton = document.getElementById(
        'add-loan-item'
    );

    const loanItemTemplate = document.getElementById(
        'loan-item-template'
    );

    let loanItemIndex = 0;

    function updateLoanItemNames() {
        if (!loanItemsContainer) return;

        const rows = loanItemsContainer.querySelectorAll(
            '.loan-item-row'
        );

        rows.forEach((row, index) => {
            const equipmentSelect = row.querySelector(
                '[data-loan-equipment]'
            );

            const quantityInput = row.querySelector(
                '[data-loan-quantity]'
            );

            if (equipmentSelect) {
                equipmentSelect.name =
                    `items[${index}][equipment_id]`;
            }

            if (quantityInput) {
                quantityInput.name =
                    `items[${index}][quantity]`;
            }
        });

        loanItemIndex = rows.length;
    }

    function updateEquipmentAvailability(row) {
        const select = row.querySelector(
            '[data-loan-equipment]'
        );

        const quantityInput = row.querySelector(
            '[data-loan-quantity]'
        );

        if (!select || !quantityInput) return;

        const selectedOption =
            select.options[select.selectedIndex];

        const available = Number(
            selectedOption?.dataset.available || 0
        );

        if (available > 0) {
            quantityInput.max = available;

            if (
                Number(quantityInput.value) > available
            ) {
                quantityInput.value = available;
            }
        } else {
            quantityInput.removeAttribute('max');
        }
    }

    function createLoanItem() {
        if (!loanItemsContainer || !loanItemTemplate) {
            return;
        }

        const fragment =
            loanItemTemplate.content.cloneNode(true);

        const row = fragment.querySelector(
            '.loan-item-row'
        );

        if (!row) return;

        loanItemsContainer.appendChild(fragment);

        updateLoanItemNames();
    }

    if (addLoanItemButton) {
        addLoanItemButton.addEventListener(
            'click',
            createLoanItem
        );
    }

    if (loanItemsContainer) {
        loanItemsContainer.addEventListener(
            'click',
            event => {
                const removeButton = event.target.closest(
                    '[data-remove-loan-item]'
                );

                if (!removeButton) return;

                const rows = loanItemsContainer.querySelectorAll(
                    '.loan-item-row'
                );

                if (rows.length <= 1) {
                    return;
                }

                removeButton.closest(
                    '.loan-item-row'
                )?.remove();

                updateLoanItemNames();
            }
        );

        loanItemsContainer.addEventListener(
            'change',
            event => {
                if (
                    event.target.matches(
                        '[data-loan-equipment]'
                    )
                ) {
                    updateEquipmentAvailability(
                        event.target.closest('.loan-item-row')
                    );
                }
            }
        );

        updateLoanItemNames();
    }


    // ======================================
    // VALIDAR PRÉSTAMOS ANTES DE ENVIAR
    // ======================================

    const loanForm = document.getElementById(
        'loan-request-form'
    );

    if (loanForm) {
        loanForm.addEventListener('submit', event => {
            const rows = loanForm.querySelectorAll(
                '.loan-item-row'
            );

            const selectedEquipment = new Set();

            for (const row of rows) {
                const select = row.querySelector(
                    '[data-loan-equipment]'
                );

                const quantityInput = row.querySelector(
                    '[data-loan-quantity]'
                );

                if (!select || !quantityInput) {
                    continue;
                }

                const equipmentId = select.value;
                const quantity = Number(
                    quantityInput.value
                );

                const selectedOption =
                    select.options[select.selectedIndex];

                const available = Number(
                    selectedOption?.dataset.available || 0
                );

                if (!equipmentId) {
                    event.preventDefault();

                    alert(
                        'Selecciona un equipo en cada fila.'
                    );

                    select.focus();
                    return;
                }

                if (selectedEquipment.has(equipmentId)) {
                    event.preventDefault();

                    alert(
                        'No puedes seleccionar el mismo equipo dos veces.'
                    );

                    select.focus();
                    return;
                }

                selectedEquipment.add(equipmentId);

                if (
                    !Number.isInteger(quantity) ||
                    quantity < 1 ||
                    quantity > available
                ) {
                    event.preventDefault();

                    alert(
                        'La cantidad solicitada no es válida o supera las unidades disponibles.'
                    );

                    quantityInput.focus();
                    return;
                }
            }
        });
    }


    // ======================================
    // VALIDAR FECHAS DE PRÉSTAMO
    // ======================================

    const startDate = document.getElementById(
        'start_date'
    );

    const dueDate = document.getElementById(
        'due_date'
    );

    if (startDate && dueDate) {
        startDate.addEventListener('change', () => {
            dueDate.min = startDate.value;

            if (
                dueDate.value &&
                dueDate.value < startDate.value
            ) {
                dueDate.value = startDate.value;
            }
        });
    }


    // ======================================
    // EVITAR ENVÍOS DUPLICADOS
    // ======================================

    document.querySelectorAll(
        'form[data-prevent-double-submit]'
    ).forEach(form => {
        form.addEventListener('submit', event => {
            if (event.defaultPrevented) {
                return;
            }

            if (form.dataset.submitting === 'true') {
                event.preventDefault();
                return;
            }

            if (!form.checkValidity()) {
                return;
            }

            form.dataset.submitting = 'true';

            const submitButton = form.querySelector(
                'button[type="submit"]'
            );

            if (submitButton) {
                submitButton.setAttribute(
                    'aria-disabled',
                    'true'
                );
            }
        });
    });


    // ======================================
    // CERRAR MODALES CON ESCAPE
    // ======================================

    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') {
            return;
        }

        if (activeModal) {
            closeModal(activeModal);
        } else if (
            sidebar &&
            sidebar.classList.contains('open')
        ) {
            setSidebar(false);
        }
    });

});
