<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

.container {
    background-color: #F9F9F9;
    margin: 0 auto;
    padding: 5px 15px;
}

.section-header {
    text-align: center;
    margin: 0 auto 50px auto;
}

.subtitle {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: #041635;
    letter-spacing: 0.8px;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.section-header h2 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 15px;
    color: #041635;
}

.section-header p {
    font-size: 13px;
    color: #4a5568;
    line-height: 1.6;
}

.steps-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    position: relative;
    gap: 20px;
}

.dashed-line {
    position: absolute;
    top: 42px;
    left: 0;
    width: 100%;
    height: 75px;
    z-index: 0;
    pointer-events: none;
}

.dashed-line svg {
    width: 100%;
    height: 100%;
}

.step-card {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    z-index: 1;
}

.icon-wrapper {
    position: relative;
    width: 90px;
    height: 90px;
    margin-bottom: 25px;
}

.bg-shape {
    position: absolute;
    top: 9px;
    left: 9px;
    width: 100%;
    height: 100%;
    background-color: #eff2f6;
    border-radius: 9px 34px 9px 34px;
    z-index: 1;
    transition: all 0.3s ease;
}

.main-shape {
    position: relative;
    width: 100%;
    height: 100%;
    background-color: #ffffff;
    border-radius: 9px 34px 9px 34px;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 7px 18px rgba(0, 0, 0, 0.04);
    z-index: 2;
    transition: all 0.3s ease;
    color: #041635;
}

.step-number {
    position: absolute;
    top: -9px;
    right: -9px;
    width: 30px;
    height: 30px;
    background-color: #ffffff;
    border: 2px solid #e0e4eb;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 11px;
    font-weight: 600;
    color: #8a94a6;
    transition: all 0.3s ease;
    z-index: 3;
}

.step-icon {
    width: 34px;
    height: 34px;
    transition: all 0.3s ease;
}

.step-content h3 {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 9px;
    color: #041635;

}

.step-content p {
    font-size: 12px;
    color: #4a5568;
    line-height: 1.6;
    max-width: 200px;
    margin: 0 auto;
}

.step-card:hover .main-shape {
    background-color: #041635;
    color: #ffffff;
    transform: translateY(-4px);
}

.step-card:hover .bg-shape {
    opacity: 0;
}

.step-card:hover .step-number {
    border-color: #041635;
    color: #041635;
    background-color: #ffffff;
}

@media (max-width: 768px) {
    .section-header h2 {
        font-size: 22px;
    }

    .steps-container {
        flex-direction: column;
        align-items: center;
        gap: 35px;
    }

    .dashed-line {
        display: none;
    }

    .step-card {
        width: 100%;
        max-width: 260px;
    }

    .step-content p {
        max-width: 100%;
    }
}
</style>
<section class="how-it-works-section" data-aos="fade-up" data-aos-duration="800" data-aos-delay="0">
    <div class="container">

        <div class="section-header" data-aos="fade-down" data-aos-duration="600">
            <span class="subtitle">{{ __('messages.how_it_works_subtitle') }}</span>
            <h2>{{ __('messages.how_it_works_title') }}</h2>
            <p>{{ __('messages.how_it_works_description') }}</p>
        </div>

        <div class="steps-container">

            <div class="dashed-line" data-aos="fade" data-aos-delay="300" data-aos-duration="800">
                <svg viewBox="0 0 1000 100" preserveAspectRatio="none">
                    <path d="M 50 50 Q 250 10 500 50 T 950 50" fill="none" stroke="#e0e4eb" stroke-width="2" stroke-dasharray="8,8"></path>
                    <circle cx="20" cy="50" r="4" fill="#d1d5db" />
                    <circle cx="980" cy="50" r="4" fill="#d1d5db" />
                    <path d="M 975 45 L 982 50 L 975 55" fill="none" stroke="#d1d5db" stroke-width="2" />
                </svg>
            </div>

            <div class="step-card" data-aos="flip-left" data-aos-delay="200" data-aos-duration="700">
                <div class="icon-wrapper">
                    <div class="bg-shape"></div>
                    <div class="main-shape">
                        <span class="step-number">01</span>
                        <svg class="step-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                            <path d="M12 23l-4-4 4-4 4 4-4 4z" fill="currentColor"></path>
                        </svg>
                    </div>
                </div>
                <div class="step-content">
                    <h3>{{ __('messages.step_1_title') }}</h3>
                    <p>{{ __('messages.step_1_description') }}</p>
                </div>
            </div>

            <div class="step-card" data-aos="zoom-in-up" data-aos-delay="400" data-aos-duration="700">
                <div class="icon-wrapper">
                    <div class="bg-shape"></div>
                    <div class="main-shape">
                        <span class="step-number">02</span>
                        <svg class="step-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <path d="M9 16l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
                <div class="step-content">
                    <h3>{{ __('messages.step_2_title') }}</h3>
                    <p>{{ __('messages.step_2_description') }}</p>
                </div>
            </div>

            <div class="step-card" data-aos="flip-right" data-aos-delay="600" data-aos-duration="700">
                <div class="icon-wrapper">
                    <div class="bg-shape"></div>
                    <div class="main-shape">
                        <span class="step-number">03</span>
                        <svg class="step-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            <path d="M5 5l2 2M19 5l-2 2M12 2v3"></path>
                        </svg>
                    </div>
                </div>
                <div class="step-content">
                    <h3>{{ __('messages.step_3_title') }}</h3>
                    <p>{{ __('messages.step_3_description') }}</p>
                </div>
            </div>

        </div>
    </div>
</section>