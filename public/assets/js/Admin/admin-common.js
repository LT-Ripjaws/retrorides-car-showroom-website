/**
 * Admin Common JavaScript
 * Unified script for all admin panel pages (Cars, Teams, Users)
 * Handles modals, search/filtering, and common UI interactions
 */

const AdminConfig = {
    selectors: {
        modal: '.admin-modal',
        openBtn: '.admin-add-btn',
        cancelBtn: '.admin-cancel-btn',
        form: '.admin-form',
        modalTitle: '.admin-modal-title',
        idInput: '.admin-id-input',
        searchBar: '#adminSearchBar',
        filterContainer: '.admin-filters',
        searchBtn: '#adminSearchBtn',
        tableContainer: '.admin-table',
        tableRows: '.admin-table tbody tr',
        editBtn: '.admin-edit-btn',
        alerts: '.error-box, .success-box'
    },
    searchDelay: 300,
    alertDelay: 5000
};

// === HELPERS ===
const $ = (sel, parent = document) => parent.querySelector(sel);
const $$ = (sel, parent = document) => Array.from(parent.querySelectorAll(sel));

class AdminPanel {
    constructor() {
        this.init();
    }

    init() {
        this.setupModal();
        this.setupSearch();
        this.setupAlerts();
    }

    // === MODAL ===
    setupModal() {
        const modal = $(AdminConfig.selectors.modal);
        const openBtn = $(AdminConfig.selectors.openBtn);
        const cancelBtn = $(AdminConfig.selectors.cancelBtn);
        const form = $(AdminConfig.selectors.form);
        const title = $(AdminConfig.selectors.modalTitle);
        const idInput = $(AdminConfig.selectors.idInput);

        if (!modal || !openBtn || !form) return;

        openBtn.addEventListener('click', () => this.openAddModal(form, title, idInput, modal));
        if (cancelBtn) cancelBtn.addEventListener('click', () => this.closeModal(modal, form, idInput));
        modal.addEventListener('click', (e) => e.target === modal && this.closeModal(modal, form, idInput));

        document.addEventListener('click', (e) => {
            if (e.target.matches(AdminConfig.selectors.editBtn)) {
                this.openEditModal(e.target, form, title, idInput, modal);
            }
        });
    }

    openAddModal(form, title, idInput, modal) {
        form.action = form.dataset.addAction || form.action;
        if (title) title.textContent = `Add New ${this.getEntityType()}`;
        form.reset();
        if (idInput) idInput.value = "";
        this.clearFormFields(form);
        this.hideImagePreview();
        modal.classList.add('show');
    }

    openEditModal(btn, form, title, idInput, modal) {
        form.action = form.dataset.updateAction || form.action;
        if (title) title.textContent = `Edit ${this.getEntityType()}`;
        this.populateFormFromButton(btn, idInput);
        this.showImagePreview(btn);
        modal.classList.add('show');
    }

    closeModal(modal, form, idInput) {
        modal.classList.remove('show');
        form.reset();
        if (idInput) idInput.value = "";
    }

    clearFormFields(form) {
        $$('input[type="text"], input[type="number"], input[type="email"], input[type="tel"], textarea', form)
            .forEach(input => input.value = "");
        $$('select', form).forEach(select => select.selectedIndex = 0);
    }

    populateFormFromButton(btn, idInput) {
        const { dataset } = btn;
        if (idInput) idInput.value = dataset.carId || dataset.empId || dataset.userId || "";

        Object.entries(dataset).forEach(([key, val]) => {
            let fieldName = key.replace(/^(car|emp|user)/, '').toLowerCase();
            const field = document.getElementById(fieldName);
            if (!field) return;

            if (field.type === 'file') {
                const preview = $(`#${fieldName}-preview`);
                if (preview && val) preview.src = val;
                return;
            }
            if (val) field.value = val;
        });
    }

    hideImagePreview() {
        const wrapper = $('#imagePreviewWrapper');
        const img = $('#currentImage');
        if (wrapper) wrapper.style.display = 'none';
        if (img) img.src = '';
    }

    showImagePreview(btn) {
        const wrapper = $('#imagePreviewWrapper');
        const img = $('#currentImage');
        if (btn.dataset.image && img) {
            img.src = `${window.baseUrl || ''}/assets/images/uploads/${btn.dataset.image}`;
            if (wrapper) wrapper.style.display = 'block';
        } else {
            this.hideImagePreview();
        }
    }

    getEntityType() {
        if ($('.car-table')) return 'Car';
        if ($$('.admin-table th').some(th => th.textContent.toLowerCase().includes('department'))) return 'Employee';
        return 'User';
    }

    // === SEARCH ===
    setupSearch() {
        const searchBar = $(AdminConfig.selectors.searchBar);
        if (!searchBar) return;

        const searchBtn = $(AdminConfig.selectors.searchBtn);
        const filters = $$(AdminConfig.selectors.filterContainer + ' select');

        if (searchBtn) searchBtn.addEventListener('click', e => (e.preventDefault(), this.performFilter()));
        searchBar.addEventListener('input', () => {
            clearTimeout(searchBar.timeout);
            searchBar.timeout = setTimeout(() => this.performFilter(), AdminConfig.searchDelay);
        });
        filters.forEach(f => f.addEventListener('change', () => this.performFilter()));
        searchBar.focus();
    }

