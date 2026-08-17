
<style>

:root {
    --fx-accent-glow: #2563eb;
    --fx-accent-light: #60a5fa;
    --fx-bg-curtain: rgba(9, 11, 16, 0.88);
}

.mxt-screen-curtain {
    position: fixed;
    inset: 0;
    width: 100vw;
    height: 100vh;
    background: var(--fx-bg-curtain);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    z-index: 999999;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.7s ease;
}

.q8-core-spinner {
    position: relative;
    width: 86px;
    height: 86px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.k3-halo-effect {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: conic-gradient(from 0deg, transparent 0%, var(--fx-accent-glow) 70%, #ffffff 100%);
    mask-image: radial-gradient(farthest-side, transparent calc(100% - 4px), #000 100%);
    -webkit-mask-image: radial-gradient(farthest-side, transparent calc(100% - 4px), #000 100%);
    animation: orbitSpin 1.1s linear infinite;
    filter: drop-shadow(0 0 12px rgba(37, 99, 235, 0.65));
}

.v9-center-node {
    width: 44px;
    height: 44px;
    background: radial-gradient(circle, var(--fx-accent-light) 0%, transparent 70%);
    border-radius: 50%;
    opacity: 0.8;
    animation: corePulse 1.8s ease-in-out infinite alternate;
}

.tx-status-wrap {
    margin-top: 28px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.tx-status-wrap .lbl-title {
    color: #ffffff;
    font-family: 'Poppins', sans-serif;
    font-size: 0.85rem;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 600;
    opacity: 0.9;
}

.vx-progress-bar {
    width: 140px;
    height: 2px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    overflow: hidden;
    position: relative;
}

.vx-progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 45%;
    background: linear-gradient(90deg, transparent, var(--fx-accent-light), transparent);
    animation: shimmerSlide 1.6s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes orbitSpin {
    100% {
        transform: rotate(360deg);
    }
}

@keyframes corePulse {
    0% {
        transform: scale(0.65);
        opacity: 0.3;
    }
    100% {
        transform: scale(1.15);
        opacity: 0.9;
    }
}

@keyframes shimmerSlide {
    0% {
        left: -50%;
    }
    100% {
        left: 100%;
    }
}

.d2-fade-out {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

</style>
<div id="mxt-screen-curtain" class="mxt-screen-curtain">
    <div class="q8-core-spinner">
        <div class="k3-halo-effect"></div>
        <div class="v9-center-node"></div>
    </div>

    <div class="tx-status-wrap">
        <span class="lbl-title">wolfstravel</span>
        <div class="vx-progress-bar"></div>
    </div>
</div>

<script>
    (function() {
        const curtainLayer = document.getElementById('mxt-screen-curtain');
        const minWaitMs = 2200;
        const startTime = Date.now();

        window.addEventListener('load', function() {
            const elapsed = Date.now() - startTime;
            const remaining = Math.max(0, minWaitMs - elapsed);

            setTimeout(() => {
                if (curtainLayer) {
                    curtainLayer.classList.add('d2-fade-out');
                    setTimeout(() => curtainLayer.remove(), 700);
                }
            }, remaining);
        });
    })();
</script>
