<style>
/* ============================================================
   SECAW COMMON HEADER & BREADCRUMB
   ============================================================ */
.secaw-header {
    background: var(--ink);
    padding: 48px 0 40px;
    position: relative;
    overflow: hidden;
}

.secaw-header .container {
    position: relative;
    z-index: 1;
}

.secaw-header__eyebrow {
    font-family: var(--font-body);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #D6E4F8;
    margin-bottom: 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.secaw-header h1 {
    font-family: var(--font-display);
    font-size: clamp(28px, 4.5vw, 48px);
    font-weight: 800;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: var(--white);
    line-height: 1.1;
    margin: 0 0 10px;
}

.secaw-header__sub {
    font-size: 14.5px;
    font-weight: 300;
    color: rgba(255, 255, 255, 0.75);
    line-height: 1.6;
    max-width: 720px;
    margin: 0;
}

/* ============================================================
   BREADCRUMB & SUBNAV BAR
   ============================================================ */
.secaw-nav-container {
    background: var(--white);
    border-bottom: 2px solid var(--border);
    position: sticky;
    top: 68px;
    z-index: 99;
}

.secaw-breadcrumb {
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    font-size: 12.5px;
}

.secaw-breadcrumb__list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    color: var(--mid);
}

.secaw-breadcrumb__list a {
    color: var(--navy);
    text-decoration: none;
    font-weight: 600;
}

.secaw-breadcrumb__list a:hover {
    text-decoration: underline;
}

.secaw-breadcrumb__list i {
    font-size: 10px;
    color: var(--border);
}

.secaw-breadcrumb__list [aria-current="page"] {
    color: var(--ink);
    font-weight: 700;
}

.secaw-subnav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    overflow-x: auto;
    scrollbar-width: thin;
}

.secaw-subnav__list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}

.secaw-subnav__link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 16px;
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    color: var(--mid);
    text-decoration: none;
    border-bottom: 3px solid transparent;
    transition: color var(--ease), border-color var(--ease), background var(--ease);
}

.secaw-subnav__link:hover {
    color: var(--navy);
    background: var(--navy-tint);
}

.secaw-subnav__link.active {
    color: var(--navy);
    border-bottom-color: var(--navy);
    background: var(--navy-tint);
    font-weight: 800;
}

.secaw-subnav__link:focus-visible {
    outline: 2px solid var(--navy);
    outline-offset: -2px;
}

.secaw-subnav__ext {
    font-size: 12px;
    font-weight: 600;
    color: var(--navy);
    text-decoration: none;
    padding: 6px 12px;
    border: 1px solid var(--border);
    background: var(--mist);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
    transition: background var(--ease), border-color var(--ease);
}

.secaw-subnav__ext:hover {
    background: var(--navy-tint);
    border-color: var(--navy);
    color: var(--navy);
}

/* ============================================================
   MAIN CONTENT SHELL
   ============================================================ */
.secaw-shell {
    background: var(--mist);
    padding: 40px 0 72px;
    min-height: calc(100vh - 300px);
}

/* ============================================================
   FILTER & SEARCH CONTROLS
   ============================================================ */
