<style>

:root {
    --primary-color: #4f46e5;
    --primary-hover: #4338ca;
    --primary-light: #eef2ff;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --danger-light: #fef2f2;
    --text-color: #1e293b;
    --text-muted: #64748b;
    --border-color: #e2e8f0;
    --bg-light: #f8fafc;
    --bg-white: #ffffff;
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.04);
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --transition: all 0.2s ease;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    direction: rtl;
    background-color: #f1f5f9;
    color: var(--text-color);
}

* {
    box-sizing: border-box;
}

.premium-container {
    max-width: 900px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: var(--bg-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
}

@media (max-width: 768px) {
    .premium-container {
        margin: 1rem;
        padding: 1.5rem;
    }
}

.page-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.page-header h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-color);
    margin: 0;
}

.form-section {
    background: var(--bg-light);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.75rem;
    margin-bottom: 2rem;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-color);
    margin: 0 0 1.5rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border-color);
}

.section-title svg {
    color: var(--primary-color);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

@media (max-width: 640px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.full-width {
    grid-column: 1 / -1;
}

.form-label {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-color);
}

.required-star {
    color: var(--danger-color);
}

.form-control {
    padding: 0.7rem 0.9rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 0.95rem;
    transition: var(--transition);
    background: var(--bg-white);
    color: var(--text-color);
    width: 100%;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
}

textarea.form-control {
    resize: vertical;
}

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: left 0.75rem center;
    padding-left: 2.5rem;
}

.error-msg {
    font-size: 0.8rem;
    color: var(--danger-color);
    margin-top: 0.25rem;
    display: block;
}

.alert-error {
    background-color: var(--danger-light);
    color: var(--danger-color);
    padding: 0.75rem 1rem;
    border-radius: var(--radius-md);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    border: 1px solid #fecaca;
}

.alert-success {
    background-color: #ecfdf5;
    color: #065f46;
    padding: 0.75rem 1rem;
    border-radius: var(--radius-md);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    border: 1px solid #a7f3d0;
}

.input-group {
    display: flex;
    gap: 0.5rem;
}

.input-group .form-control {
    flex: 1;
}

.btn-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    width: 44px;
    height: 44px;
    color: var(--text-muted);
    cursor: pointer;
    transition: var(--transition);
}

.btn-icon:hover {
    background: var(--primary-light);
    color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.btn-primary:hover {
    background: var(--primary-hover);
    box-shadow: var(--shadow-md);
}

.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: var(--bg-white);
    color: var(--text-color);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: var(--transition);
}

.btn-secondary:hover {
    background: var(--bg-light);
}

.features-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.chip {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: 9999px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: var(--transition);
    color: var(--text-muted);
    user-select: none;
}

.chip:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
    background: var(--primary-light);
}

.chip.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.chip svg {
    width: 16px;
    height: 16px;
}

.custom-select-trigger {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
    cursor: pointer;
    min-height: 44px;
    padding: 0.5rem 0.9rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background: var(--bg-white);
}

.custom-select-placeholder {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.selected-category-chip {
    display: inline-flex;
    align-items: center;
    background: var(--primary-light);
    color: var(--primary-color);
    border-radius: 9999px;
    padding: 0.25rem 0.75rem;
    font-size: 0.8rem;
    gap: 0.5rem;
}

.remove-category-btn {
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    color: var(--primary-color);
    padding: 0;
}

.custom-select-dropdown {
    margin-top: 0.5rem;
    max-height: 220px;
    overflow-y: auto;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background: var(--bg-white);
    box-shadow: var(--shadow-sm);
}

.category-option-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1rem;
    cursor: pointer;
    transition: background 0.15s;
    font-size: 0.9rem;
    border-bottom: 1px solid #f1f5f9;
}

.category-option-item:last-child {
    border-bottom: none;
}

.category-option-item:hover {
    background: var(--bg-light);
}

.child-item {
    padding-right: 2.5rem;
}

.category-checkbox {
    accent-color: var(--primary-color);
    width: 18px;
    height: 18px;
}

.modern-dropzone {
    border: 2px dashed var(--border-color);
    border-radius: var(--radius-lg);
    padding: 2rem;
    text-align: center;
    background: var(--bg-light);
    transition: var(--transition);
    cursor: pointer;
    position: relative;
}

.modern-dropzone.dragover {
    border-color: var(--primary-color);
    background: var(--primary-light);
}

.dropzone-icon {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.dropzone-text {
    font-weight: 500;
    color: var(--text-color);
    margin: 0.5rem 0 0.25rem;
}

.dropzone-text span {
    color: var(--primary-color);
    text-decoration: underline;
    cursor: pointer;
}

.dropzone-hint {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.file-input-hidden {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}

.image-preview-area {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 1rem;
}

.preview-box {
    width: 90px;
    height: 90px;
    border-radius: var(--radius-md);
    overflow: hidden;
    border: 1px solid var(--border-color);
}

.preview-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.existing-images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
    margin-top: 0.5rem;
}

.image-item {
    position: relative;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    overflow: hidden;
    background: var(--bg-white);
}

.image-item img {
    width: 100%;
    height: 100px;
    object-fit: cover;
}

.image-item .remove-check {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: var(--danger-color);
    color: white;
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.8rem;
    transition: var(--transition);
    opacity: 0.8;
}

.image-item .remove-check:hover {
    opacity: 1;
    transform: scale(1.1);
}

.image-item .remove-check input {
    display: none;
}

.image-item .remove-check.selected {
    background: var(--success-color);
}

.map-container {
    width: 100%;
    height: 300px;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-color);
    margin-top: 0.5rem;
    z-index: 1;
}

.map-hint {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-top: 0.5rem;
}

.map-hint svg {
    color: var(--primary-color);
}

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s, visibility 0.3s;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: var(--bg-white);
    padding: 2rem;
    border-radius: var(--radius-lg);
    width: 90%;
    max-width: 450px;
    box-shadow: var(--shadow-lg);
}

.modal-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 1.5rem 0;
    color: var(--text-color);
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1.5rem;
}

.search-results-box {
    max-height: 200px;
    overflow-y: auto;
    margin-top: 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background: var(--bg-white);
    display: none;
}

.search-results-box.show {
    display: block;
}

.search-item {
    padding: 0.75rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    transition: background 0.15s;
    border-bottom: 1px solid var(--border-color);
}

.search-item:last-child {
    border-bottom: none;
}

.search-item:hover {
    background: var(--primary-light);
}

.country-name {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.spacing-mt-1_5 {
    margin-top: 1.5rem;
}
.spacing-mt-2_5 {
    margin-top: 2.5rem;
}
</style>