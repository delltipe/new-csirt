{{--
    accessibility.blade.php
    Global accessibility widget — rebuilt to match jakarta.go.id
    "Widget Aksesibilitas Version 2.0", restyled on the project design tokens
    (DESIGN_SYSTEM.md): --ink / --navy / --navy-mid / --navy-tint / --mist /
    --border / --mid / --white. All surfaces boxy (border-radius: 0) per the
    NYC.gov look.
    Storage: browser localStorage (key: accessibilityState)
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
        <div class="group_title_disabilitas">
            <div class="row_title_disabilitas">
                <div class="start_title_disabilitas">
                    <div class="title_disabilitas">Menu Aksesibilitas (CTRL+U)</div>
                </div>
                <div class="end_title_disabilitas">
                    <button type="button" class="box_circle_disabilitas" id="circle_close_popup_dsb" aria-label="Tutup Menu Aksesibilitas">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="15px" height="15px" viewBox="0 0 448 448" fill="none">
                            <path class="icon_x_svg_color" d="M437.5,386.6L306.9,256l130.6-130.6c14.1-14.1,14.1-36.8,0-50.9c-14.1-14.1-36.8-14.1-50.9,0L256,205.1L125.4,74.5  c-14.1-14.1-36.8-14.1-50.9,0c-14.1,14.1-14.1,36.8,0,50.9L205.1,256L74.5,386.6c-14.1,14.1-14.1,36.8,0,50.9  c14.1,14.1,36.8,14.1,50.9,0L256,306.9l130.6,130.6c14.1,14.1,36.8,14.1,50.9,0C451.5,423.4,451.5,400.6,437.5,386.6z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="group_box_content_disabilitas">
            <div class="group_scroll_content">
                <!-- Language row (only Indonesian available) -->
                <div class="layout_content_title">
                    <div class="group_row_widget_dsb">
                        <button type="button" class="group_action_bahasa" id="dropdown_bahasa_widget" aria-expanded="false" aria-haspopup="true">
                            <span class="box_content_disabilitas"><span class="name_id">ID</span></span>
                            <span class="box_name_menu_disabilitas">Bahasa Indonesia (Indonesian)</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" aria-hidden="true">
                                <path class="icon_svg_color" d="M7 10l5 5 5-5z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="layout_bahasa_widget" id="show_bahasa_widget_dsb" hidden>
                    <div class="group_title_bahasa">
                        <div class="title_daftar_bahasa">Daftar Bahasa Widget Aksesibilitas</div>
                    </div>
                    <div class="group_body_bahasa">
                        <div class="box_selected_bahasa">
                            <span class="name_id">ID</span>
                            <span>Bahasa Indonesia (Indonesian)</span>
                            <i class="icon_check_bahasa" aria-hidden="true">✓</i>
                        </div>
                    </div>
                </div>

                <!-- Feature grid -->
                <div class="content_daftar_action_disabilitas">
                    <div class="layout_grid_disabilitas" role="group" aria-label="Fitur aksesibilitas">

                        {{-- Mode Suara (voice / TTS) --}}
                        <button type="button" class="box_group_disabilitas" id="action_moda_suara" aria-pressed="false">
                            <span class="box_icon_disabilitas">
                                <svg data-name="Layer 1" width="80px" height="60px" id="Layer_1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <line class="icon_svg_sroke_color" x1="2.5" x2="2.5" y1="10.5" y2="13.5"></line>
                                    <line class="icon_svg_sroke_color" x1="4.875" x2="4.875" y1="8" y2="16"></line>
                                    <line class="icon_svg_sroke_color" x1="7.25" x2="7.25" y1="5.045" y2="18.955"></line>
                                    <line class="icon_svg_sroke_color" x1="9.625" x2="9.625" y1="8.909" y2="15.091"></line>
                                    <line class="icon_svg_sroke_color" x1="12" x2="12" y1="10" y2="14"></line>
                                    <line class="icon_svg_sroke_color" x1="14.375" x2="14.375" y1="6.5" y2="17.5"></line>
                                    <line class="icon_svg_sroke_color" x1="16.75" x2="16.75" y1="3.5" y2="20.5"></line>
                                    <line class="icon_svg_sroke_color" x1="19.125" x2="19.125" y1="8.136" y2="15.864"></line>
                                    <line class="icon_svg_sroke_color" x1="21.5" x2="21.5" y1="10.455" y2="13.545"></line>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas">Mode Suara</span>
                            <span class="box_column_action_strip"></span>
                        </button>

                        {{-- Perbesar Teks (4-level gauge) --}}
                        <button type="button" class="box_group_disabilitas" id="action_perbesar_text">
                            <span class="box_icon_disabilitas">
                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="70px" height="70px" viewBox="0 0 234.000000 174.000000" preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,174.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                                        <path class="icon_svg_color" d="M900 1470 l0 -100 250 0 250 0 0 -590 0 -590 95 0 95 0 0 590 0 590 248 2 247 3 3 98 3 97 -596 0 -595 0 0 -100z"></path>
                                        <path class="icon_svg_color" d="M117 1173 c-4 -3 -7 -48 -7 -100 l0 -93 200 0 200 0 0 -395 0 -395 100 0 100 0 0 395 0 395 195 0 195 0 0 100 0 100 -488 0 c-269 0 -492 -3 -495-7z"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas">Perbesar Teks</span>
                            <span class="box_column_action_strip">
                                <span class="box_row_action_strip" id="list_strip_loading_perbesar_text" role="img" aria-label="Ukuran teks: level 1 dari 4">
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_1"></span>
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_2"></span>
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_3"></span>
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_4"></span>
                                </span>
                            </span>
                        </button>

                        {{-- Perkecil Teks (4-level gauge, mirrors current size level) --}}
                        <button type="button" class="box_group_disabilitas" id="action_perkecil_text">
                            <span class="box_icon_disabilitas">
                                <svg version="1.1" width="60px" height="60px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none">
                                    <path class="icon_svg_color" d="M16 9v8h-2V9h-4V7h10v2h-4zM8 5v12H6V5H0V3h15v2H8z"></path>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas">Perkecil Teks</span>
                            <span class="box_column_action_strip">
                                <span class="box_row_action_strip" id="list_strip_loading_perkecil_text" role="img" aria-label="Ukuran teks: level 1 dari 4">
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_1"></span>
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_2"></span>
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_3"></span>
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_4"></span>
                                </span>
                            </span>
                        </button>

                        {{-- Skala Abu-Abu (grayscale toggle) --}}
                        <button type="button" class="box_group_disabilitas" id="action_grey_scale" aria-pressed="false">
                            <span class="box_icon_disabilitas">
                                <svg version="1.1" width="60px" height="60px" viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg" fill="none">
                                    <g style="isolation: isolate">
                                        <g data-name="Layer 1" id="Layer_1">
                                            <path class="icon_svg_grey_scale_color_1" d="M36.64843,0S4.58051,35.08224,4.58051,68.3789c0,18.46444,14.35732,32.8026,32.06792,32.8026S68.71635,86.84334,68.71635,68.3789C68.71635,35.08224,36.64843,0,36.64843,0Z"></path>
                                            <path class="icon_svg_grey_scale_color_2" d="M36.64842,0s-.04336.04776-.11442.12681c7.72737,10.25554,23.909,34.51028,23.909,57.82942,0,16.9843-12.50526,30.1731-27.93125,30.1731-13.987,0-25.57232-10.84318-27.61209-25.527a55.61064,55.61064,0,0,0-.31916,5.77655c0,18.46443,14.35732,32.80255,32.06791,32.80255S68.71632,86.84334,68.71632,68.3789C68.71632,35.08225,36.64842,0,36.64842,0Z"></path>
                                            <circle class="icon_svg_grey_scale_color_4" cx="22.88945" cy="58.6888" r="6.85099"></circle>
                                            <path class="icon_svg_grey_scale_color_3" d="M91.35157,26.81852S59.28365,61.90075,59.28365,95.1974C59.28365,113.66184,73.641,128,91.35157,128s32.06792-14.33816,32.06792-32.8026C123.41949,61.90075,91.35157,26.81852,91.35157,26.81852Z"></path>
                                            <path class="icon_svg_grey_scale_color_2" d="M91.35156,26.8185s-.04336.04776-.11442.12681c7.72737,10.25554,23.909,34.51028,23.909,57.82942,0,16.9843-12.50526,30.1731-27.93125,30.1731-13.987,0-25.57232-10.84318-27.61209-25.527a55.61064,55.61064,0,0,0-.31916,5.77655C59.28365,113.66184,73.641,128,91.35156,128s32.06791-14.33812,32.06791-32.80255C123.41946,61.90075,91.35156,26.8185,91.35156,26.8185Z"></path>
                                            <circle class="icon_svg_grey_scale_color_4" cx="77.59259" cy="85.5073" r="6.85099"></circle>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas">Skala Abu - Abu</span>
                            <span class="box_column_action_strip"></span>
                        </button>

                        {{-- Kontras+ (4-level gauge, 4 swapped icons) --}}
                        <button type="button" class="box_group_disabilitas" id="action_kontras">
                            <span class="box_icon_disabilitas">
                                <svg id="svg_kontras_multi" width="60px" height="60px" version="1.1" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <path class="circle_multi" d="M27,15.1c1.2,1.5,2,3.4,2,5.4c0,4.7-3.8,8.5-8.5,8.5S12,25.2,12,20.5s3.8-8.5,8.5-8.5c0.5,0,1,0,1.4,0.1"></path>
                                    <path class="circle_multi" d="M19.7,22.6c-0.9,3.7-4.3,6.4-8.2,6.4C6.8,29,3,25.2,3,20.5c0-2.2,0.8-4.2,2.2-5.7"></path>
                                    <path class="circle_multi" d="M10.5,12.1c0.3,0,0.7-0.1,1-0.1c3.5,0,6.4,2.1,7.8,5"></path>
                                    <path class="circle_single" d="M9.7,17.5C8.4,16,7.7,14.1,7.7,12c0-4.7,3.8-8.5,8.5-8.5s8.5,3.8,8.5,8.5s-3.8,8.5-8.5,8.5c-0.3,0-0.6,0-0.9,0"></path>
                                </svg>
                                <svg id="svg_balikan_warna" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="60" height="60" style="display: none;">
                                    <path class="active_icon_svg_color" fill-rule="nonzero" d="m14.1 33.9-7.805 7.805a1 1 0 0 0 .708.295h33.994c.551 0 1.003-.451 1.003-1.003V7.003a1 1 0 0 0-.295-.708L33.9 14.1A13.956 13.956 0 0 1 38 24c0 7.732-6.268 14-14 14a13.956 13.956 0 0 1-9.9-4.1ZM4 7.002A3.006 3.006 0 0 1 7.003 4h33.994A3.006 3.006 0 0 1 44 7.003v33.994A3.006 3.006 0 0 1 40.997 44H7.003A3.006 3.006 0 0 1 4 40.997V7.003ZM33.9 14.1A13.956 13.956 0 0 0 24 10c-7.732 0-14 6.268-14 14 0 3.866 1.567 7.366 4.1 9.9l19.8-19.8Z"></path>
                                </svg>
                                <svg id="svg_kontras_warna" viewBox="0 0 512 512" width="60px" height="60px" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <circle class="icon_contrast_white" cx="256" cy="256" r="208"></circle>
                                    <path class="icon_contrast_black" d="M256,464C141.12,464,48,370.88,48,256S141.12,48,256,48Z"></path>
                                </svg>
                                <svg width="60px" height="60px" id="svg_kontras_klise" style="display: none;" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g>
                                        <path class="active_icon_svg_color" d="M256,128c-81.9,0-145.7,48.8-224,128c67.4,67.7,124,128,224,128c99.9,0,173.4-76.4,224-126.6   C428.2,198.6,354.8,128,256,128z M256,347.3c-49.4,0-89.6-41-89.6-91.3c0-50.4,40.2-91.3,89.6-91.3s89.6,41,89.6,91.3   C345.6,306.4,305.4,347.3,256,347.3z"></path>
                                        <g>
                                            <path class="active_icon_svg_color" d="M256,224c0-7.9,2.9-15.1,7.6-20.7c-2.5-0.4-5-0.6-7.6-0.6c-28.8,0-52.3,23.9-52.3,53.3c0,29.4,23.5,53.3,52.3,53.3    s52.3-23.9,52.3-53.3c0-2.3-0.2-4.6-0.4-6.9c-5.5,4.3-12.3,6.9-19.8,6.9C270.3,256,256,241.7,256,224z"></path>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas" id="text_name_kontras">Kontras+</span>
                            <span class="box_column_action_strip">
                                <span class="box_row_action_strip" id="list_strip_loading_action_kontras" role="img" aria-label="Level kontras: 0 dari 4">
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_1"></span>
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_2"></span>
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_3"></span>
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_4"></span>
                                </span>
                            </span>
                        </button>

                        {{-- Sembunyikan Gambar (toggle) --}}
                        <button type="button" class="box_group_disabilitas" id="action_hidden_image" aria-pressed="false">
                            <span class="box_icon_disabilitas">
                                <svg fill="none" height="60" viewBox="0 0 64 64" width="60" xmlns="http://www.w3.org/2000/svg">
                                    <path class="icon_svg_color" clip-rule="evenodd" d="M5.41421 2.58579C4.63316 1.80474 3.36683 1.80474 2.58579 2.58579C1.80474 3.36683 1.80474 4.63316 2.58579 5.41421L5.36029 8.18871C3.25416 10.8981 2 14.3026 2 18V46C2 54.8366 9.16344 62 18 62H46C49.6974 62 53.1019 60.7458 55.8113 58.6397L58.5858 61.4142C59.3668 62.1953 60.6332 62.1953 61.4142 61.4142C62.1953 60.6332 62.1953 59.3668 61.4142 58.5858L5.41421 2.58579ZM52.9531 55.7815L8.2185 11.0469C6.82158 13.0086 6 15.4083 6 18V46C6 46.3848 6.01811 46.7653 6.05352 47.1408L10.589 39.8841C15.6167 31.8397 27.0207 31.0191 33.1483 38.2609L49.4299 57.5027C50.7062 57.1227 51.8935 56.536 52.9531 55.7815Z" fill="black" fill-rule="evenodd"></path>
                                    <path class="icon_svg_color" d="M58 46C58 47.2605 57.8057 48.4755 57.4453 49.6169L60.5322 52.7037C61.4743 50.6647 62 48.3937 62 46V18C62 9.16344 54.8366 2 46 2H18C15.6063 2 13.3353 2.52566 11.2963 3.46785L14.3831 6.55468C15.5245 6.19434 16.7395 6 18 6H46C52.6274 6 58 11.3726 58 18V46Z" fill="black"></path>
                                    <path class="icon_svg_color" d="M43 17C39.6863 17 37 19.6863 37 23C37 26.3137 39.6863 29 43 29C46.3137 29 49 26.3137 49 23C49 19.6863 46.3137 17 43 17Z" fill="black"></path>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas">Sembunyikan Gambar</span>
                            <span class="box_column_action_strip"></span>
                        </button>

                        {{-- Rata Tulisan (4-level gauge, 4 swapped icons) --}}
                        <button type="button" class="box_group_disabilitas" id="action_perataan_text">
                            <span class="box_icon_disabilitas">
                                <svg id="svg_left_text_icon" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                                    <rect fill="none" height="60" width="60"></rect>
                                    <line fill="none" class="icon_svg_sroke_color" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="40" x2="216" y1="68" y2="68"></line>
                                    <line fill="none" class="icon_svg_sroke_color" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="40" x2="168" y1="108" y2="108"></line>
                                    <line fill="none" class="icon_svg_sroke_color" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="40" x2="216" y1="148" y2="148"></line>
                                    <line fill="none" class="icon_svg_sroke_color" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="40" x2="168" y1="188" y2="188"></line>
                                </svg>
                                <svg class="hidden_svg" id="svg_center_text_icon" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <rect fill="none" height="60" width="60"></rect>
                                    <path class="active_icon_svg_color" d="M40,76H216a8,8,0,0,0,0-16H40a8,8,0,0,0,0,16Z"></path>
                                    <path class="active_icon_svg_color" d="M64,100a8,8,0,0,0,0,16H192a8,8,0,0,0,0-16Z"></path>
                                    <path class="active_icon_svg_color" d="M216,140H40a8,8,0,0,0,0,16H216a8,8,0,0,0,0-16Z"></path>
                                    <path class="active_icon_svg_color" d="M192,180H64a8,8,0,0,0,0,16H192a8,8,0,0,0,0-16Z"></path>
                                </svg>
                                <svg class="hidden_svg" id="svg_right_text_icon" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <rect fill="none" height="60" width="60"></rect>
                                    <line fill="none" class="active_icon_svg_sroke_color" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="40" x2="216" y1="68" y2="68"></line>
                                    <line fill="none" class="active_icon_svg_sroke_color" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="88" x2="216" y1="108" y2="108"></line>
                                    <line fill="none" class="active_icon_svg_sroke_color" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="40" x2="216" y1="148" y2="148"></line>
                                    <line fill="none" class="active_icon_svg_sroke_color" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="88" x2="216" y1="188" y2="188"></line>
                                </svg>
                                <svg class="hidden_svg" id="svg_right_left_text_icon" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <rect fill="none" height="60" width="60"></rect>
                                    <line fill="none" class="active_icon_svg_sroke_color" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="40" x2="216" y1="68" y2="68"></line>
                                    <line fill="none" class="active_icon_svg_sroke_color" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="40" x2="216" y1="108" y2="108"></line>
                                    <line fill="none" class="active_icon_svg_sroke_color" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="40" x2="216" y1="148" y2="148"></line>
                                    <line fill="none" class="active_icon_svg_sroke_color" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="40" x2="216" y1="188" y2="188"></line>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas" id="text_rata_tulisan">Rata Tulisan</span>
                            <span class="box_column_action_strip">
                                <span class="box_row_action_strip" id="list_strip_loading_perataan_text" role="img" aria-label="Perataan tulisan: level 0 dari 4">
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_1"></span>
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_2"></span>
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_3"></span>
                                    <span class="strip_loading_unprocess_v4" id="strip_loading_4"></span>
                                </span>
                            </span>
                        </button>

                        {{-- Tulisan Dapat Dibaca (toggle) --}}
                        <button type="button" class="box_group_disabilitas" id="action_tulisan_dapat_di_baca" aria-pressed="false">
                            <span class="box_icon_disabilitas">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="60" width="60" viewBox="0 0 448 448">
                                    <path class="icon_svg_color" d="M181.25 139.75l-42.5 112.5c24.75 0.25 49.5 1 74.25 1 4.75 0 9.5-0.25 14.25-0.5-13-38-28.25-76.75-46-113zM0 416l0.5-19.75c23.5-7.25 49-2.25 59.5-29.25l59.25-154 70-181h32c1 1.75 2 3.5 2.75 5.25l51.25 120c18.75 44.25 36 89 55 133 11.25 26 20 52.75 32.5 78.25 1.75 4 5.25 11.5 8.75 14.25 8.25 6.5 31.25 8 43 12.5 0.75 4.75 1.5 9.5 1.5 14.25 0 2.25-0.25 4.25-0.25 6.5-31.75 0-63.5-4-95.25-4-32.75 0-65.5 2.75-98.25 3.75 0-6.5 0.25-13 1-19.5l32.75-7c6.75-1.5 20-3.25 20-12.5 0-9-32.25-83.25-36.25-93.5l-112.5-0.5c-6.5 14.5-31.75 80-31.75 89.5 0 19.25 36.75 20 51 22 0.25 4.75 0.25 9.5 0.25 14.5 0 2.25-0.25 4.5-0.5 6.75-29 0-58.25-5-87.25-5-3.5 0-8.5 1.5-12 2-15.75 2.75-31.25 3.5-47 3.5z"></path>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas">Tulisan Dapat Dibaca</span>
                            <span class="box_column_action_strip"></span>
                        </button>

                        {{-- Tinggi Garis (3-level gauge) --}}
                        <button type="button" class="box_group_disabilitas" id="action_tulisan_line_height">
                            <span class="box_icon_disabilitas">
                                <svg fill="none" height="60" width="60" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path class="icon_svg_color" d="M5.09668 6.99707H7.17358L4.17358 3.99707L1.17358 6.99707H3.09668V17.0031H1.15881L4.15881 20.0031L7.15881 17.0031H5.09668V6.99707Z" fill="currentColor"></path>
                                    <path class="icon_svg_color" d="M22.8412 7H8.84119V5H22.8412V7Z" fill="currentColor"></path>
                                    <path class="icon_svg_color" d="M22.8412 11H8.84119V9H22.8412V11Z" fill="currentColor"></path>
                                    <path class="icon_svg_color" d="M8.84119 15H22.8412V13H8.84119V15Z" fill="currentColor"></path>
                                    <path class="icon_svg_color" d="M22.8412 19H8.84119V17H22.8412V19Z" fill="currentColor"></path>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas" id="text_id_tinggi_garis">Tinggi Garis</span>
                            <span class="box_column_action_strip">
                                <span class="box_row_action_strip" id="list_strip_loading_action_tulisan_line_height" role="img" aria-label="Tinggi garis: level 0 dari 3">
                                    <span class="strip_loading_unprocess_v3" id="strip_loading_1"></span>
                                    <span class="strip_loading_unprocess_v3" id="strip_loading_2"></span>
                                    <span class="strip_loading_unprocess_v3" id="strip_loading_3"></span>
                                </span>
                            </span>
                        </button>

                        {{-- Animasi Dijeda (toggle) --}}
                        <button type="button" class="box_group_disabilitas" id="action_animate_pause" aria-pressed="false">
                            <span class="box_icon_disabilitas">
                                <svg height="45" width="45" id="svg_animasi_pause" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg">
                                    <defs id="defs19"></defs>
                                    <g id="g2998" transform="matrix(10.666667,0,0,10.666667,-13434.667,-10410.667)">
                                        <g id="g3">
                                            <path class="icon_svg_color" d="m 1293,987 c 0,2.812 -3.311,5.562 -5.973,7.771 -1.203,0.998 -2.48,1.995 -3.527,3.229 -1.046,-1.233 -2.324,-2.23 -3.527,-3.229 -2.658,-2.209 -5.973,-4.959 -5.973,-7.771 0,0 7.436,3.399 9.5,3.399 2.064,0 9.5,-3.399 9.5,-3.399 z" id="path5"></path>
                                            <path class="icon_svg_color" d="m 1283.5,1006 c 1.051,0.927 2.322,1.684 3.526,2.436 2.663,1.658 5.974,3.728 5.974,5.839 V 1018 h -19 v -3.727 c 0,-2.111 3.311,-4.181 5.973,-5.839 1.205,-0.75 2.481,-1.507 3.527,-2.434" id="path7"></path>
                                            <g id="g9">
                                                <path class="icon_svg_color" d="m 1294,981 v 6.517 c 0,3.725 -3.01,6.452 -5.208,8.444 -1.621,1.469 -2.792,2.53 -2.792,4.039 0,1.509 1.172,2.57 2.793,4.04 2.197,1.991 5.207,4.72 5.207,8.442 V 1019 h -21 v -6.518 c 0,-3.724 3.01,-6.451 5.207,-8.442 1.621,-1.47 2.793,-2.531 2.793,-4.04 0,-1.509 -1.171,-2.57 -2.792,-4.039 -2.198,-1.992 -5.208,-4.72 -5.208,-8.444 V 981 h 21 m 2,-2 h -25 c 0,0 0,1.542 0,8.517 0,6.977 8,10.647 8,12.483 0,1.836 -8,5.508 -8,12.482 0,6.976 0,8.518 0,8.518 h 25 c 0,0 0,-1.542 0,-8.518 0,-6.976 -8,-10.646 -8,-12.482 0,-1.835 8,-5.507 8,-12.483 0,-6.975 0,-8.517 0,-8.517 l 0,0 z" id="path11"></path>
                                            </g>
                                            <rect class="icon_svg_sroke_color" height="2" id="rect13" width="29" x="1269" y="976"></rect>
                                            <rect class="icon_svg_sroke_color" height="2" id="rect15" width="29" x="1269" y="1022"></rect>
                                        </g>
                                    </g>
                                </svg>
                                <svg class="hidden_svg" id="svg_animasi_play" version="1.1" height="60" width="60" viewBox="0 0 512 512" style="display: none;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g>
                                        <g>
                                            <path class="active_icon_svg_color" d="M224.6,366C224.6,366,224.6,366,224.6,366c-18.8,0-33.6-14-33.6-31.9V215.8c0-18,15-32.6,33.8-32.6c7.2,0,13.9,2.2,19.6,6.5l81.1,59c8.2,6.1,12.8,15.4,12.8,25.5c0,10.5-5.1,20.5-13.6,26.9l-80.5,58.9C238.6,364.1,231.8,366,224.6,366z M225,217.4v115.1l78.7-57.8L225,217.4z"></path>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas" id="text_id_animasi">Animasi Dijeda</span>
                            <span class="box_column_action_strip"></span>
                        </button>

                        {{-- Kursor (toggle) --}}
                        <button type="button" class="box_group_disabilitas" id="action_kursor" aria-pressed="false">
                            <span class="box_icon_disabilitas">
                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path class="icon_svg_color" d="M16.5744 19.1999L12.6361 15.2616L11.4334 16.4643C10.2022 17.6955 9.58656 18.3111 8.92489 18.1658C8.26322 18.0204 7.96225 17.2035 7.3603 15.5696L5.3527 10.1205C4.15187 6.86106 3.55146 5.23136 4.39141 4.39141C5.23136 3.55146 6.86106 4.15187 10.1205 5.35271L15.5696 7.3603C17.2035 7.96225 18.0204 8.26322 18.1658 8.92489C18.3111 9.58656 17.6955 10.2022 16.4643 11.4334L15.2616 12.6361L19.1999 16.5744C19.6077 16.9821 19.8116 17.186 19.9058 17.4135C19.9058 17.7168 19.9058 18.0575 19.9058 18.3608C19.8116 18.5882 19.6077 18.7921 19.1999 19.1999C18.7921 19.6077 18.5882 19.8116 18.3608 19.9058C18.0575 20.0314 17.7168 20.0314 17.4135 19.9058C17.186 19.8116 16.9821 19.6077 16.5744 19.1999Z"></path>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas">Kursor</span>
                            <span class="box_column_action_strip"></span>
                        </button>

                        {{-- Spasi Teks (3-level gauge) --}}
                        <button type="button" class="box_group_disabilitas" id="action_space_text">
                            <span class="box_icon_disabilitas">
                                <svg version="1.0" width="60" height="60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 208.000000 207.000000" preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,207.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                                        <path class="icon_svg_color" d="M270 1825 l0 -105 320 0 320 0 0 -480 0 -480 110 0 110 0 0 480 0
480 320 0 320 0 0 105 0 105 -750 0 -750 0 0 -105z"></path>
                                        <path class="icon_svg_color" d="M300 625 l-185 -185 188 -188 187 -187 0 133 0 132 530 0 530 0 0
-132 0 -133 187 188 188 187 -188 187 -187 188 0 -133 0 -132 -530 0 -529 0
-3 130 -3 130 -185 -185z"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas" id="id_space_text">Spasi Teks</span>
                            <span class="box_column_action_strip">
                                <span class="box_row_action_strip" id="list_strip_loading_action_space_text" role="img" aria-label="Spasi teks: level 0 dari 3">
                                    <span class="strip_loading_unprocess_v3" id="strip_loading_1"></span>
                                    <span class="strip_loading_unprocess_v3" id="strip_loading_2"></span>
                                    <span class="strip_loading_unprocess_v3" id="strip_loading_3"></span>
                                </span>
                            </span>
                        </button>

                        {{-- Garis Bawahi Tautan (2-level gauge, 2 swapped icons) --}}
                        <button type="button" class="box_group_disabilitas" id="action_garis_bawahi_tautan">
                            <span class="box_icon_disabilitas">
                                <svg fill="#000000" id="svg_decoration_link" width="60px" height="60px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <rect class="icon_fill_svg_color_black" x="2" y="2" width="20" height="20" rx="2"></rect>
                                    <path class="icon_fill_svg_color_white" d="M7,6A1,1,0,0,1,8,5h2a1,1,0,0,1,0,2v4a2,2,0,0,0,4,0V7a1,1,0,0,1,0-2h2a1,1,0,0,1,0,2v4a4,4,0,0,1-8,0V7A1,1,0,0,1,7,6Zm9,11H8a1,1,0,0,0,0,2h8a1,1,0,0,0,0-2Z"></path>
                                </svg>
                                <svg version="1.0" id="svg_block_decoration_link" xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 231.000000 129.000000" preserveAspectRatio="xMidYMid meet" style="display: none;">
                                    <g class="active_icon_svg_color" transform="translate(0.000000,129.000000) scale(0.100000,-0.100000)" stroke="none">
                                        <path class="active_icon_svg_color" d="M435 1136 c-495 -161 -489 -863 9 -1015 61 -19 93 -21 293 -21 l225
0 29 29 c31 32 38 79 18 124 -22 48 -40 52 -274 57 -216 5 -221 6 -277 33 -62
30 -115 83 -150 149 -19 36 -23 58 -23 138 0 80 4 102 23 138 35 66 88 119
150 149 56 27 61 28 277 33 234 5 252 9 274 57 20 45 13 92 -18 124 l-29 29
-229 0 c-209 -1 -234 -3 -298 -24z"></path>
                                        <path class="active_icon_svg_color" d="M1269 1131 c-31 -32 -38 -79 -18 -124 22 -48 40 -52 274 -57 216 -5
221 -6 277 -33 62 -30 115 -83 150 -149 19 -36 23 -58 23 -138 0 -80 -4 -102
-23 -138 -35 -66 -88 -119 -150 -149 -56 -27 -61 -28 -277 -33 -234 -5 -252
-9 -274 -57 -20 -45 -13 -92 18 -124 l29 -29 225 0 c253 0 310 10 417 75 338
205 338 705 0 910 -107 65 -164 75 -417 75 l-225 0 -29 -29z"></path>
                                        <path class="active_icon_svg_color" d="M763 724 c-29 -15 -63 -65 -63 -94 0 -30 34 -80 65 -94 49 -24 681
-24 730 0 31 14 65 64 65 94 0 30 -34 80 -65 94 -49 24 -684 23 -732 0z"></path>
                                    </g>
                                </svg>
                            </span>
                            <span class="box_text_bottom_disabilitas" id="text_garis_bawahi_tautan">Garis Bawahi Tautan</span>
                            <span class="box_column_action_strip">
                                <span class="box_row_action_strip" id="list_strip_loading_action_garis_bawahi_tautan" role="img" aria-label="Garis bawahi tautan: level 0 dari 2">
                                    <span class="strip_loading_unprocess_v2" id="strip_loading_1"></span>
                                    <span class="strip_loading_unprocess_v2" id="strip_loading_2"></span>
                                </span>
                            </span>
                        </button>

                    </div>

                    {{-- Reset --}}
                    <div class="column_reset_disabilitas_menu">
                        <button type="button" class="row_persegi_reset" id="reset_pengaturan_all_dsb">
                            <span class="icon_persegi_riset">
                                <svg height="20px" width="20px" id="Layer_1" style="enable-background:new 0 0 32 32;" version="1.1" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <path class="icon_x_svg_color" d="M29,2c-0.452,0-0.952,0.144-1.415,0.582l-1.941,1.941C22.981,2.273,19.593,1,16,1C9.473,1,3.738,5.173,1.73,11.385  c-0.34,1.051,0.237,2.179,1.288,2.518C3.224,13.969,3.431,14,3.634,14c0.845,0,1.63-0.539,1.903-1.385C7.009,8.06,11.214,5,16,5  c2.513,0,4.88,0.864,6.791,2.377l-3.209,3.209C19.144,11.048,19,11.548,19,12c0,1.021,0.809,2,2,2h8c1.024,0,2-0.812,2-2V4  C31,2.809,30.021,2,29,2z M28.981,18.097C28.776,18.031,28.569,18,28.366,18c-0.845,0-1.63,0.539-1.903,1.385C24.991,23.94,20.786,27,16,27c-2.513,0-4.88-0.864-6.791-2.377l3.209-3.209C12.856,20.952,13,20.452,13,20c0-1.021-0.809-2-2-2H3  c-1.024,0-2,0.812-2,2v8c0,1.191,0.979,2,2,2c0.452,0,0.952-0.144,1.415-0.582l1.941-1.941C9.019,29.727,12.407,31,16,31  c6.527,0,12.262-4.173,14.27-10.385C30.609,19.564,30.032,18.437,28.981,18.097z"></path>
                                </svg>
                            </span>
                            <span class="column_text_persegi_riset">Atur Ulang Semua Pengaturan Aksesibilitas</span>
                        </button>
                    </div>

                    <div class="column_cek_version_dsb">
                        <div class="column_text_cek_version_dsb">- Widget Aksesibilitas Version 2.0 -</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===== ACCESSIBILITY WIDGET v2.0 STYLES — design-token based ===== */

    .accessibility-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: var(--font-body, 'Inter', system-ui, sans-serif);
    }

    /* Toggle Button */
    .accessibility-toggle {
        width: 52px;
        height: 52px;
        border-radius: 0;
        background: var(--navy, #003580);
        color: var(--white, #FFFFFF);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 53, 128, 0.25);
        transition: background var(--ease), box-shadow var(--ease), transform var(--ease);
    }

    .accessibility-toggle:hover {
        background: var(--navy-mid, #004099);
        box-shadow: 0 6px 16px rgba(0, 53, 128, 0.35);
    }

    .accessibility-toggle:focus-visible {
        outline: 2px solid var(--navy, #003580);
        outline-offset: 2px;
    }

    /* Panel */
    .accessibility-panel {
        position: absolute;
        bottom: 64px;
        right: 0;
        width: 560px;
        max-width: calc(100vw - 32px);
        background: var(--white, #FFFFFF);
        border-radius: 0;
        border: 1px solid var(--border, #D8DCE3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        animation: a11ySlideIn 0.3s var(--ease);
    }

    .accessibility-panel[hidden] {
        display: none;
    }

    @keyframes a11ySlideIn {
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
    .group_title_disabilitas {
        padding: 12px 14px;
        background: var(--mist, #F4F5F7);
        border-bottom: 1px solid var(--border, #D8DCE3);
    }

    .row_title_disabilitas {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .title_disabilitas {
        font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif);
        font-size: 13px;
        font-weight: 600;
        color: var(--ink, #0A0F1A);
        margin: 0;
        letter-spacing: 0.01em;
    }

    .box_circle_disabilitas {
        width: 28px;
        height: 28px;
        border-radius: 0;
        border: 1px solid var(--border, #D8DCE3);
        background: var(--white, #FFFFFF);
        color: var(--mid, #6B7280);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: background var(--ease), color var(--ease);
    }

    .box_circle_disabilitas:hover {
        background: var(--navy-tint, #E8EFF8);
        color: var(--navy, #003580);
    }

    .box_circle_disabilitas:focus-visible {
        outline: 2px solid var(--navy, #003580);
        outline-offset: 2px;
    }

    .icon_x_svg_color {
        fill: currentColor;
    }

    /* Scroll Body */
    .group_box_content_disabilitas {
        overflow: hidden;
    }

    .group_scroll_content {
        max-height: min(560px, 60vh);
        overflow-y: auto;
    }

    /* Language row */
    .layout_content_title {
        padding: 12px 14px 4px;
    }

    .group_row_widget_dsb {
        display: flex;
        align-items: center;
    }

    .group_action_bahasa {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border: 1px solid var(--border, #D8DCE3);
        border-radius: 0;
        background: var(--white, #FFFFFF);
        padding: 5px 10px 5px 5px;
        cursor: pointer;
        font-family: var(--font-body, 'Inter', system-ui, sans-serif);
        font-size: 12px;
        color: var(--ink, #0A0F1A);
        transition: border-color var(--ease);
    }

    .group_action_bahasa:hover {
        border-color: var(--navy, #003580);
    }

    .group_action_bahasa:focus-visible {
        outline: 2px solid var(--navy, #003580);
        outline-offset: 2px;
    }

    .group_action_bahasa .icon_svg_color {
        fill: var(--mid, #6B7280);
    }

    .box_content_disabilitas {
        background: var(--navy, #003580);
        color: var(--white, #FFFFFF);
        font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif);
        font-size: 11px;
        font-weight: 700;
        padding: 3px 6px;
        line-height: 1;
    }

    .box_name_menu_disabilitas {
        font-size: 12px;
    }

    .layout_bahasa_widget {
        margin: 6px 14px 0;
        border: 1px solid var(--border, #D8DCE3);
        border-radius: 0;
        background: var(--white, #FFFFFF);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .group_title_bahasa {
        padding: 10px 12px;
        border-bottom: 1px solid var(--border, #D8DCE3);
        background: var(--mist, #F4F5F7);
    }

    .title_daftar_bahasa {
        font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif);
        font-size: 12px;
        font-weight: 600;
        color: var(--ink, #0A0F1A);
    }

    .group_body_bahasa {
        padding: 6px 8px;
    }

    .box_selected_bahasa {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        color: var(--ink, #0A0F1A);
        padding: 6px 8px;
        background: var(--navy-tint, #E8EFF8);
        border: 1px solid var(--navy, #003580);
    }

    .box_selected_bahasa .name_id {
        background: var(--navy, #003580);
        color: var(--white, #FFFFFF);
        font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif);
        font-size: 11px;
        font-weight: 700;
        padding: 3px 6px;
        line-height: 1;
    }

    .icon_check_bahasa {
        margin-left: auto;
        color: var(--navy, #003580);
        font-style: normal;
        font-weight: 700;
    }

    /* Feature grid */
    .content_daftar_action_disabilitas {
        padding: 10px 14px 14px;
    }

    .layout_grid_disabilitas {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }

    /* Tile */
    .box_group_disabilitas {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 14px 6px;
        border: 1px solid var(--border, #D8DCE3);
        border-radius: 0;
        background: var(--white, #FFFFFF);
        color: var(--ink, #0A0F1A);
        font-family: var(--font-body, 'Inter', system-ui, sans-serif);
        cursor: pointer;
        text-align: center;
        transition: background var(--ease), border-color var(--ease), color var(--ease);
    }

    .box_group_disabilitas:hover {
        background: var(--navy-tint, #E8EFF8);
        border-color: var(--navy, #003580);
    }

    .box_group_disabilitas:focus-visible {
        outline: 2px solid var(--navy, #003580);
        outline-offset: 2px;
    }

    .box_group_disabilitas.active_box_menu_disabilitas {
        background: var(--navy, #003580);
        border-color: var(--navy, #003580);
        color: var(--white, #FFFFFF);
    }

    .box_icon_disabilitas {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
    }

    .box_icon_disabilitas svg {
        width: 40px;
        height: 40px;
        flex: none;
    }

    /* Icon colors — design-token based (reference glyph set kept) */
    .box_group_disabilitas .icon_svg_color,
    .box_group_disabilitas .icon_svg_grey_scale_color_1,
    .box_group_disabilitas .icon_svg_grey_scale_color_2,
    .box_group_disabilitas .icon_svg_grey_scale_color_3,
    .box_group_disabilitas .icon_svg_grey_scale_color_4 {
        fill: var(--ink, #0A0F1A);
    }

    .box_group_disabilitas .icon_svg_sroke_color {
        fill: none;
        stroke: var(--ink, #0A0F1A);
    }

    .box_group_disabilitas .circle_multi,
    .box_group_disabilitas .circle_single {
        fill: none;
        stroke: var(--ink, #0A0F1A);
        stroke-width: 2;
    }

    .box_group_disabilitas .active_icon_svg_color {
        fill: var(--ink, #0A0F1A);
    }

    .box_group_disabilitas .active_icon_svg_sroke_color {
        fill: none;
        stroke: var(--ink, #0A0F1A);
    }

    .box_group_disabilitas .icon_fill_svg_color_black {
        fill: var(--ink, #0A0F1A);
    }

    .box_group_disabilitas .icon_fill_svg_color_white {
        fill: var(--white, #FFFFFF);
    }

    .box_group_disabilitas .icon_contrast_white {
        fill: var(--ink, #0A0F1A);
    }

    .box_group_disabilitas .icon_contrast_black {
        fill: var(--ink, #0A0F1A);
        opacity: 0.3;
    }

    /* Active (selected) tile — glyphs turn white */
    .box_group_disabilitas.active_box_menu_disabilitas .icon_svg_color,
    .box_group_disabilitas.active_box_menu_disabilitas .icon_svg_grey_scale_color_1,
    .box_group_disabilitas.active_box_menu_disabilitas .icon_svg_grey_scale_color_2,
    .box_group_disabilitas.active_box_menu_disabilitas .icon_svg_grey_scale_color_3,
    .box_group_disabilitas.active_box_menu_disabilitas .icon_svg_grey_scale_color_4,
    .box_group_disabilitas.active_box_menu_disabilitas .active_icon_svg_color,
    .box_group_disabilitas.active_box_menu_disabilitas .icon_contrast_black,
    .box_group_disabilitas.active_box_menu_disabilitas .icon_fill_svg_color_black,
    .box_group_disabilitas.active_box_menu_disabilitas .icon_fill_svg_color_white {
        fill: var(--white, #FFFFFF);
    }

    .box_group_disabilitas.active_box_menu_disabilitas .icon_svg_sroke_color,
    .box_group_disabilitas.active_box_menu_disabilitas .active_icon_svg_sroke_color {
        fill: none;
        stroke: var(--white, #FFFFFF);
    }

    .box_group_disabilitas.active_box_menu_disabilitas .circle_multi,
    .box_group_disabilitas.active_box_menu_disabilitas .circle_single {
        fill: none;
        stroke: var(--white, #FFFFFF);
    }

    .box_group_disabilitas .box_icon_disabilitas .hidden_svg {
        display: none;
    }

    /* Tile label */
    .box_text_bottom_disabilitas {
        font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif);
        font-size: 11px;
        font-weight: 600;
        line-height: 1.3;
        color: var(--ink, #0A0F1A);
        transition: color var(--ease);
    }

    .box_group_disabilitas.active_box_menu_disabilitas .box_text_bottom_disabilitas {
        color: var(--white, #FFFFFF);
    }

    /* Voice reading pulse */
    .box_group_disabilitas.speaking {
        animation: a11ySpeak 1s var(--ease) infinite;
    }

    @keyframes a11ySpeak {
        0%, 100% { box-shadow: 0 0 0 0 rgba(0, 53, 128, 0.45); }
        50% { box-shadow: 0 0 0 6px rgba(0, 53, 128, 0); }
    }

    /* Strips (level gauges) */
    .box_column_action_strip {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 6px;
    }

    .box_row_action_strip {
        display: flex;
        gap: 3px;
    }

    .strip_loading_process_v2,
    .strip_loading_process_v3,
    .strip_loading_process_v4,
    .strip_loading_unprocess_v2,
    .strip_loading_unprocess_v3,
    .strip_loading_unprocess_v4 {
        width: 8px;
        height: 4px;
        display: inline-block;
    }

    .strip_loading_process_v2,
    .strip_loading_process_v3,
    .strip_loading_process_v4 {
        background: var(--navy, #003580);
    }

    .strip_loading_unprocess_v2,
    .strip_loading_unprocess_v3,
    .strip_loading_unprocess_v4 {
        background: var(--border, #D8DCE3);
    }

    .box_group_disabilitas.active_box_menu_disabilitas .strip_loading_unprocess_v2,
    .box_group_disabilitas.active_box_menu_disabilitas .strip_loading_unprocess_v3,
    .box_group_disabilitas.active_box_menu_disabilitas .strip_loading_unprocess_v4 {
        background: rgba(255, 255, 255, 0.35);
    }

    /* Reset */
    .column_reset_disabilitas_menu {
        margin-top: 12px;
    }

    .row_persegi_reset {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid var(--border, #D8DCE3);
        border-radius: 0;
        background: var(--white, #FFFFFF);
        color: var(--ink, #0A0F1A);
        padding: 10px 12px;
        cursor: pointer;
        text-align: left;
        transition: background var(--ease), border-color var(--ease);
    }

    .row_persegi_reset:hover {
        background: var(--navy-tint, #E8EFF8);
        border-color: var(--navy, #003580);
    }

    .row_persegi_reset:focus-visible {
        outline: 2px solid var(--navy, #003580);
        outline-offset: 2px;
    }

    .icon_persegi_riset {
        display: flex;
        color: var(--mid, #6B7280);
    }

    .column_text_persegi_riset {
        font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif);
        font-size: 12px;
        font-weight: 600;
    }

    /* Footer / version */
    .column_cek_version_dsb {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid var(--border, #D8DCE3);
        text-align: center;
        color: var(--mid, #6B7280);
        font-size: 11px;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .layout_grid_disabilitas {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 480px) {
        .layout_grid_disabilitas {
            grid-template-columns: repeat(2, 1fr);
        }

        .accessibility-panel {
            width: 100%;
            max-width: 100vw;
            bottom: 60px;
            right: 0;
        }

        .accessibility-widget {
            right: 12px;
            bottom: 12px;
        }
    }
</style>