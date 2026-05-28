{{--
    accessibility.blade.php
    Global accessibility widget component
    Provides: Font size, Contrast, Grayscale, Line height, Text spacing, Reset
    Storage: Browser localStorage (session-based)
--}}

<div id="accessibility-widget" class="accessibility-widget" role="region" aria-label="Menu Aksesibilitas">
    <!-- Floating Toggle Button -->
    <button id="accessibility-toggle" class="accessibility-toggle" aria-label="Buka Menu Aksesibilitas" aria-expanded="false" title="Menu Aksesibilitas (CTRL+U)">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" fill="currentColor"/>
        </svg>
    </button>

    <!-- Panel Container -->
    <div id="accessibility-panel" class="accessibility-panel" hidden>
        <!-- Header -->
        <div class="accessibility-header">
            <h2 class="accessibility-title">Menu Aksesibilitas</h2>
            <button id="accessibility-close" class="accessibility-close-btn" aria-label="Tutup Menu">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Controls Container -->
        <div class="accessibility-controls">
            <!-- Font Size Control -->
            <div class="accessibility-control-group">
                <label for="accessibility-font-size" class="control-label">Ukuran Teks</label>
                <div class="control-buttons">
                    <button id="font-decrease" class="control-btn control-btn-secondary" aria-label="Perkecil Teks">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <text x="2" y="18" font-size="16" font-family="Arial">A</text>
                            <line x1="6" y1="12" x2="14" y2="12" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        A-
                    </button>
                    <div class="font-size-display" id="font-size-display">100%</div>
                    <button id="font-increase" class="control-btn control-btn-secondary" aria-label="Perbesar Teks">
                        A+
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <text x="6" y="18" font-size="18" font-family="Arial">A</text>
                            <line x1="6" y1="12" x2="14" y2="12" stroke="currentColor" stroke-width="2"/>
                            <line x1="10" y1="8" x2="10" y2="16" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Line Height Control -->
            <div class="accessibility-control-group">
                <label for="accessibility-line-height" class="control-label">Tinggi Garis</label>
                <div class="control-buttons">
                    <button id="line-height-decrease" class="control-btn control-btn-secondary" aria-label="Kurangi Tinggi Garis">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <line x1="4" y1="6" x2="20" y2="6" stroke="currentColor" stroke-width="2"/>
                            <line x1="4" y1="12" x2="20" y2="12" stroke="currentColor" stroke-width="2"/>
                            <line x1="4" y1="18" x2="20" y2="18" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>
                    <div class="line-height-display" id="line-height-display">Normal</div>
                    <button id="line-height-increase" class="control-btn control-btn-secondary" aria-label="Tambah Tinggi Garis">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <line x1="4" y1="4" x2="20" y2="4" stroke="currentColor" stroke-width="2"/>
                            <line x1="4" y1="12" x2="20" y2="12" stroke="currentColor" stroke-width="2"/>
                            <line x1="4" y1="20" x2="20" y2="20" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Text Spacing Control -->
            <div class="accessibility-control-group">
                <label for="accessibility-letter-spacing" class="control-label">Spasi Teks</label>
                <div class="control-buttons">
                    <button id="letter-spacing-decrease" class="control-btn control-btn-secondary" aria-label="Kurangi Spasi Teks">
                        A A
                    </button>
                    <div class="letter-spacing-display" id="letter-spacing-display">Normal</div>
                    <button id="letter-spacing-increase" class="control-btn control-btn-secondary" aria-label="Tambah Spasi Teks">
                        A &nbsp; A
                    </button>
                </div>
            </div>

            <!-- Divider -->
            <div class="accessibility-divider"></div>

            <!-- Contrast Control -->
            <div class="accessibility-control-group">
                <label class="control-label">Kontras</label>
                <div class="control-buttons full-width">
                    <button id="contrast-normal" class="control-btn control-btn-toggle" data-contrast="normal" aria-pressed="true">
                        Normal
                    </button>
                    <button id="contrast-high" class="control-btn control-btn-toggle" data-contrast="high" aria-pressed="false">
                        Tinggi
                    </button>
                    <button id="contrast-dark" class="control-btn control-btn-toggle" data-contrast="dark" aria-pressed="false">
                        Gelap
                    </button>
                </div>
            </div>

            <!-- Grayscale Control -->
            <div class="accessibility-control-group">
                <label class="control-label">Warna</label>
                <div class="control-buttons full-width">
                    <button id="grayscale-off" class="control-btn control-btn-toggle" data-grayscale="off" aria-pressed="true">
                        Normal
                    </button>
                    <button id="grayscale-on" class="control-btn control-btn-toggle" data-grayscale="on" aria-pressed="false">
                        Skala Abu
                    </button>
                </div>
            </div>

            <!-- Divider -->
            <div class="accessibility-divider"></div>

            <!-- Reset Button -->
            <button id="accessibility-reset" class="control-btn control-btn-reset" aria-label="Atur Ulang Semua Pengaturan">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-8C4.13 2 1.73 3.12 0 4.99h5.84C4.36 3.89 5.64 3 7 3c3.31 0 6 2.69 6 6 0 .13-.01.26-.02.39h2.01c.02-.13.01-.26.01-.39 0-4.42-3.58-8-8-8zm10 8c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2zm0 7.99c1.87-1.87 3-4.46 3-7.29 0-.24-.02-.47-.04-.71h-2.02c.02.23.04.47.04.71 0 2.21-.9 4.2-2.34 5.65L19 18.82c1.1-1.1 1.76-2.46 1.93-3.96h-2.05c-.16 1.01-.63 1.94-1.32 2.69l-1.55-1.56zM2 13.99c0 .2.02.39.04.59h2.01c-.02-.19-.03-.38-.03-.59 0-2.77 1.61-5.17 3.95-6.39C6.72 6.84 6.38 6.43 6.07 6c-2.84 1.46-4.76 4.36-4.76 7.75 0 .6.06 1.19.16 1.77H2.08c-.06-.57-.09-1.15-.09-1.73l.01-.03z"/>
                </svg>
                Atur Ulang
            </button>
        </div>

        <!-- Footer -->
        <div class="accessibility-footer">
            <small>Versi 1.0</small>
        </div>
    </div>
