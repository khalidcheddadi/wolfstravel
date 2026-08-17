@extends('layouts.business-owner')

@section('content')

@include('business-owner.listings.createStyle')


<div class="premium-container" x-data="listingForm()">
    <header class="page-header">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--primary-color)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="12" y1="8" x2="12" y2="16"></line>
            <line x1="8" y1="12" x2="16" y2="12"></line>
        </svg>
        <h1>{{ __('messages.add_new_activity') }}</h1>
    </header>

    <div x-show="generalError" x-transition class="alert-error" style="display:none;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="15" x2="9" y2="9"></line><line x1="9" y1="15" x2="15" y2="9"></line></svg>
        <span x-text="generalErrorMessage"></span>
    </div>

    <form @submit.prevent="submitForm" enctype="multipart/form-data">
        @csrf

        <div class="form-section">
            <h2 class="section-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                {{ __('messages.basic_info') }}
            </h2>

            <div class="form-grid">
                <div class="form-group">
                    <label for="listing_type_id" class="form-label">{{ __('messages.activity_type') }} <span class="required-star">*</span></label>
                    <select name="listing_type_id" id="listing_type_id" class="form-control" x-model="form.listing_type_id" @change="validateField('listing_type_id')">
                        <option value="">{{ __('messages.select_activity_type') }}</option>
                        @foreach($listingTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <span class="error-msg" x-show="errors.listing_type_id" x-text="errors.listing_type_id"></span>
                </div>

                <div class="form-group">
                    <label for="title" class="form-label">{{ __('messages.activity_title') }} <span class="required-star">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="{{ __('messages.enter_activity_title') }}" x-model="form.title" @input.debounce.150="validateField('title')" required>
                    <span class="error-msg" x-show="errors.title" x-text="errors.title"></span>
                </div>

                <div class="form-group full-width">
                    <label for="short_description" class="form-label">{{ __('messages.short_description') }}</label>
                    <textarea name="short_description" id="short_description" rows="2" class="form-control" placeholder="{{ __('messages.short_description_placeholder') }}" x-model="form.short_description"></textarea>
                    <span class="error-msg" x-show="errors.short_description" x-text="errors.short_description"></span>
                </div>

                <div class="form-group full-width">
                    <label for="description" class="form-label">{{ __('messages.description') }} <span class="required-star">*</span></label>
                    <textarea name="description" id="description" rows="5" class="form-control" placeholder="{{ __('messages.description_placeholder') }}" x-model="form.description" @input.debounce.150="validateField('description')" required></textarea>
                    <span class="error-msg" x-show="errors.description" x-text="errors.description"></span>
                </div>

                <div class="form-group full-width"
                     x-data="categorySelector()">
                    <label class="form-label">{{ __('messages.categories') }} <span class="required-star">*</span></label>

                    <div class="custom-select-trigger" @click="open = !open">
                        <template x-if="selectedCats.length === 0">
                            <span class="custom-select-placeholder">{{ __('messages.select_categories_placeholder') }}</span>
                        </template>
                        <template x-for="cat in selectedCats" :key="cat.id">
                            <span class="selected-category-chip" @click.stop>
                                <span x-text="cat.name"></span>
                                <button type="button" class="remove-category-btn" @click="remove(cat.id, $event)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            </span>
                        </template>
                        <div style="margin-right: auto; color: #94a3b8;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                    </div>

                    <div class="custom-select-dropdown"
                         x-show="open"
                         x-transition
                         @click.outside="open = false">
                        @foreach($categories as $category)
                            <label class="category-option-item">
                                <input type="checkbox" class="category-checkbox" value="{{ $category->id }}" @change="toggle('{{ $category->id }}', '{{ $category->name }}', $event)">
                                <span>{{ $category->name }}</span>
                            </label>
                            @if($category->children->count())
                                @foreach($category->children as $child)
                                    <label class="category-option-item child-item">
                                        <input type="checkbox" class="category-checkbox" value="{{ $child->id }}" @change="toggle('{{ $child->id }}', '{{ $child->name }}', $event)">
                                        <span>{{ $child->name }}</span>
                                    </label>
                                @endforeach
                            @endif
                        @endforeach
                    </div>

                    <span class="error-msg" x-show="errors.category_ids" x-text="errors.category_ids"></span>
                    <input type="hidden" name="category_ids_json" :value="JSON.stringify(selectedCats.map(c => c.id))">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2 class="section-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                {{ __('messages.location') }}
            </h2>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.country') }} <span class="required-star">*</span></label>
                    <div class="input-group">
                        <select name="country_id" id="country_id" class="form-control" x-model="form.country_id" @change="validateField('country_id')">
                            <option value="">{{ __('messages.select_country') }}</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" id="addCountryBtn" class="btn-icon" title="{{ __('messages.add_country') }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        </button>
                    </div>
                    <span class="error-msg" x-show="errors.country_id" x-text="errors.country_id"></span>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('messages.city') }} <span class="required-star">*</span></label>
                    <div class="input-group">
                        <select name="city_id" id="city_id" class="form-control" x-model="form.city_id" @change="validateField('city_id')">
                            <option value="">{{ __('messages.select_city') }}</option>
                        </select>
                        <button type="button" id="searchCityBtn" class="btn-icon" title="{{ __('messages.search_city') }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                    </div>
                    <span class="error-msg" x-show="errors.city_id" x-text="errors.city_id"></span>
                </div>

                <div class="form-group full-width">
                    <label for="address" class="form-label">{{ __('messages.address') }}</label>
                    <input type="text" name="address" id="address" class="form-control" placeholder="{{ __('messages.address_placeholder') }}" x-model="form.address">
                </div>

                <div class="form-group full-width">
                    <label class="form-label">{{ __('messages.map_label') }}</label>
                    <div id="map" class="map-container"></div>
                    <div class="map-hint">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        {{ __('messages.map_hint') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="latitude" class="form-label">{{ __('messages.latitude') }}</label>
                    <input type="number" step="0.0000001" name="latitude" id="latitude" x-model="form.latitude" class="form-control">
                </div>

                <div class="form-group">
                    <label for="longitude" class="form-label">{{ __('messages.longitude') }}</label>
                    <input type="number" step="0.0000001" name="longitude" id="longitude" x-model="form.longitude" class="form-control">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h2 class="section-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                {{ __('messages.features_media') }}
            </h2>

            <div class="form-group full-width spacing-mt-1_5" x-data="featureSelector()">
                <label class="form-label">{{ __('messages.features') }} <span class="required-star">*</span></label>
                <div class="features-wrapper">
                    <template x-for="feature in features" :key="feature.id">
                        <button type="button"
                                @click="toggleFeature(feature.id); $dispatch('feature-toggled')"
                                class="chip"
                                :class="isSelected(feature.id) ? 'active' : ''">
                            <template x-if="isSelected(feature.id)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </template>
                            <span x-text="feature.name"></span>
                        </button>
                    </template>
                </div>
                <template x-for="id in selectedIds" :key="id">
                    <input type="hidden" name="features[]" :value="id">
                </template>
                <span class="error-msg" x-show="errors.features" x-text="errors.features"></span>
            </div>

            <div class="form-group full-width spacing-mt-1_5">
                <label class="form-label">حالة النشاط</label>
                <select name="availability_status" class="form-control" style="max-width: 260px;">
                    <option value="">بدون تأكيد</option>
                    <option value="open">مفتوح الآن</option>
                    <option value="closed">مغلق الآن</option>
                </select>
            </div>

            <div class="form-group full-width spacing-mt-1_5">
                <label class="form-label">{{ __('messages.image_gallery') }}</label>
                <div class="modern-dropzone" id="dropzoneArea"
                     ondragover="this.classList.add('dragover'); event.preventDefault();"
                     ondragleave="this.classList.remove('dragover');"
                     ondrop="this.classList.remove('dragover');">

                    <div id="dropzoneText">
                        <svg class="dropzone-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        <p class="dropzone-text">{{ __('messages.dropzone_text') }}</p>
                        <p class="dropzone-hint">{{ __('messages.dropzone_hint') }}</p>
                    </div>

                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="file-input-hidden">
                </div>
                <div class="image-preview-area" id="imagePreviewContainer"></div>
                <span class="error-msg" x-show="errors.images" x-text="errors.images"></span>
            </div>
        </div>

        <div class="form-group spacing-mt-2_5">
            <button type="submit" class="btn-primary" :disabled="isSubmitting">
                <template x-if="isSubmitting">
                    <svg class="spinner" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path></svg>
                </template>
                <template x-if="!isSubmitting">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                </template>
                <span x-text="isSubmitting ? '{{ __('messages.saving') }}' : '{{ __('messages.save_and_add') }}'"></span>
            </button>
        </div>
    </form>
</div>

<div id="countryModal" class="modal-overlay">
    <div class="modal-content">
        <h3 class="modal-header">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary-color)" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            {{ __('messages.add_country_modal_title') }}
        </h3>
        <div class="form-group">
            <label class="form-label">{{ __('messages.country_name') }}</label>
            <input type="text" id="newCountryName" class="form-control" placeholder="{{ __('messages.country_name_placeholder') }}">
        </div>
        <div class="modal-actions">
            <button type="button" id="closeCountryModal" class="btn-secondary">{{ __('messages.cancel') }}</button>
            <button type="button" id="saveCountryBtn" class="btn-primary" style="padding: 0.6rem 1.5rem;">{{ __('messages.save_country') }}</button>
        </div>
    </div>
</div>

<div id="searchCityModal" class="modal-overlay">
    <div class="modal-content">
        <h3 class="modal-header">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary-color)" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            {{ __('messages.search_city_modal_title') }}
        </h3>
        <div class="form-group">
            <label class="form-label">{{ __('messages.search_city_modal_label') }}</label>
            <input type="text" id="searchCityInput" class="form-control" placeholder="{{ __('messages.search_city_modal_placeholder') }}">
        </div>
        <div id="searchResults" class="search-results-box"></div>
        <div class="modal-actions">
            <button type="button" id="closeSearchCityModal" class="btn-secondary">{{ __('messages.cancel') }}</button>
            <button type="button" id="searchCityApiBtn" class="btn-primary" style="padding: 0.6rem 1.5rem;">{{ __('messages.start_search') }}</button>
        </div>
    </div>