.secaw-filter-box {
    background: var(--white);
    border: 1px solid var(--border);
    padding: 20px 24px;
    margin-bottom: 28px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.secaw-filter-box__stats {
    font-size: 13.5px;
    color: var(--ink);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.secaw-search-form {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.secaw-input-group {
    display: flex;
    align-items: center;
    position: relative;
    min-width: 260px;
}

.secaw-input {
    width: 100%;
    padding: 9px 12px 9px 36px;
    font-size: 13.5px;
    border: 1px solid var(--border);
    border-radius: 0;
    background: var(--white);
    color: var(--ink);
    font-family: var(--font-body);
}

.secaw-input:focus {
    border-color: var(--navy);
    outline: 2px solid var(--navy);
    outline-offset: 0;
}

.secaw-input-icon {
    position: absolute;
    left: 12px;
    color: var(--mid);
    font-size: 14px;
    pointer-events: none;
}

.secaw-select {
    padding: 9px 14px;
    font-size: 13.5px;
    border: 1px solid var(--border);
    border-radius: 0;
    background: var(--white);
    color: var(--ink);
    font-family: var(--font-body);
    min-width: 180px;
}

.secaw-select:focus {
    border-color: var(--navy);
    outline: 2px solid var(--navy);
    outline-offset: 0;
}

/* ============================================================
   BUTTONS & BADGES
   ============================================================ */
.secaw-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 18px;
    font-family: var(--font-display);
    font-size: 12.5px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    border-radius: 0;
    text-decoration: none;
    transition: background var(--ease), color var(--ease), border-color var(--ease);
    border: 1px solid transparent;
    cursor: pointer;
    line-height: 1.2;
}

.secaw-btn:focus-visible {
    outline: 3px solid var(--navy);
    outline-offset: 2px;
}

.secaw-btn--primary {
    background: var(--navy);
    color: var(--white);
    border-color: var(--navy);
}

.secaw-btn--primary:hover {
    background: var(--navy-mid);
    border-color: var(--navy-mid);
    color: var(--white);
}

.secaw-btn--secondary {
    background: var(--white);
    color: var(--navy);
    border-color: var(--border);
}

.secaw-btn--secondary:hover {
    background: var(--navy-tint);
    border-color: var(--navy);
    color: var(--navy);
}

.secaw-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-family: var(--font-display);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 3px 8px;
    border-radius: 0;
    line-height: 1.2;
}

.secaw-badge--available {
    background: #E6F4EA;
    color: #137333;
    border: 1px solid #CEEAD6;
}

.secaw-badge--info {
    background: var(--navy-tint);
    color: var(--navy);
    border: 1px solid #C4D7F2;
}

/* ============================================================
   CARDS & GRIDS
   ============================================================ */
.secaw-grid-3 {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}

.secaw-grid-2 {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 24px;
}

.secaw-card {
    background: var(--white);
    border: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform var(--ease), box-shadow var(--ease);
    position: relative;
}

.secaw-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(10, 15, 26, 0.08);
}