</div>

<style>
    /* ===== ACCESSIBILITY WIDGET STYLES ===== */
    
    .accessibility-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: var(--font-body, 'Inter', system-ui, sans-serif);
    }

    /* Toggle Button */
    .accessibility-toggle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--navy, #003580);
        color: var(--white, #FFFFFF);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 53, 128, 0.25);
        transition: all 0.3s ease;
    }

    .accessibility-toggle:hover {
        background: var(--navy-mid, #004099);
        box-shadow: 0 6px 16px rgba(0, 53, 128, 0.35);
        transform: scale(1.05);
    }

    .accessibility-toggle:focus {
        outline: 3px solid var(--navy, #003580);
        outline-offset: 2px;
    }

    /* Panel */
    .accessibility-panel {
        position: absolute;
        bottom: 70px;
        right: 0;
        width: 320px;
        background: var(--white, #FFFFFF);
        border-radius: 8px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        border: 1px solid var(--border, #D8DCE3);
        display: flex;
        flex-direction: column;
        max-height: 600px;
        overflow-y: auto;
        animation: slideIn 0.3s ease;
    }

    .accessibility-panel[hidden] {
        display: none;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Header */
    .accessibility-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        border-bottom: 1px solid var(--border, #D8DCE3);
        background: var(--mist, #F4F5F7);
    }

    .accessibility-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--ink, #0A0F1A);
        margin: 0;
        letter-spacing: 0.01em;
    }

    .accessibility-close-btn {
        background: none;
        border: none;
        color: var(--mid, #6B7280);
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease;
    }

    .accessibility-close-btn:hover {
        color: var(--ink, #0A0F1A);
    }

    /* Controls Container */
    .accessibility-controls {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* Control Group */
    .accessibility-control-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .control-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--ink, #0A0F1A);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Control Buttons */
    .control-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .control-buttons.full-width {
        width: 100%;
    }

    .control-btn {
        padding: 8px 12px;
        border: 1px solid var(--border, #D8DCE3);
        border-radius: 4px;
        background: var(--white, #FFFFFF);
        color: var(--ink, #0A0F1A);
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .control-btn-secondary {
        flex: 1;
    }

    .control-btn-secondary:hover {
        background: var(--navy-tint, #E8EFF8);
        border-color: var(--navy, #003580);
    }

    .control-btn-toggle {
        flex: 1;
        background: var(--mist, #F4F5F7);
    }

    .control-btn-toggle[aria-pressed="true"] {
        background: var(--navy, #003580);
        color: var(--white, #FFFFFF);
        border-color: var(--navy, #003580);
    }

    .control-btn-toggle:hover {
        border-color: var(--navy, #003580);
    }

    .font-size-display,
    .line-height-display,
    .letter-spacing-display {
        font-size: 12px;
        color: var(--mid, #6B7280);
        text-align: center;
        flex: 1;
    }

    /* Divider */
    .accessibility-divider {
        height: 1px;
        background: var(--border, #D8DCE3);
        margin: 8px 0;
    }

    /* Reset Button */
    .control-btn-reset {
        width: 100%;
        padding: 10px;
        background: var(--navy, #003580);
        color: var(--white, #FFFFFF);
        border-color: var(--navy, #003580);
        font-weight: 600;
    }

    .control-btn-reset:hover {
        background: var(--navy-mid, #004099);
    }

    /* Footer */
    .accessibility-footer {
        padding: 8px 16px;
        text-align: center;
        border-top: 1px solid var(--border, #D8DCE3);
        background: var(--mist, #F4F5F7);
        font-size: 11px;
        color: var(--mid, #6B7280);
    }

    /* Responsive */
    @media (max-width: 480px) {
        .accessibility-panel {
            width: 280px;
            bottom: 60px;
            right: -10px;
        }
    }
</style>