</div>

<script>
    window.categorySelector = function() {
        return {
            open: false,
            selectedCats: [],
            toggle(id, name, event) {
                if (event.target.checked) {
                    this.selectedCats.push({ id: id, name: name });
                } else {
                    this.selectedCats = this.selectedCats.filter(cat => cat.id !== id);
                }
                this.$dispatch('category-changed');
            },
            remove(id, event) {
                this.selectedCats = this.selectedCats.filter(cat => cat.id !== id);
                const checkbox = document.querySelector(`input.category-checkbox[value="${id}"]`);
                if (checkbox) checkbox.checked = false;
                this.$dispatch('category-changed');
            }
        };
    };

    window.featureSelector = function() {
        return {
            features: @json($features),
            selectedIds: [],
            toggleFeature(id) {
                const index = this.selectedIds.indexOf(id);
                if (index > -1) {
                    this.selectedIds.splice(index, 1);
                } else {
                    this.selectedIds.push(id);
                }
                this.$dispatch('feature-toggled');
            },
            isSelected(id) {
                return this.selectedIds.includes(id);
            }
        };
    };

    window.listingForm = function() {
        return {
            form: {
                listing_type_id: '',
                title: '',
                short_description: '',
                description: '',
                country_id: '',
                city_id: '',
                address: '',
                latitude: {{ old('latitude', 40.4168) }},
                longitude: {{ old('longitude', -3.7038) }},
            },
            errors: {},
            isSubmitting: false,
            generalError: false,
            generalErrorMessage: '',

            validateField(field) {
                delete this.errors[field];
                const required = ['listing_type_id', 'title', 'description', 'country_id', 'city_id'];
                if (required.includes(field) && !this.form[field]) {
                    this.errors[field] = '{{ __("messages.required_field") }}';
                }
                if (field === 'description' && this.form.description && this.form.description.length < 10) {
                    this.errors.description = '{{ __("messages.description_min_length") }}';
                }
                this.validateCategoriesAndFeatures();
            },

            validateCategoriesAndFeatures() {
                const categoryJson = document.querySelector('input[name="category_ids_json"]');
                if (categoryJson) {
                    try {
                        const ids = JSON.parse(categoryJson.value || '[]');
                        if (ids.length === 0) {
                            this.errors.category_ids = '{{ __("messages.select_at_least_one_category") }}';
                        } else {
                            delete this.errors.category_ids;
                        }
                    } catch (e) {
                        this.errors.category_ids = '{{ __("messages.invalid_category_data") }}';
                    }
                }
                const featureInputs = document.querySelectorAll('input[name="features[]"]');
                if (featureInputs.length === 0) {
                    this.errors.features = '{{ __("messages.select_at_least_one_feature") }}';
                } else {
                    delete this.errors.features;
                }
            },

            validateForm() {
                ['listing_type_id', 'title', 'description', 'country_id', 'city_id'].forEach(field => {
                    this.validateField(field);
                });
                this.validateCategoriesAndFeatures();
                return Object.keys(this.errors).length === 0;
            },

            submitForm() {
                this.generalError = false;
                this.generalErrorMessage = '';
                if (!this.validateForm()) {
                    const firstError = document.querySelector('.error-msg:not(:empty)');
                    if (firstError) firstError.closest('.form-group')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }
                this.isSubmitting = true;

                const formData = new FormData();
                formData.append('listing_type_id', this.form.listing_type_id);
                formData.append('title', this.form.title);
                formData.append('short_description', this.form.short_description || '');
                formData.append('description', this.form.description);
                formData.append('country_id', this.form.country_id);
                formData.append('city_id', this.form.city_id);
                formData.append('address', this.form.address || '');
                formData.append('latitude', this.form.latitude);
                formData.append('longitude', this.form.longitude);

                const categoryJson = document.querySelector('input[name="category_ids_json"]');
                if (categoryJson) {
                    try {
                        const ids = JSON.parse(categoryJson.value || '[]');
                        ids.forEach(id => formData.append('category_ids[]', id));
                    } catch (e) {}
                }
                document.querySelectorAll('input[name="features[]"]').forEach(el => {
                    formData.append('features[]', el.value);
                });

                const imageInput = document.getElementById('images');
                if (imageInput && imageInput.files) {
                    Array.from(imageInput.files).forEach(file => formData.append('images[]', file));
                }

                fetch('{{ route("business-owner.listings.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw { status: response.status, data };
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route("business-owner.listings.index") }}';
                    } else {
                        this.generalError = true;
                        this.generalErrorMessage = data.message || '{{ __("messages.unexpected_error") }}';
                    }
                })
                .catch(error => {
                    if (error.status === 422) {
                        this.errors = error.data.errors || {};
                        if (!this.errors.category_ids) this.validateCategoriesAndFeatures();
                        const firstError = document.querySelector('.error-msg:not(:empty)');
                        if (firstError) firstError.closest('.form-group')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        this.generalError = true;
                        this.generalErrorMessage = error.data?.message || '{{ __("messages.server_error") }}';
                    }
                })
                .finally(() => {
                    this.isSubmitting = false;
                });
            }
        };
    };
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('images');
        const previewContainer = document.getElementById('imagePreviewContainer');

        if (imageInput) {
            imageInput.addEventListener('change', function() {
                previewContainer.innerHTML = '';
                const files = this.files;
                const maxFiles = Math.min(files.length, 10);
                for (let i = 0; i < maxFiles; i++) {
                    const file = files[i];
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewBox = document.createElement('div');
                            previewBox.className = 'preview-box';
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = 'Preview';
                            previewBox.appendChild(img);
                            previewContainer.appendChild(previewBox);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('category-changed', function() {
            const formComponent = document.querySelector('[x-data="listingForm()"]')?.__x;
            if (formComponent && formComponent.validateCategoriesAndFeatures) {
                formComponent.validateCategoriesAndFeatures();
            }
        });
        document.addEventListener('feature-toggled', function() {
            const formComponent = document.querySelector('[x-data="listingForm()"]')?.__x;
            if (formComponent && formComponent.validateCategoriesAndFeatures) {
                formComponent.validateCategoriesAndFeatures();
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const countrySelect = document.getElementById('country_id');
        const citySelect = document.getElementById('city_id');
        const addCountryBtn = document.getElementById('addCountryBtn');
        const searchCityBtn = document.getElementById('searchCityBtn');

        const countryModal = document.getElementById('countryModal');
        const searchCityModal = document.getElementById('searchCityModal');
        const closeCountryModal = document.getElementById('closeCountryModal');
        const closeSearchCityModal = document.getElementById('closeSearchCityModal');

        const newCountryName = document.getElementById('newCountryName');
        const saveCountryBtn = document.getElementById('saveCountryBtn');
        const searchCityInput = document.getElementById('searchCityInput');
        const searchCityApiBtn = document.getElementById('searchCityApiBtn');
        const searchResults = document.getElementById('searchResults');

        function loadCities(countryId) {
            citySelect.innerHTML = '<option value="">{{ __("messages.select_city") }}</option>';
            if (countryId) {
                fetch(`/ajax/cities/${countryId}`)
                    .then(response => response.json())
                    .then(cities => {
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });
                    })
                    .catch(err => console.error('خطأ في جلب المدن:', err));
            }
        }

        countrySelect.addEventListener('change', function () { loadCities(this.value); });
        if (countrySelect.value) { loadCities(countrySelect.value); }

        addCountryBtn.addEventListener('click', () => {
            countryModal.classList.add('show');
            newCountryName.value = '';
            setTimeout(() => newCountryName.focus(), 100);
        });

        closeCountryModal.addEventListener('click', () => countryModal.classList.remove('show'));

        saveCountryBtn.addEventListener('click', function () {
            const name = newCountryName.value.trim();
            if (!name) return alert('{{ __("messages.enter_country_name") }}');
            fetch('{{ route("ajax.countries.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: name })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const option = document.createElement('option');
                    option.value = data.country.id;
                    option.textContent = data.country.name;
                    countrySelect.appendChild(option);
                    countrySelect.value = data.country.id;
                    loadCities(data.country.id);
                    countryModal.classList.remove('show');
                } else {
                    alert('{{ __("messages.unexpected_error") }}: ' + (data.message || '{{ __("messages.unknown_error") }}'));
                }
            })
            .catch(err => alert('{{ __("messages.server_error") }}'));
        });

        searchCityBtn.addEventListener('click', () => {
            if (!countrySelect.value) {
                alert('{{ __("messages.select_country_first") }}');
                return;
            }
            searchCityModal.classList.add('show');
            searchCityInput.value = '';
            searchResults.innerHTML = '';
            searchResults.classList.remove('show');
            setTimeout(() => searchCityInput.focus(), 100);
        });

        closeSearchCityModal.addEventListener('click', () => searchCityModal.classList.remove('show'));

        function performCitySearch() {
            const query = searchCityInput.value.trim();
            if (query.length < 2) return alert('{{ __("messages.enter_at_least_two_chars") }}');
            const countryId = countrySelect.value;
            const countryName = countrySelect.options[countrySelect.selectedIndex]?.text || '';
            let url = `{{ route('ajax.cities.search') }}?q=${encodeURIComponent(query)}`;
            if (countryName && countryName !== '{{ __("messages.select_country") }}') {
                url += `&country_code=${encodeURIComponent(countryName)}`;
            }
            searchResults.innerHTML = '<div style="padding:1rem;text-align:center;color:var(--text-muted)">{{ __("messages.searching") }}</div>';
            searchResults.classList.add('show');
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    if (data.length === 0) {
                        searchResults.innerHTML = '<div style="padding:1rem;text-align:center;color:var(--text-muted)">{{ __("messages.no_results_found") }}</div>';
                        return;
                    }
                    data.forEach(city => {
                        const div = document.createElement('div');
                        div.className = 'search-item';
                        div.innerHTML = `<span>${city.name}</span><span class="country-name">${city.country}</span>`;
                        div.addEventListener('click', function () {
                            fetch('{{ route("ajax.cities.store") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    name: city.name,
                                    country_id: countryId,
                                    latitude: city.latitude,
                                    longitude: city.longitude,
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    const option = document.createElement('option');
                                    option.value = data.city.id;
                                    option.textContent = data.city.name;
                                    citySelect.appendChild(option);
                                    citySelect.value = data.city.id;
                                    searchCityModal.classList.remove('show');
                                } else {
                                    alert('{{ __("messages.unexpected_error") }}: ' + (data.message || '{{ __("messages.unknown_error") }}'));
                                }
                            });
                        });
                        searchResults.appendChild(div);
                    });
                });
        }

        searchCityApiBtn.addEventListener('click', performCitySearch);
        searchCityInput.addEventListener('keydown', e => { if (e.key === 'Enter') performCitySearch(); });

        [countryModal, searchCityModal].forEach(modal => {
            modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('show'); });
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                countryModal.classList.remove('show');
                searchCityModal.classList.remove('show');
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof L === 'undefined') {
            console.error('❌ Leaflet غير محمّل.');
            return;
        }

        const mapContainer = document.getElementById('map');
        if (!mapContainer) return;

        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        if (!latInput || !lngInput) return;

        const lat = parseFloat(latInput.value) || 40.4168;
        const lng = parseFloat(lngInput.value) || -3.7038;

        const map = L.map('map').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        function updateCoords(newLat, newLng) {
            latInput.value = newLat.toFixed(7);
            lngInput.value = newLng.toFixed(7);

            latInput.dispatchEvent(new Event('input', { bubbles: true }));
            lngInput.dispatchEvent(new Event('input', { bubbles: true }));

            console.log('✅ تم تحديث الإحداثيات:', newLat, newLng);
        }

        marker.on('dragend', function() {
            const pos = marker.getLatLng();
            updateCoords(pos.lat, pos.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
        });

        console.log('✅ الخريطة جاهزة!');
    });
</script>

@endsection