    performFilter() {
        const searchBar = $(AdminConfig.selectors.searchBar);
        const rows = $$(AdminConfig.selectors.tableRows);
        const filters = $$(AdminConfig.selectors.filterContainer + ' select');

        if (!searchBar || !rows.length) return;
        const searchText = searchBar.value.toLowerCase().trim();
        const filterValues = Object.fromEntries(filters.map(f => [f.id, f.value.toLowerCase()]));

        let visible = 0;
        rows.forEach(row => {
            if (row.classList.contains('no-results-row') || row.cells.length === 1) return;
            const shouldShow = this.matchesFilters(row, searchText, filterValues);
            row.style.display = shouldShow ? '' : 'none';
            if (shouldShow) visible++;
        });
        this.showNoResultsMessage(visible === 0);
    }

    matchesFilters(row, searchText, filters) {
        const text = this.getSearchableText(row).toLowerCase();
        const matchesSearch = !searchText || text.includes(searchText);
        return matchesSearch && Object.entries(filters).every(([id, val]) =>
            !val || this.checkFilterMatch(row, id, val)
        );
    }

    getSearchableText(row) {
        const info = row.querySelector('.employee-info');
        if (info) {
            return `${info.querySelector('.name')?.textContent || ''} ${info.querySelector('.email')?.textContent || ''} ${info.querySelector('.phone')?.textContent || ''}`;
        }
        return `${row.querySelector('.vin')?.textContent || ''} ${row.querySelector('.brand')?.textContent || ''} ${row.querySelector('.model')?.textContent || ''}`;
    }

    checkFilterMatch(row, id, val) {
        const map = {
            roleFilter: row.cells[1]?.querySelector('.status')?.textContent.toLowerCase(),
            statusFilter: row.cells[2]?.querySelector('.status')?.textContent.toLowerCase(),
            departmentFilter: row.cells[2]?.textContent.toLowerCase(),
            brandFilter: row.querySelector('.brand')?.textContent.toLowerCase()
        };
        return map[id] === val;
    }

    showNoResultsMessage(show) {
        let row = $('.no-results-row');
        if (show && !row) {
            const tbody = $(`${AdminConfig.selectors.tableContainer} tbody`);
            if (tbody) {
                row = document.createElement('tr');
                row.className = 'no-results-row';
                const cols = tbody.closest('table').querySelectorAll('thead tr th').length;
                row.innerHTML = `<td colspan="${cols}" style="text-align:center;padding:20px;font-style:italic;color:#666;">No items match your search criteria.</td>`;
                tbody.appendChild(row);
            }
        }
        if (row) row.style.display = show ? '' : 'none';
    }

    clearFilters() {
        const searchBar = $(AdminConfig.selectors.searchBar);
        const filters = $$(AdminConfig.selectors.filterContainer + ' select');
        if (searchBar) searchBar.value = '';
        filters.forEach(f => f.value = '');
        this.performFilter();
        if (searchBar) searchBar.focus();
    }

    // === ALERTS ===
    setupAlerts() {
    $$(AdminConfig.selectors.alerts).forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, AdminConfig.alertDelay);
    });
    }
}

// === FORM VALIDATOR ===
class FormValidator {
    constructor() {
        this.setupValidation();
    }

    setupValidation() {
        const form = $(AdminConfig.selectors.form);
        if (!form) return;
        form.addEventListener('submit', e => {
            const errors = this.validateForm(form);
            if (errors.length) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        });
    }

    validateForm(form) {
        const errors = [];
        const entity = this.getEntityType();
        const name = $('#name', form);
        const email = $('#email', form);
        const phone = $('#phone', form);

        if (name && name.value.trim().length < 2) errors.push('Name must be at least 2 characters.');
        if (email?.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) errors.push('Invalid email.');
        if (phone?.value.trim() && !/^\+?[0-9\s\-]{7,15}$/.test(phone.value)) errors.push('Invalid phone.');

        if (entity === 'Car') errors.push(...this.validateCar(form));
        if (entity === 'Employee') errors.push(...this.validateEmployee(form));
        return errors;
    }

    validateCar(form) {
        return ['vin', 'brand', 'model'].reduce((errs, id) => {
            const field = $(`#${id}`, form);
            if (field && !field.value.trim()) errs.push(`${id.toUpperCase()} is required.`);
            return errs;
        }, []);
    }

    validateEmployee(form) {
        return ['role', 'department', 'status'].reduce((errs, id) => {
            const field = $(`#${id}`, form);
            if (field && !field.value) errs.push(`Please select a ${id}.`);
            return errs;
        }, []);
    }

    getEntityType() {
        if ($('.car-table')) return 'Car';
        if ($('#department')) return 'Employee';
        return 'User';
    }
}

// === INIT ===
document.addEventListener('DOMContentLoaded', () => {
    new AdminPanel();
    new FormValidator();
});

function showConfirmation(message, callback) {
    if (confirm(message)) callback();
}