.secaw-card__body {
    padding: 24px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.secaw-card__footer {
    padding: 16px 24px;
    background: var(--mist);
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.secaw-card__title {
    font-family: var(--font-display);
    font-size: 17px;
    font-weight: 800;
    color: var(--ink);
    margin: 10px 0 8px;
    line-height: 1.35;
}

.secaw-card__meta {
    font-size: 12.5px;
    color: var(--mid);
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-bottom: 14px;
}

.secaw-card__meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.secaw-card__meta-item i {
    color: var(--navy);
    font-size: 13px;
}

.secaw-card__desc {
    font-size: 13.5px;
    color: var(--ink);
    line-height: 1.6;
    margin-bottom: 16px;
    flex-grow: 1;
}

/* ============================================================
   INFOGRAPHICS GALLERY SPECIFICS
   ============================================================ */
.secaw-infographic-card {
    background: var(--white);
    border: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    cursor: pointer;
    padding: 0;
    text-align: left;
    position: relative;
    overflow: hidden;
    width: 100%;
    transition: transform var(--ease), box-shadow var(--ease);
}

.secaw-infographic-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(10, 15, 26, 0.08);
}

.secaw-infographic-card:focus-visible {
    outline: 3px solid var(--navy);
    outline-offset: -3px;
}

.secaw-infographic-img-wrap {
    position: relative;
    overflow: hidden;
    height: 220px;
    background: #E5E9F0;
}

.secaw-infographic-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.secaw-infographic-card:hover .secaw-infographic-img {
    transform: scale(1.03);
}

.secaw-card-overlay {
    position: absolute;
    inset: 0;
    background: rgba(10, 15, 26, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.secaw-infographic-card:hover .secaw-card-overlay {
    opacity: 1;
}

.secaw-zoom-btn {
    background: var(--white);
    color: var(--navy);
    width: 44px;
    height: 44px;
    border-radius: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* ============================================================
   MODAL / LIGHTBOX DIALOG (Modern Web Guidance Compliant)
   ============================================================ */
dialog.secaw-dialog {
    border: 2px solid var(--navy);
    border-radius: 0;
    padding: 0;
    max-width: 900px;
    width: 90vw;
    background: var(--white);
    color: var(--ink);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
}

dialog.secaw-dialog:modal {
    overscroll-behavior: contain;
}

dialog.secaw-dialog::backdrop {
    background: rgba(10, 15, 26, 0.85);
    overflow: hidden;
    overscroll-behavior: contain;
}

.secaw-dialog__header {
    background: var(--ink);
    color: var(--white);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.secaw-dialog__title {
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 800;
    margin: 0;
    color: var(--white);
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.secaw-dialog__close {
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: var(--white);
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 16px;
    border-radius: 0;
}

.secaw-dialog__close:hover,
.secaw-dialog__close:focus-visible {
    background: var(--navy);
    border-color: var(--navy);
    outline: 2px solid var(--white);
}

.secaw-dialog__body {
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #000;
}

.secaw-dialog__img {
    max-height: 72vh;
    max-width: 100%;
    object-fit: contain;
    display: block;
}

.secaw-dialog__footer {
    padding: 14px 20px;
    background: var(--mist);
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

/* ============================================================
   VIDEO RESPONSIVE PLAYER & FACADE
   ============================================================ */
.secaw-video-card {
    background: var(--white);
    border: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: transform var(--ease), box-shadow var(--ease);
}

.secaw-video-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(10, 15, 26, 0.08);
}

.secaw-video-facade-container {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 9;
    background: #000;
    overflow: hidden;
}

.secaw-video-facade-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.secaw-video-play-btn {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    background: rgba(10, 15, 26, 0.35);
    border: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    cursor: pointer;
    color: var(--white);
    transition: background 0.2s ease;
}

.secaw-video-play-btn:hover,
.secaw-video-play-btn:focus-visible {
    background: rgba(10, 15, 26, 0.6);
    outline: 3px solid var(--navy);
    outline-offset: -3px;
}

.secaw-video-play-icon {
    width: 60px;
    height: 60px;
    background: var(--navy);
    color: var(--white);
    border: 2px solid var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    padding-left: 4px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    transition: transform 0.2s ease, background 0.2s ease;
}

.secaw-video-play-btn:hover .secaw-video-play-icon {
    transform: scale(1.1);
    background: #0040B0;
}

.secaw-video-play-label {
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    background: rgba(10, 15, 26, 0.7);
    padding: 4px 12px;
}

/* ============================================================
   TOOLS DIRECTORY & SECURITY WARNING
   ============================================================ */
.secaw-alert {
    padding: 18px 22px;
    border: 1px solid var(--border);
    border-left: 5px solid var(--navy);
    background: var(--white);
    margin-bottom: 24px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
}

.secaw-alert--warning {
    border-left-color: #D9381E;
    background: #FFFBF8;
}

.secaw-alert--warning i {
    font-size: 24px;
    color: #D9381E;
    flex-shrink: 0;
    margin-top: 2px;
}

.secaw-alert__content {
    font-size: 13.5px;
    color: var(--ink);
    line-height: 1.6;
}

.secaw-alert__content strong {
    color: #9C1C08;
    display: block;
    margin-bottom: 4px;
    font-family: var(--font-display);
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.secaw-external-tag {
    font-size: 11.5px;
    color: var(--mid);
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* ============================================================
   EMPTY STATE COMPONENT
   ============================================================ */
.secaw-empty-state {
    background: var(--white);
    border: 1px dashed var(--border);
    padding: 56px 24px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    grid-column: 1 / -1;
}

.secaw-empty-state__icon {
    font-size: 42px;
    color: var(--mid);
    opacity: 0.6;
}

.secaw-empty-state h3 {
    font-family: var(--font-display);
    font-size: 19px;
    font-weight: 800;
    color: var(--ink);
    margin: 0;
    text-transform: uppercase;
}

.secaw-empty-state p {
    font-size: 13.5px;
    color: var(--mid);
    max-width: 480px;
    margin: 0 0 8px;
    line-height: 1.6;
}

/* ============================================================
   RESPONSIVE ADJUSTMENTS
   ============================================================ */
@media (max-width: 768px) {
    .secaw-filter-box {
        flex-direction: column;
        align-items: stretch;
    }
    .secaw-search-form {
        flex-direction: column;
        align-items: stretch;
    }
    .secaw-input-group,
    .secaw-select {
        min-width: 100%;
        width: 100%;
    }
    .secaw-grid-3,
    .secaw-grid-2 {
        grid-template-columns: 1fr;
    }
}
</style>
